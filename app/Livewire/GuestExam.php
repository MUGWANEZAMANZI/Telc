<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Setting;
use App\Services\AnswerExplainer;
use App\Services\ExamGrader;
use App\Services\SpeakingPromptService;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Livewire\Component;

class GuestExam extends Component
{
    public string $selectedLevel = 'B1';

    public int $freeSectionLimit = 3;

    public bool $showLoginPrompt = false;

    /** @var array<int, string> */
    public array $completedSectionIds = [];

    /** @var array<int, string> */
    public array $noRetrySectionIds = [];

    public int $remainingSections = 4;

    public bool $hasReachedLimit = false;

    public ?int $selectedTemplateId = null;

    /** @var array<string, mixed> */
    public array $template = [];

    public ?int $activeSectionIndex = null;

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public ?int $nextSectionIndex = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public string $selectedTopic = '';

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

    public function mount(?int $templateId = null): void
    {
        if (auth()->check()) {
            if ($templateId) {
                $this->redirect(route('dashboard').'?start_exam='.$templateId);
            } else {
                $this->redirect(route('dashboard'));
            }

            return;
        }

        $this->freeSectionLimit = max(1, (int) Setting::value('free_section_limit', '3'));

        $this->loadCompletedFromCookie();
        $this->loadNoRetryFromCookie();

        if ($templateId) {
            $record = ExamTemplate::query()
                ->where('is_published', true)
                ->find($templateId);

            if ($record) {
                $status = $this->getTemplateFreeStatus($record);
                if (! $status['is_free']) {
                    session(['intended_enroll_exam' => $record->id]);
                    $this->redirect(route('register'));

                    return;
                }
                $this->selectedLevel = $record->level;
                $this->loadTemplate($record);

                return;
            }
        }

        $level = request()->query('level', '');
        if (in_array($level, ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'], true)) {
            $this->selectedLevel = $level;
        }

        $this->loadTemplateForLevel();
        $this->recalculateRemaining();
    }

    public function loadTemplate(ExamTemplate $record): void
    {
        $this->selectedTemplateId = $record->id;
        $data = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);
        $data['sections'] = collect($data['sections'] ?? [])->filter(function ($section) {
            return ! collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
        })->values()->all();
        $this->template = $data;
        $this->recalculateRemaining();
        $this->loadSpeakingTopics();
    }

    public function loadCompletedFromCookie(): void
    {
        $raw = request()->cookie('free_sections_completed');
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->completedSectionIds = is_array($decoded) ? $decoded : [];
        } else {
            $this->completedSectionIds = [];
        }
    }

    public function saveCompletedToCookie(): void
    {
        Cookie::queue('free_sections_completed', json_encode($this->completedSectionIds), 60 * 24 * 365);
    }

    public function loadNoRetryFromCookie(): void
    {
        $raw = request()->cookie('free_no_retry_sections');
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->noRetrySectionIds = is_array($decoded) ? $decoded : [];
        } else {
            $this->noRetrySectionIds = [];
        }
    }

    public function saveNoRetryToCookie(): void
    {
        Cookie::queue('free_no_retry_sections', json_encode($this->noRetrySectionIds), 60 * 24 * 365);
    }

    /**
     * @return array{is_free: bool, free_sections: int}
     */
    public function getTemplateFreeStatus(ExamTemplate $record): array
    {
        if (str_contains(strtolower($record->title), 'premium')) {
            return ['is_free' => false, 'free_sections' => 0];
        }

        if (str_contains(strtolower($record->title), 'test')) {
            $settingLimit = max(1, (int) Setting::value('free_section_limit', '3'));

            return ['is_free' => true, 'free_sections' => $settingLimit];
        }

        $all = ExamTemplate::query()
            ->where('is_published', true)
            ->where('exam_provider', $record->exam_provider)
            ->where('level', $record->level)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $index = array_search($record->id, $all);
        if ($index === 0) {
            $settingLimit = max(1, (int) Setting::value('free_section_limit', '3'));

            return ['is_free' => true, 'free_sections' => max($settingLimit, count($record->sections ?? []))];
        } elseif ($index === 1) {
            return ['is_free' => true, 'free_sections' => 2];
        }

        return ['is_free' => false, 'free_sections' => 0];
    }

    public function recalculateRemaining(): void
    {
        if (! $this->selectedTemplateId) {
            $this->remainingSections = 0;
            $this->hasReachedLimit = true;

            return;
        }

        $record = ExamTemplate::find($this->selectedTemplateId);
        if (! $record) {
            $this->remainingSections = 0;
            $this->hasReachedLimit = true;

            return;
        }

        $status = $this->getTemplateFreeStatus($record);
        $limit = $status['free_sections'];
        $completed = count($this->completedSectionIds);

        $this->freeSectionLimit = $limit;
        $this->remainingSections = (int) max(0, $limit - $completed);
        $this->hasReachedLimit = $this->remainingSections <= 0;
    }

    public function selectLevel(string $level): void
    {
        if (! in_array($level, ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'], true)) {
            return;
        }

        $this->selectedLevel = $level;
        $this->activeSectionIndex = null;
        $this->answers = [];
        $this->result = null;
        $this->started = false;
        $this->loadTemplateForLevel();
    }

    public function loadTemplateForLevel(): void
    {
        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->where('level', $this->selectedLevel)
            ->orderBy('id')
            ->first();

        if (! $record) {
            $this->template = [];
            $this->selectedTemplateId = null;

            return;
        }

        $this->loadTemplate($record);
    }

    /** @return list<string> */
    public function getLevels(): array
    {
        $order = ['A1' => 0, 'A2' => 1, 'B1' => 2, 'B2' => 3, 'C1' => 4, 'C2' => 5];

        return array_values(ExamTemplate::query()
            ->where('is_published', true)
            ->whereIn('level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])
            ->pluck('level')
            ->unique()
            ->sortBy(fn ($l) => $order[$l] ?? 99)
            ->toArray());
    }

    public function isSectionCompleted(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return in_array($sections[$sectionIndex]['id'], $this->completedSectionIds, true);
    }

    public function isNoRetrySection(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }
        $id = $sections[$sectionIndex]['id'];

        return in_array($id, $this->noRetrySectionIds, true);
    }

    public function canRetry(int $sectionIndex): bool
    {
        if (! $this->isSectionCompleted($sectionIndex)) {
            return true;
        }

        return ! $this->isNoRetrySection($sectionIndex);
    }

    public function sectionHasAudio(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return collect((array) ($sections[$sectionIndex]['elements'] ?? []))
            ->contains(fn ($el) => ($el['type'] ?? '') === 'audio');
    }

    public function sectionHasSpeaking(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return collect((array) ($sections[$sectionIndex]['elements'] ?? []))
            ->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
    }

    public function sectionType(int $sectionIndex): string
    {
        if ($this->sectionHasSpeaking($sectionIndex)) {
            return 'speaking';
        }
        if ($this->sectionHasAudio($sectionIndex)) {
            return 'listening';
        }
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return 'default';
        }
        $elements = $sections[$sectionIndex]['elements'] ?? [];
        $types = collect((array) $elements)->pluck('type')->unique()->values()->toArray();
        if (in_array('writing', $types, true)) {
            return 'writing';
        }
        if (in_array('inline_gap', $types, true) || in_array('interactive_text', $types, true)) {
            return 'reading';
        }

        return 'reading';
    }

    public function sectionStatus(int $sectionIndex): string
    {
        if ($this->isSectionCompleted($sectionIndex) && $this->isNoRetrySection($sectionIndex)) {
            return 'locked';
        }
        if ($this->isSectionCompleted($sectionIndex)) {
            return 'completed';
        }
        if ($this->activeSectionIndex === $sectionIndex && $this->started) {
            return 'in-progress';
        }

        return 'new';
    }

    public function startSection(int $index): void
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$index])) {
            return;
        }

        $this->recalculateRemaining();
        $isNew = ! $this->isSectionCompleted($index);

        if ($isNew && $this->hasReachedLimit) {
            return;
        }

        if ($this->isSectionCompleted($index) && $this->isNoRetrySection($index)) {
            return;
        }

        $this->activeSectionIndex = $index;
        $this->activeSection = $index;
        $this->answers = [];
        $this->result = null;
        $this->nextSectionIndex = null;
        $this->started = true;
        $this->remainingSeconds = max((int) ($sections[$index]['minutes'] ?? 1), 1) * 60;
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
        @set_time_limit(180);

        if (! $this->selectedTemplateId || $this->activeSectionIndex === null) {
            return;
        }

        $sections = $this->template['sections'] ?? [];
        $currentSection = $sections[$this->activeSectionIndex] ?? null;

        if (! $currentSection) {
            return;
        }

        $sectionId = $currentSection['id'];
        $isFirstCompletion = ! in_array($sectionId, $this->completedSectionIds, true);

        $tempCompleted = $this->completedSectionIds;
        if ($isFirstCompletion) {
            $tempCompleted[] = $sectionId;
        }

        $isLastSectionOfTemplate = ($this->activeSectionIndex === count($sections) - 1);
        $reachesFreeLimit = (count($tempCompleted) >= $this->freeSectionLimit);

        if ($isLastSectionOfTemplate || $reachesFreeLimit) {
            $this->started = false;

            // Save to session
            session([
                'guest_pending_attempt' => [
                    'template_id' => $this->selectedTemplateId,
                    'active_section' => $this->activeSectionIndex,
                    'answers' => $this->answers,
                    'completed_sections' => array_unique(array_merge($this->completedSectionIds, [$sectionId])),
                    'no_retry_sections' => array_unique(array_merge($this->noRetrySectionIds, [$sectionId])),
                ],
            ]);

            $this->showLoginPrompt = true;

            return;
        }

        $this->started = false;

        $this->result = app(ExamGrader::class)->grade(
            [$currentSection],
            $this->answers
        );

        $this->enrichExplanations();

        if ($isFirstCompletion) {
            $this->completedSectionIds[] = $sectionId;
            $this->saveCompletedToCookie();
            $this->recalculateRemaining();
        }

        if ($this->sectionHasSpeaking($this->activeSectionIndex) || $this->sectionHasAudio($this->activeSectionIndex)) {
            if (! in_array($sectionId, $this->noRetrySectionIds, true)) {
                $this->noRetrySectionIds[] = $sectionId;
                $this->saveNoRetryToCookie();
            }
        }

        ExamAttempt::create([
            'exam_template_id' => $this->selectedTemplateId,
            'user_id' => null,
            'answers' => $this->answers,
            'result' => $this->result,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        $this->nextSectionIndex = $this->findNextSectionToJump();
    }

    public function findNextSectionToJump(): ?int
    {
        $sections = $this->template['sections'] ?? [];
        $currentId = $sections[$this->activeSectionIndex]['id'] ?? null;

        foreach ($sections as $i => $section) {
            $id = $section['id'] ?? '';

            if ($id === $currentId) {
                continue;
            }

            $isCompleted = in_array($id, $this->completedSectionIds, true);
            $isLocked = $isCompleted && in_array($id, $this->noRetrySectionIds, true);

            if ($isLocked) {
                continue;
            }

            if (! $isCompleted && $this->hasReachedLimit) {
                continue;
            }

            return $i;
        }

        return null;
    }

    public function backToChapters(): void
    {
        $this->activeSectionIndex = null;
        $this->answers = [];
        $this->result = null;
        $this->nextSectionIndex = null;
        $this->started = false;
    }

    public function render(): View
    {
        $sections = $this->template['sections'] ?? [];

        return view('livewire.guest-exam', [
            'levels' => $this->getLevels(),
            'sections' => $sections,
            'currentSection' => ($this->activeSectionIndex !== null && isset($sections[$this->activeSectionIndex]))
                ? $sections[$this->activeSectionIndex]
                : null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $element
     * @return array<int, string>
     */
    public function optionsFor(array $element): array
    {
        $options = array_filter(array_map('trim', explode(',', (string) ($element['options'] ?? ''))));

        if ($options !== []) {
            return array_values($options);
        }

        return match ($element['type'] ?? '') {
            'true_false' => ['+', '-'],
            'multiple_choice' => ['a', 'b', 'c'],
            'matching' => range('a', 'j'),
            default => [],
        };
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

        $raw = json_decode((string) ($element['options'] ?? '[]'), true);

        return is_array($raw) ? array_values($raw) : [];
    }

    /** @var array<int, string> */
    public array $aiGeneratedTopics = [];

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
            $provider = 'telc'; // default guest provider
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
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

        if (! empty($this->aiGeneratedTopics)) {
            $remaining = array_values(array_filter($this->aiGeneratedTopics, fn ($t) => $t !== $topic));
            if (! empty($remaining)) {
                $this->unchosenTopic = $remaining[array_rand($remaining)];
            }
        }
        if (empty($this->unchosenTopic)) {
            $this->unchosenTopic = 'Das alternative Thema';
        }
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

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
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

        $provider = 'telc'; // default guest provider
        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);

        // SP-1a: student's initial presentation on chosen topic
        if (str_ends_with($elementId, '-SP-1a')) {
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse($systemInstruction."\n\n".$criteriaPrompt)
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
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse($systemInstruction."\n\n".$criteriaPrompt)
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

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                )
                ->generateContent(
                    "Aufgabenstellung/Thema: {$prompt}\n\n"
                    ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                    .'Generiere die nächste Antwort des Prüfers:'
                );

            $assistantReply = $result->text();

            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => $assistantReply,
            ];

            $this->speakingFeedback[$elementId] = $assistantReply;
            $this->answers[$elementId] = $transcribedText;
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
        // No-op for guest exam
    }

    private function enrichExplanations(): void
    {
        $explainer = app(AnswerExplainer::class);
        $batchItems = [];
        $detailsToUpdate = [];

        foreach ($this->result['details'] ?? [] as $key => $detail) {
            $element = collect((array) ($this->template['sections'] ?? []))
                ->pluck('elements')
                ->flatten(1)
                ->firstWhere('id', $detail['id']);

            if (! $element) {
                continue;
            }

            if (! empty($element['explanation'])) {
                $this->result['details'][$key]['explanation'] = $element['explanation'];

                continue;
            }

            $question = $element['label'] ?? '';
            $content = $element['content'] ?? '';
            if ($content !== '') {
                $question .= "\n".$content;
            }

            $correctAnswer = $element['answer'] ?? '';
            if ($correctAnswer === '' || $question === '') {
                $this->result['details'][$key]['explanation'] = '';

                continue;
            }

            $this->result['details'][$key]['explanation'] = '';
            $batchItems[] = [
                'id' => $detail['id'],
                'question' => $question,
                'correct_answer' => $correctAnswer,
                'student_answer' => is_array($detail['given'] ?? null) ? json_encode($detail['given']) : ($detail['given'] ?? null),
            ];
            $detailsToUpdate[$detail['id']] = $key;
        }

        if (! empty($batchItems)) {
            $explanations = $explainer->explainBatch($batchItems);
            foreach ($explanations as $id => $explanation) {
                if (isset($detailsToUpdate[$id])) {
                    $key = $detailsToUpdate[$id];
                    $this->result['details'][$key]['explanation'] = $explanation;
                }
            }
        }
    }

    public function repeat(): void
    {
        if ($this->activeSectionIndex !== null) {
            $sections = $this->template['sections'] ?? [];
            $currentSection = $sections[$this->activeSectionIndex] ?? null;
            if ($currentSection) {
                $sectionId = $currentSection['id'];
                $this->completedSectionIds = array_values(array_diff($this->completedSectionIds, [$sectionId]));
                $this->noRetrySectionIds = array_values(array_diff($this->noRetrySectionIds, [$sectionId]));
                $this->saveCompletedToCookie();
                $this->saveNoRetryToCookie();
                $this->recalculateRemaining();
            }

            $this->answers = [];
            $this->result = null;
            $this->started = true;
            $this->remainingSeconds = max((int) ($sections[$this->activeSectionIndex]['minutes'] ?? 1), 1) * 60;
        }
    }
}
