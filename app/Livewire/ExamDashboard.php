<?php

namespace App\Livewire;

use App\Models\ContactRequest;
use App\Models\ExamAttempt;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Setting;
use App\Models\User;
use App\Services\AnswerExplainer;
use App\Services\ExamGrader;
use App\Services\TelcTemplateFactory;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('TELC Exam Builder')]
class ExamDashboard extends Component
{
    use WithFileUploads;

    public mixed $audioUpload = null;

    public ?int $selectedTemplateId = null;

    public string $mode = 'overview';

    /** @var array<string, mixed> */
    public array $template = [];

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<int, string> */
    public array $aiGeneratedTopics = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public string $providerFilter = 'telc';

    public string $selectedTopic = '';

    public string $transitionMessage = '';

    public string $aiPresentationText = '';

    public string $unchosenTopic = '';

    public int $speakingStep = 1;

    public string $c1SelectedTopic = '';

    public string $c1AiResponseText = '';

    public string $c1AiResponseAudio = '';

    public int $c1SpeakingStep = 1;

    /** @var array<string, mixed> */
    public array $speakingFeedback = [];

    /** @var array<string, mixed> */
    public array $speakingProcessing = [];

    /** @var array<string, array<int, array<string, string>>> */
    public array $speakingHistory = [];

    public bool $showEnrollModal = false;

    public ?int $enrollTemplateId = null;

    public string $enrollModalError = '';

    public string $googleFormUrl = '';

    public string $newExamTitle = '';

    public string $newExamMessage = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $this->googleFormUrl = Setting::value('google_form_url', '');

        $this->mode = 'overview';
        $this->selectedTemplateId = null;
        $this->template = [];
    }

    public function render(): View
    {
        $templates = collect();
        if (in_array($this->mode, ['builder', 'student'], true)) {
            $templates = ExamTemplate::query()
                ->where('is_template', true)
                ->where('exam_provider', $this->providerFilter)
                ->orderByDesc('is_free')
                ->orderBy('level')
                ->get();
        }

        $cards = [];
        if ($this->mode === 'overview') {
            $grouped = ExamTemplate::query()
                ->where('is_template', false)
                ->get()
                ->groupBy(function ($item) {
                    $provider = $item->exam_provider ?: 'telc';
                    $level = $item->level ?: 'B1';

                    return $provider.'|'.$level;
                });

            foreach ($grouped as $key => $items) {
                [$provider, $level] = explode('|', $key);
                $publishedCount = $items->where('is_published', true)->count();
                $draftCount = $items->where('is_published', false)->count();

                $cards[] = [
                    'provider' => $provider,
                    'level' => $level,
                    'published_count' => $publishedCount,
                    'draft_count' => $draftCount,
                    'exams' => $items,
                ];
            }

            usort($cards, function ($a, $b) {
                $providerCompare = strcasecmp($a['provider'], $b['provider']);
                if ($providerCompare !== 0) {
                    return $providerCompare;
                }

                return strcasecmp($a['level'], $b['level']);
            });
        }

        $participants = collect();
        if ($this->mode === 'builder' && $this->selectedTemplateId) {
            $participants = ExamAttempt::query()
                ->where('exam_template_id', $this->selectedTemplateId)
                ->with('user')
                ->latest()
                ->get();
        }

        $contactRequests = collect();
        if ($this->mode === 'contacts') {
            $contactRequests = ContactRequest::query()->latest()->get();
        }

        return view('livewire.exam-dashboard', [
            'templates' => $templates,
            'cards' => $cards,
            'presets' => array_keys(TelcTemplateFactory::presets()),
            'elementTypes' => $this->elementTypes(),
            'metrics' => $this->metrics(),
            'participants' => $participants,
            'pendingPayments' => ExamEnrollment::query()
                ->where('status', 'pending')
                ->with(['user', 'examTemplate'])
                ->latest()
                ->get(),
            'contactRequests' => $contactRequests,
        ]);
    }

    public function approvePayment(int $enrollmentId): void
    {
        $enrollment = ExamEnrollment::findOrFail($enrollmentId);
        $enrollment->update([
            'status' => 'active',
            'payment_status' => 'manual_enrolled',
            'enrolled_at' => now(),
        ]);

        session()->flash('payment_message', "Zahlung für {$enrollment->user->name} freigegeben.");
    }

    public function rejectPayment(int $enrollmentId): void
    {
        $enrollment = ExamEnrollment::findOrFail($enrollmentId);
        // Delete receipt file if exists
        if ($enrollment->receipt_path) {
            Storage::disk('public')->delete($enrollment->receipt_path);
        }

        $enrollment->delete();

        session()->flash('payment_message', 'Zahlung abgelehnt und Antrag gelöscht.');
    }

    public function changeProvider(string $provider): void
    {
        $this->providerFilter = $provider;
        $template = ExamTemplate::query()->where('exam_provider', $this->providerFilter)->orderBy('level')->first();
        if ($template) {
            $this->loadTemplate($template->id);
        } else {
            $this->selectedTemplateId = null;
            $this->template = [];
        }
    }

    public function loadTemplate(int $id): void
    {
        $record = ExamTemplate::findOrFail($id);

        $this->selectedTemplateId = $record->id;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_published', 'exam_provider', 'price', 'is_free']);
        $this->providerFilter = $record->exam_provider ?? 'telc';
        $this->activeSection = 0;
        $this->answers = [];
        $this->result = null;
        $this->resetTimer();

        if ($this->mode === 'overview') {
            $this->mode = 'builder';
        }
    }

    public function editExam(int $id): void
    {
        $this->loadTemplate($id);
        $this->mode = 'builder';
    }

    public function createNewExam(): void
    {
        $this->selectedTemplateId = null;
        $this->template = [];
        $this->mode = 'builder';
    }

    public function deleteExam(): void
    {
        if ($this->selectedTemplateId) {
            $exam = ExamTemplate::find($this->selectedTemplateId);
            if ($exam) {
                $exam->delete();
                session()->flash('dashboard_message', "Exam '{$exam->title}' was deleted successfully.");
            }
            $this->selectedTemplateId = null;
            $this->template = [];
            $this->mode = 'overview';
        }
    }

    public function createFromPreset(string $level): void
    {
        $template = ExamTemplate::query()
            ->where('is_template', true)
            ->where('level', $level)
            ->where('exam_provider', $this->providerFilter)
            ->first();

        if ($template) {
            $this->duplicateTemplate($template, $template->title.' (Neu)');
            return;
        }

        $preset = TelcTemplateFactory::forLevel($level);
        $preset['title'] .= ' Custom';
        $preset['is_published'] = false;
        $preset['is_template'] = false;
        $preset['exam_provider'] = $this->providerFilter;

        $record = ExamTemplate::create($preset);

        $this->loadTemplate($record->id);
    }

    public function duplicateTemplateAsExam(): void
    {
        $template = ExamTemplate::find($this->selectedTemplateId);
        if (! $template) {
            return;
        }

        $this->duplicateTemplate($template, $this->newExamTitle !== '' ? $this->newExamTitle : $template->title.' (Neu)');
    }

    private function duplicateTemplate(ExamTemplate $template, string $title): void
    {
        $record = $template->replicate();
        $record->title = $title;
        $record->is_published = false;
        $record->is_template = false;

        $record->save();

        $this->newExamTitle = '';
        $this->newExamMessage = "Exam '{$record->title}' wurde aus dem Template '{$template->title}' erstellt. Titel anpassen und unten veröffentlichen.";
        $this->loadTemplate($record->id);
    }

    public function save(): void
    {
        $this->validate([
            'template.title' => ['required', 'string', 'max:255'],
            'template.level' => ['required', 'string', 'max:8'],
            'template.sections' => ['required', 'array', 'min:1'],
        ]);

        $record = ExamTemplate::updateOrCreate(
            ['id' => $this->selectedTemplateId],
            $this->template,
        );

        $this->selectedTemplateId = $record->id;
        $this->template['id'] = $record->id;

        $this->dispatch('exam-saved');
    }

    public function togglePublished(): void
    {
        $this->template['is_published'] = ! (bool) ($this->template['is_published'] ?? false);
        $this->save();
    }

    public function saveGoogleFormUrl(): void
    {
        Setting::updateOrCreate(
            ['key' => 'google_form_url'],
            ['value' => $this->googleFormUrl]
        );
        session(['google_form_saved' => true]);
    }

    public function addSection(): void
    {
        $this->template['sections'][] = [
            'id' => 'section-'.Str::uuid(),
            'title' => 'Neuer Prüfungsteil',
            'minutes' => 0,
            'elements' => [],
            'layout_style' => 'default',
        ];
    }

    public function removeSection(int $sectionIndex): void
    {
        unset($this->template['sections'][$sectionIndex]);
        $this->template['sections'] = array_values($this->template['sections']);
        $this->activeSection = min($this->activeSection, max(count($this->template['sections']) - 1, 0));
    }

    public function moveSection(int $from, int $to): void
    {
        $this->template['sections'] = $this->move($this->template['sections'], $from, $to);
        $this->activeSection = $to;
    }

    public function addElement(int $sectionIndex, string $type): void
    {
        $defaultOptions = match ($type) {
            'multiple_choice', 'true_false' => 'a,b,c,d',
            'inline_gap' => '[]',
            default => '',
        };

        $this->template['sections'][$sectionIndex]['elements'][] = [
            'id' => 'item-'.Str::uuid(),
            'type' => $type,
            'label' => $this->elementTypes()[$type] ?? 'Element',
            'content' => '',
            'options' => $defaultOptions,
            'answer' => '',
            'points' => in_array($type, ['text', 'image', 'audio', 'writing'], true) ? 0 : 1,
        ];
    }

    public function duplicateElement(int $sectionIndex, int $elementIndex): void
    {
        $element = $this->template['sections'][$sectionIndex]['elements'][$elementIndex];
        $element['id'] = 'item-'.Str::uuid();
        $element['label'] .= ' Kopie';

        array_splice($this->template['sections'][$sectionIndex]['elements'], $elementIndex + 1, 0, [$element]);
    }

    public function removeElement(int $sectionIndex, int $elementIndex): void
    {
        unset($this->template['sections'][$sectionIndex]['elements'][$elementIndex]);
        $this->template['sections'][$sectionIndex]['elements'] = array_values($this->template['sections'][$sectionIndex]['elements']);
    }

    public function moveElement(int $sectionIndex, int $from, int $to): void
    {
        $this->template['sections'][$sectionIndex]['elements'] = $this->move($this->template['sections'][$sectionIndex]['elements'], $from, $to);
    }

    public function uploadAudio(int $sectionIndex, int $elementIndex): void
    {
        $this->validate([
            'audioUpload' => ['required', 'file', 'mimes:mp3,wav,ogg,m4a,aac,flac,mpga', 'max:102400'],
        ], [
            'audioUpload.required' => 'Bitte wählen Sie zuerst eine Audio-Datei aus.',
            'audioUpload.mimes' => 'Ungültiges Audio-Format. Erlaubt sind MP3, WAV, OGG, M4A, AAC, FLAC.',
            'audioUpload.max' => 'Die Audio-Datei darf maximal 100 MB groß sein.',
        ]);

        $path = $this->audioUpload->store('', 'exam-audio');

        $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['content'] = route('exam.audio', ['path' => $path]);
        $this->audioUpload = null;
        $this->save();
    }

    public function syncGapIds(int $sectionIndex, int $elementIndex): void
    {
        $content = $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['content'] ?? '';
        $gaps = $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['gaps'] ?? [];

        preg_match_all('/\[\[gap:(\d+)\]\]/', $content, $matches);
        $foundIds = array_unique($matches[1]);

        $updated = [];
        foreach ($foundIds as $id) {
            if (isset($gaps[$id])) {
                $updated[$id] = $gaps[$id];
            } else {
                $updated[$id] = [
                    'type' => 'dropdown',
                    'options' => 'a:,b:,c:,d:',
                    'answer' => '',
                ];
            }
        }

        $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['gaps'] = $updated;
    }

    public function generateExplanation(int $sectionIndex, int $elementIndex): void
    {
        $element = $this->template['sections'][$sectionIndex]['elements'][$elementIndex] ?? null;
        if (! $element) {
            return;
        }

        $explainer = app(AnswerExplainer::class);
        $explanation = $explainer->explainElement($element);

        if ($explanation !== '') {
            $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['explanation'] = $explanation;
            $this->save();
        }
    }

    public function switchMode(string $mode): void
    {
        $this->mode = $mode;
        if ($mode === 'student' && ! empty($this->template['sections'])) {
            $this->startSection(0);
        }
        $this->resetTimer();
    }

    public function startSection(int $sectionIndex): void
    {
        $this->activeSection = $sectionIndex;
        $this->started = true;
        $minutes = (int) ($this->template['sections'][$sectionIndex]['minutes'] ?? 0);
        $this->remainingSeconds = max($minutes, 1) * 60;
    }

    public function tick(): void
    {
        if (! $this->started || $this->remainingSeconds <= 0) {
            return;
        }

        $this->remainingSeconds--;

        if ($this->remainingSeconds === 0) {
            $this->submit();
        }
    }

    public function submit(): void
    {
        $this->started = false;
        $this->result = app(ExamGrader::class)->grade($this->template['sections'], $this->answers);

        if ($this->selectedTemplateId) {
            ExamAttempt::create([
                'exam_template_id' => $this->selectedTemplateId,
                'user_id' => Auth::id(),
                'answers' => $this->answers,
                'result' => $this->result,
                'started_at' => now(),
                'submitted_at' => now(),
            ]);
        }
    }

    public function resetTimer(): void
    {
        $this->started = false;
        $this->remainingSeconds = max((int) ($this->template['sections'][$this->activeSection]['minutes'] ?? 0), 1) * 60;
    }

    /**
     * @return array<string, string>
     */
    public function elementTypes(): array
    {
        return [
            'text' => 'Text / Lesetext',
            'image' => 'Bildplatzhalter',
            'audio' => 'Audioquelle',
            'multiple_choice' => 'Multiple Choice',
            'matching' => 'Zuordnung',
            'fill_blank' => 'Lückentext',
            'inline_gap' => 'Lückentext (inline)',
            'interactive_text' => 'Interaktiver Text',
            'true_false' => 'Richtig / Falsch',
            'writing' => 'Schreibfeld',
            'speaking' => 'Sprechen (KI)',
        ];
    }

    /**
     * @param  array<string, mixed>  $element
     * @return array<int, array<string, mixed>>
     */
    public function gapsFor(array $element): array
    {
        if (($element['type'] ?? '') !== 'inline_gap') {
            return [];
        }

        $raw = $element['options'] ?? '[]';
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }

        return is_array($raw) ? array_values($raw) : [];
    }

    /**
     * @param  array<string, mixed>  $element
     * @return list<string>
     */
    public function optionsFor(array $element): array
    {
        $options = array_filter(array_map('trim', explode(',', (string) ($element['options'] ?? ''))));
        if ($options !== []) {
            return array_values($options);
        }

        return match ($element['type'] ?? '') {
            'true_false' => ['+', '-'],
            'multiple_choice' => ['a', 'b', 'c', 'd'],
            'matching' => range('a', 'j'),
            default => [],
        };
    }

    public function setGapAnswer(string $elementId, int $gapIndex, string $value): void
    {
        if (! isset($this->answers[$elementId]) || ! is_array($this->answers[$elementId])) {
            $this->answers[$elementId] = [];
        }
        $this->answers[$elementId][(string) $gapIndex] = $value;
    }

    public function chooseTopic(string $topic, string $elementId = ''): void
    {
        $this->selectedTopic = $topic;
        $this->speakingStep = 1;
        $speakingIds = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'speaking')
            ->pluck('id');

        if ($elementId === '') {
            $elementId = $speakingIds->first(fn ($id) => str_ends_with((string) $id, '-SP-1a')) ?? 'SP-1a';
        }

        foreach ($speakingIds as $sid) {
            $this->speakingHistory[(string) $sid] = [];
        }

        $this->speakingHistory[$elementId] = [];
        $this->answers[$elementId] = '';
    }

    public function loadAiPresentation(): void
    {
        $this->generateAiPresentation($this->unchosenTopic);
    }

    public function generateAiPresentation(string $unchosenTopic, string $sp1bId = ''): void
    {
        if ($sp1bId === '') {
            $elements = collect((array) ($this->template['sections'] ?? []))
                ->pluck('elements')
                ->flatten(1);
            $sp1bEl = $elements->firstWhere('id', 'C1-SP-1b')
                ?? $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1b'));
            $sp1bId = $sp1bEl['id'] ?? 'SP-1b';
        }

        try {
            $this->speakingProcessing[$sp1bId] = true;

            $prompt = "Schreibe einen gut strukturierten Vortrag (Einleitung, Hauptteil, Eigene Meinung, Schluss/Fazit) zum Thema: \"{$unchosenTopic}\". Der Vortrag soll klar und verständlich sein, mit einer deutlichen Argumentationslinie.";

            $result = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                ->withSystemInstruction(Content::parse('Du bist ein TELC-Prüfungskandidat, der einen Vortrag hält. Sprich den Lernenden direkt an.'))
                ->generateContent($prompt);

            $this->aiPresentationText = $result->text();

            $this->speakingHistory[$sp1bId][] = [
                'role' => 'assistant',
                'text' => $this->aiPresentationText,
            ];

            $this->answers[$sp1bId] = $this->speakingHistory[$sp1bId];
            $this->speakingStep = 2;
            $this->saveProgress();
        } catch (\Throwable $e) {
            $this->aiPresentationText = 'Fehler bei der Vortragserstellung: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$sp1bId] = false;
        }
    }

    public function analyzeSpeaking(string $elementId, string $transcribedText): void
    {
        $this->speakingProcessing[$elementId] = true;
        $this->speakingFeedback[$elementId] = '';

        if (! isset($this->speakingHistory[$elementId])) {
            $this->speakingHistory[$elementId] = [];
        }

        $this->speakingHistory[$elementId][] = [
            'role' => 'user',
            'text' => $transcribedText,
        ];

        $element = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->firstWhere('id', $elementId);

        $prompt = $element['content'] ?? '';
        $maxPoints = (int) ($element['points'] ?? 24);
        $level = $this->template['level'] ?? 'C1';

        if (str_ends_with($elementId, '-SP-1a')) {
            $criteriaPrompt = "Bewerte den Vortrag des Lernenden zu dem von ihm gewählten Thema.\nKriterien:\n- Inhaltliche Angemessenheit & Relevanz (0-2 Punkte)\n- Struktur und logischer Aufbau (0-2 Punkte)\n- Ausdrucksweise und Kohärenz (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-1b')) {
            $criteriaPrompt = "Bewerte die Reaktion des Lernenden auf den Vortrag.\nKriterien:\n- Verständnis des gehörten Vortrags (0-2 Punkte)\n- Relevanz und Qualität der Anschlussfragen/Kommentare (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-2')) {
            $criteriaPrompt = "Bewerte die Diskussionsleistung des Lernenden.\nKriterien:\n- Argumentation und Eingehen auf den Gesprächspartner (0-2 Punkte)\n- Interaktionsfähigkeit (0-2 Punkte)\n- Syntaktische und inhaltliche Angemessenheit (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } else {
            $criteriaPrompt = "Bewerte die mündliche Leistung des Lernenden.\nBitte gib eine Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        }

        // SP-1a: student's initial presentation on chosen topic
        if (str_ends_with($elementId, '-SP-1a')) {
            try {
                $levelLabel = "TELC-{$level}";
                $result = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein offizieller {$levelLabel}-Prüfer. "
                            .'Bewerte den mündlichen Vortrag des Lernenden zu dem von ihm gewählten Thema. '
                            .$criteriaPrompt
                        )
                    )
                    ->generateContent(
                        "Gewähltes Thema: {$this->selectedTopic}\n\n"
                        ."Transkription des Vortrags:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Präsentation anhand der vier Kriterien:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $transcribedText;

                // Extract the unchosen topic from the element content
                $content = $element['content'] ?? '';
                $topics = self::parseTopicsFromContent($content);
                $this->unchosenTopic = '';
                if (count($topics) === 2) {
                    $this->unchosenTopic = $topics[0] === $this->selectedTopic ? $topics[1] : $topics[0];
                }
                if ($this->unchosenTopic === '') {
                    $this->unchosenTopic = 'Das alternative Thema';
                }

                $this->speakingStep = 2;
                $this->transitionMessage = 'Teil 1a ist abgeschlossen. Sehr gut! Wir gehen jetzt zu Teil 1b über. Hören Sie sich bitte den KI-Vortrag an.';
                $this->saveProgress();
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // SP-1b: student's reaction/summary of the AI presentation
        if (str_ends_with($elementId, '-SP-1b')) {
            try {
                $levelLabel = "TELC-{$level}";
                $result = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein offizieller {$levelLabel}-Prüfer. "
                            .'Der Lernende reagiert auf deine Zusammenfassung / deinen Vortrag. '
                            .'Bewerte seine Reaktion darauf. Achte auf logischen Bezug, Sprachgefühl und syntaktische Korrektheit. '
                            .$criteriaPrompt
                        )
                    )
                    ->generateContent(
                        "AI Vortrag:\n{$this->aiPresentationText}\n\n"
                        ."Antwort des Lernenden:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Reaktion anhand der vier Kriterien:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $transcribedText;
                $this->speakingStep = 3;
                $this->transitionMessage = 'Teil 1b ist abgeschlossen. Sehr gut! Wir gehen jetzt zur Diskussion über.';
                $this->saveProgress();
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // SP-2: interactive discussion handler
        $historyContext = '';
        foreach ($this->speakingHistory[$elementId] as $turn) {
            $roleLabel = $turn['role'] === 'user' ? 'Lernender' : 'Prüfer';
            $historyContext .= "{$roleLabel}: {$turn['text']}\n";
        }

        $userTurnCount = collect($this->speakingHistory[$elementId])->where('role', 'user')->count();
        $timerEnded = $this->remainingSeconds <= 0;
        $isLastTurn = $timerEnded || $userTurnCount >= 3;

        if ($isLastTurn) {
            $generationPrompt = "Aufgabenstellung/Thema: {$prompt}\n\n"
                ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                ."WICHTIG: Die Zeit für die Diskussion (Teil 2) ist abgelaufen bzw. die maximale Rundenanzahl ist erreicht. Generiere als Prüfer eine kurze, höfliche Verabschiedung zur Diskussion (z.B. 'Vielen Dank für das Gespräch, damit beenden wir die Diskussion.') und gib am Ende die finale Gesamtbewertung ab im Format 'SCORE: X/{$maxPoints}'. Stelle KEINE weiteren Fragen mehr!";
        } else {
            $generationPrompt = "Aufgabenstellung/Thema: {$prompt}\n\n"
                ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                .'Generiere die nächste Antwort des Prüfers:';
        }

        try {
            $levelLabel = "TELC-{$level}";
            $result = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                ->withSystemInstruction(
                    Content::parse(
                        "Du bist ein {$levelLabel}-Prüfer für die mündliche Prüfung. "
                        .'Unterhalte dich interaktiv mit dem Lernenden über das gewählte Thema. '
                        .$criteriaPrompt
                    )
                )
                ->generateContent($generationPrompt);

            $assistantReply = $result->text();

            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => $assistantReply,
            ];

            $this->speakingFeedback[$elementId] = $assistantReply;
            $this->answers[$elementId] = $transcribedText;
            if ($isLastTurn || str_contains($assistantReply, 'SCORE:')) {
                $this->dispatch('stop-all-speech');
            }
            $this->saveProgress();
        } catch (\Throwable $e) {
            array_pop($this->speakingHistory[$elementId]);
            $this->speakingFeedback[$elementId] = 'Fehler bei der Analyse: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$elementId] = false;
        }
    }

    /**
     * @return array<int, string>
     */
    private static function parseTopicsFromContent(string $content): array
    {
        $topics = [];
        if (preg_match_all('/(\d+)\.\s*(.+?)\s*(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $content, $matches)) {
            foreach ($matches[2] as $m) {
                $trimmed = trim($m);
                if ($trimmed !== '') {
                    $topics[] = $trimmed;
                }
            }
        }
        if (count($topics) < 2) {
            $topics = [];
            if (preg_match_all('/Thema\s*\d+\s*[:.]?\s*(.+?)\s*(?=\s*\/\s*Thema|\s*$)/ui', $content, $matches)) {
                foreach ($matches[1] as $m) {
                    $trimmed = trim($m);
                    if ($trimmed !== '') {
                        $topics[] = $trimmed;
                    }
                }
            }
        }

        return $topics;
    }

    public function saveProgress(): void
    {
        // No-op for admin preview dashboard
    }

    /**
     * @return array<string, int|float>
     */
    private function metrics(): array
    {
        $today = now()->startOfDay();

        return [
            'students' => User::query()->where('role', 'student')->count(),
            'daily_logged_in' => User::query()->where('role', 'student')->where('last_login_at', '>=', $today)->count(),
            'attempts' => ExamAttempt::query()->count(),
            'average_score' => round((float) ExamAttempt::query()->avg('result->percentage'), 2),
        ];
    }

    /**
     * @param  list<mixed>  $items
     * @return list<mixed>
     */
    private function move(array $items, int $from, int $to): array
    {
        if (! isset($items[$from]) || $to < 0 || $to >= count($items)) {
            return $items;
        }

        $item = $items[$from];
        array_splice($items, $from, 1);
        array_splice($items, $to, 0, [$item]);

        return $items;
    }

    public function toggleContactStatus(int $id, string $status): void
    {
        ContactRequest::findOrFail($id)->update(['status' => $status]);
    }

    public function hasActiveSubscription(): bool
    {
        return true;
    }

    public function isFullyEnrolled(int $templateId): bool
    {
        return true;
    }

    /**
     * @return array{is_free: bool, free_sections: int}
     */
    public function getTemplateFreeStatus(ExamTemplate $record): array
    {
        if ($record->is_free) {
            return ['is_free' => true, 'free_sections' => count($record->sections ?? [])];
        }

        return ['is_free' => false, 'free_sections' => 0];
    }

    public function loadSpeakingTopics(): void
    {
        $elements = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1);
        $sp1aEl = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1a'));

        $spContent = $sp1aEl['content'] ?? '';
        $parsedTopics = [];
        if (preg_match_all('/(\d+)\.\s*(.+?)(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $spContent, $topicMatches)) {
            foreach ($topicMatches[2] as $t) {
                $parsedTopics[] = trim($t);
            }
        }

        if (count($parsedTopics) >= 2) {
            $this->aiGeneratedTopics = $parsedTopics;
        } else {
            $level = $this->template['level'] ?? 'C1';
            $provider = $this->template['exam_provider'] ?? 'telc';
            try {
                $result = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                    ->generateContent(
                        "Generiere zwei verschiedene, niveauangemessene und typische Sprechthemen für eine mündliche {$provider} {$level} Prüfung.\n"
                        ."Antworte NUR im folgenden JSON-Format:\n"
                        .'["Thema 1", "Thema 2"]'
                    );

                $text = trim($result->text());
                if (str_starts_with($text, '```json')) {
                    $text = substr($text, 7, -3);
                }
                $decoded = json_decode(trim($text), true);
                if (is_array($decoded) && count($decoded) >= 2) {
                    $this->aiGeneratedTopics = $decoded;
                }
            } catch (\Throwable $e) {
                $this->aiGeneratedTopics = ['Freies Thema A', 'Freies Thema B'];
            }
        }
    }

    public function chooseRandomTopic(string $elementId = ''): void
    {
        if (empty($this->aiGeneratedTopics)) {
            $this->loadSpeakingTopics();
        }
        if (! empty($this->aiGeneratedTopics)) {
            $topic = $this->aiGeneratedTopics[array_rand($this->aiGeneratedTopics)];
            $this->chooseTopic($topic, $elementId);
        }
    }

    public function submitSection(): void
    {
        $this->started = false;
        $sections = $this->template['sections'] ?? [];
        $currentSection = $sections[$this->activeSection] ?? null;
        if ($currentSection) {
            $this->result = app(ExamGrader::class)->grade([$currentSection], $this->answers);
        }
    }

    public function exitExam(): void
    {
        $this->dispatch('stop-all-speech');
        $this->started = false;
        $this->result = null;
        $this->answers = [];
        $this->activeSection = 0;
    }
}
