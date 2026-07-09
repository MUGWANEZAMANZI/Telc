@php
    $level = $template['level'] ?? 'C1';
    $elements = collect($template['sections'] ?? [])->pluck('elements')->flatten(1);
    $sp1aEl = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1a'));
    $sp1bEl = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1b'));
    $sp2El = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-2'));
    $sp1aId = $sp1aEl['id'] ?? 'SP-1a';
    $sp1bId = $sp1bEl['id'] ?? 'SP-1b';
    $sp2Id = $sp2El['id'] ?? 'SP-2';
    $sp1aPoints = $sp1aEl['points'] ?? 6;
    $sp1bPoints = $sp1bEl['points'] ?? 4;
    $sp2Points = $sp2El['points'] ?? 6;

    // Parse topics from SP-1a content (format: "1. Thema A / 2. Thema B")
    $spContent = $sp1aEl['content'] ?? '';
    $parsedTopics = [];
    if (preg_match_all('/(\d+)\.\s*(.+?)(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $spContent, $topicMatches)) {
        foreach ($topicMatches[2] as $t) {
            $parsedTopics[] = trim($t);
        }
    }
    if (count($parsedTopics) < 2) {
        $parsedTopics = ['Thema 1', 'Thema 2'];
    }
    $discussionTopic = $sp2El['content'] ?? 'Diskussionsthema';
@endphp

<style>
    .markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4 {
        color: #1a1a1a;
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .markdown-content h3 {
        font-size: 1.05rem;
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 0.25rem;
        margin-top: 1.25rem;
    }
    .markdown-content p {
        margin-top: 0;
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }
    .markdown-content ul, .markdown-content ol {
        margin-top: 0;
        margin-bottom: 0.75rem;
        padding-left: 1.25rem;
    }
    .markdown-content li {
        margin-bottom: 0.25rem;
    }
    .markdown-content strong {
        color: #111;
        font-weight: 600;
    }
    .markdown-content hr {
        border: 0;
        border-top: 1px solid #e5e5e5;
        margin: 1.25rem 0;
    }
</style>

<div class="speaking-flow-container" style="margin-bottom:2rem;">

    {{-- TOPIC SELECTION (only if no topic chosen yet) --}}
    @if (!$selectedTopic)
        <div style="background: #ffffff; border: 1px solid #e5e5e5; padding: 2rem; border-radius: 6px; text-align: center; margin-bottom: 2rem;">
            <h4 style="font-size: 1.3rem; margin-bottom: 1rem;">Wählen Sie ein Präsentationsthema</h4>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem;">Das andere Thema wird für den KI-Vortrag verwendet.</p>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 500px; margin: 0 auto;">
                @foreach ($parsedTopics as $i => $topic)
                    <button type="button" wire:click="chooseTopic(@js($topic),@js($sp1aId))"
                        style="background:#fff;border:1px solid #ccc;padding:1rem;text-align:left;font-size:0.9rem;border-radius:4px;cursor:pointer;font-weight:600;">
                        Thema {{ chr(65 + $i) }}: {{ $topic }}
                    </button>
                @endforeach
            </div>
        </div>
    @else
        {{-- STEP 1a: User Presentation --}}
        <div class="speaking-task" style="border:1px solid #e5e5e5;padding:2rem;border-radius:6px;background:#fff;margin-bottom:2rem;"
             x-data="{
                 isRecording: false,
                 recognition: null,
                 transcript: @js($speakingHistory[$sp1aId][0]['text'] ?? ''),
                 initRecognition() {
                     const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                     if (!SR) return;
                     this.recognition = new SR();
                     this.recognition.lang = 'de-DE';
                     this.recognition.continuous = true;
                     this.recognition.interimResults = true;
                     this.recognition.onresult = (e) => {
                         let t = '';
                         for (let i = e.resultIndex; i < e.results.length; i++) t += e.results[i][0].transcript;
                         this.transcript = t;
                     };
                     this.recognition.onerror = () => this.stopRecording();
                 },
                 startRecording() {
                     if (!this.recognition) this.initRecognition();
                     if (!this.recognition) return;
                     this.isRecording = true;
                     this.recognition.start();
                 },
                 stopRecording() {
                     this.isRecording = false;
                     if (this.recognition) this.recognition.stop();
                 },
                 toggleRecording() { this.isRecording ? this.stopRecording() : this.startRecording(); },
                 sendReply() {
                     $wire.call('analyzeSpeaking', '{{ $sp1aId }}', this.transcript);
                 }
             }">
            <div style="border-bottom:1px solid #e5e5e5;padding-bottom:0.75rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;text-transform:uppercase;font-size:0.85rem;color:#d8232a;">1a: Präsentation (Max. {{ $sp1aPoints }} Punkte)</span>
                    <span style="background:#f7f7f7;padding:0.25rem 0.5rem;font-size:0.8rem;border-radius:4px;">Status: Schritt 1 / 3</span>
                @if (!empty($speakingHistory[$sp1aId] ?? []))
                    <span style="background:#e8f5e9;padding:0.25rem 0.5rem;font-size:0.8rem;border-radius:4px;color:#2e7d32;">✓ Abgeschlossen</span>
                @endif
            </div>
            <p style="font-size:0.95rem;color:#4a4a4a;background:#fafafa;padding:1rem;border-left:3px solid #d8232a;margin-bottom:1rem;text-align:left;">
                <strong>Ihr Thema:</strong> {{ $selectedTopic }}
            </p>

            @if (!empty($speakingHistory[$sp1aId] ?? []))
                <div style="background:#f7f9fa;border:1px solid #e5e5e5;border-radius:4px;padding:1.25rem;margin-bottom:1.5rem;text-align:left;">
                    @foreach ($speakingHistory[$sp1aId] as $turn)
                        <div style="margin-bottom:0.5rem;">
                            <strong style="font-size:0.8rem;color:#666;">{{ $turn['role'] === 'user' ? 'Ihr Vortrag' : 'Bewertung' }}:</strong>
                            @if ($turn['role'] === 'user')
                                <p style="font-size:0.9rem;white-space:pre-wrap;margin-top:0.25rem;">{{ $turn['text'] }}</p>
                            @else
                                <div class="markdown-content" style="font-size:0.9rem;line-height:1.6;margin-top:0.25rem;">
                                    {!! \Illuminate\Support\Str::markdown($turn['text']) !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            @if (empty($speakingHistory[$sp1aId] ?? []))
                <div style="display:flex;gap:1rem;align-items:center;">
                    <button type="button" @click="toggleRecording" class="speaking-record-btn"
                        :style="isRecording ? 'background:#1a1a1a;' : 'background:#d8232a;'"
                        style="color:#fff;border:none;padding:0.85rem 1.5rem;border-radius:4px;font-weight:600;cursor:pointer;">
                        <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Vortrag aufnehmen'"></span>
                    </button>
                    <input type="text" x-model="transcript" placeholder="Transkript eingeben..."
                        style="flex:1;border:1px solid #ccc;padding:0.85rem 1rem;border-radius:4px;font-size:0.9rem;"
                        @keydown.enter.prevent="sendReply">
                    <button type="button" @click="sendReply" class="telc-button primary"
                        :disabled="$wire.speakingProcessing['{{ $sp1aId }}'] || !transcript.trim()"
                        style="padding:0.85rem 1.5rem;white-space:nowrap;">
                        Abschicken
                    </button>
                </div>
            @else
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:0.75rem 1rem;border-radius:4px;font-size:0.9rem;color:#15803d;font-weight:600;text-align:left;">
                    ✓ Präsentation abgeschlossen und bewertet.
                </div>
            @endif
        </div>

        {{-- STEP 1b: AI Presentation on Unchosen Topic --}}
        @if ($speakingStep >= 2)
            @if (empty($aiPresentationText))
                {{-- Auto-trigger loadAiPresentation via Alpine x-init when this div first mounts --}}
                <div style="background:#fff;border:1px solid #e5e5e5;padding:2rem;border-radius:6px;text-align:center;margin-bottom:2rem;"
                     x-init="$wire.loadAiPresentation()">
                    <h4 style="font-size:1.1rem;margin-bottom:0.75rem;">KI-Vortrag wird generiert...</h4>
                    <p style="font-size:0.9rem;color:#666;margin-bottom:1.5rem;">
                        Das KI-System erstellt einen Vortrag zum alternativen Thema. Bitte warten Sie einen Moment.
                    </p>
                    <div style="display:inline-block;width:2rem;height:2rem;border:3px solid #e5e5e5;border-top-color:#d8232a;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
                </div>
            @else
                <div class="speaking-task" style="border:1px solid #e5e5e5;padding:2rem;border-radius:6px;background:#fff;margin-bottom:2rem;"
                     x-data="{
                         isSynthesizing: false,
                         playAudio() {
                             const u = new SpeechSynthesisUtterance(@js($aiPresentationText));
                             u.lang = 'de-DE'; u.rate = 0.95;
                             u.onstart = () => { this.isSynthesizing = true; };
                             u.onend = () => { this.isSynthesizing = false; };
                             window.speechSynthesis.speak(u);
                         },
                         stopAudio() { window.speechSynthesis.cancel(); this.isSynthesizing = false; }
                     }">
                    <div style="border-bottom:1px solid #e5e5e5;padding-bottom:0.75rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-weight:700;text-transform:uppercase;font-size:0.85rem;color:#d8232a;">1b: KI-Vortrag & Ihre Reaktion (Max. {{ $sp1bPoints }} Punkte)</span>
                        <span style="background:#f7f7f7;padding:0.25rem 0.5rem;font-size:0.8rem;border-radius:4px;">Status: Schritt 2 / 3</span>
                    </div>
                    <p style="font-size:0.9rem;color:#666;margin-bottom:1rem;text-align:left;">
                        Hören Sie den KI-Vortrag zum anderen Thema und fassen Sie ihn zusammen.
                    </p>
                    <div style="background:#fafafa;border:1px solid #ccc;border-radius:4px;padding:1.5rem;margin-bottom:1.5rem;text-align:left;">
                        <div style="display:flex;gap:0.75rem;align-items:center;margin-bottom:1rem;">
                            <button type="button" @click="isSynthesizing ? stopAudio() : playAudio()"
                                style="background:#1a1a1a;color:#fff;border:none;padding:0.5rem 1rem;border-radius:4px;font-size:0.85rem;font-weight:600;cursor:pointer;">
                                <span x-show="!isSynthesizing">🔊 Vortrag anhören</span>
                                <span x-show="isSynthesizing" style="display:none;">⏹ Stoppen</span>
                            </button>
                            <span x-show="isSynthesizing" style="font-size:0.85rem;color:#d8232a;font-weight:600;">Spielt Audio...</span>
                        </div>
                        <strong style="display:block;font-size:0.85rem;margin-bottom:0.5rem;">KI-Vortrag:</strong>
                        <div class="markdown-content" style="font-size:0.9rem;line-height:1.6;">
                            {!! \Illuminate\Support\Str::markdown($aiPresentationText) !!}
                        </div>
                    </div>

                    {{-- User reply to AI presentation --}}
                    @if ($speakingStep === 2)
                        <div x-data="{
                            isRecording: false, recognition: null, transcript: '',
                            initRecognition() {
                                const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                                if (!SR) return;
                                this.recognition = new SR(); this.recognition.lang = 'de-DE';
                                this.recognition.continuous = true; this.recognition.interimResults = true;
                                this.recognition.onresult = (e) => {
                                    let t = '';
                                    for (let i = e.resultIndex; i < e.results.length; i++) t += e.results[i][0].transcript;
                                    this.transcript = t;
                                };
                                this.recognition.onerror = () => this.stopRecording();
                            },
                            startRecording() { if (!this.recognition) this.initRecognition(); if (!this.recognition) return; this.isRecording = true; this.recognition.start(); },
                            stopRecording() { this.isRecording = false; if (this.recognition) this.recognition.stop(); },
                            toggleRecording() { this.isRecording ? this.stopRecording() : this.startRecording(); },
                            sendReply() { $wire.call('analyzeSpeaking', '{{ $sp1bId }}', this.transcript); }
                        }">
                            <div style="border-top:1px solid #e5e5e5;padding-top:1.5rem;display:flex;gap:1rem;align-items:center;">
                                <button type="button" @click="toggleRecording" class="speaking-record-btn"
                                    :style="isRecording ? 'background:#1a1a1a;' : 'background:#d8232a;'"
                                    style="color:#fff;border:none;padding:0.85rem 1.5rem;border-radius:4px;font-weight:600;cursor:pointer;">
                                    <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Antwort aufnehmen'"></span>
                                </button>
                                <input type="text" x-model="transcript" placeholder="Zusammenfassung eingeben..."
                                    style="flex:1;border:1px solid #ccc;padding:0.85rem 1rem;border-radius:4px;font-size:0.9rem;"
                                    @keydown.enter.prevent="sendReply">
                                <button type="button" @click="sendReply" class="telc-button primary"
                                    :disabled="$wire.speakingProcessing['{{ $sp1bId }}'] || !transcript.trim()"
                                    style="padding:0.85rem 1.5rem;white-space:nowrap;">
                                    Abschicken
                                </button>
                            </div>
                        </div>
                    @else
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:0.75rem 1rem;border-radius:4px;font-size:0.9rem;color:#15803d;font-weight:600;text-align:left;margin-top:1rem;">
                            ✓ Teil 1b abgeschlossen und bewertet.
                        </div>
                    @endif

                    @if (!empty($speakingFeedback[$sp1bId] ?? ''))
                        <div style="margin-top:1rem;padding:1rem;background:#f7f9fa;border:1px solid #e5e5e5;border-radius:4px;text-align:left;">
                            <strong style="font-size:0.85rem;color:#666;">Bewertung:</strong>
                            <div class="markdown-content" style="font-size:0.9rem;line-height:1.6;margin-top:0.25rem;">
                                {!! \Illuminate\Support\Str::markdown($speakingFeedback[$sp1bId]) !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif

        {{-- STEP 2: Discussion (SP-2) --}}
        @if ($speakingStep >= 3)
            <div class="speaking-task" style="border:1px solid #e5e5e5;padding:2rem;border-radius:6px;background:#fff;margin-bottom:2rem;"
                 x-data="{
                     isRecording: false, recognition: null, transcript: '',
                     initRecognition() {
                         const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                         if (!SR) return;
                         this.recognition = new SR(); this.recognition.lang = 'de-DE';
                         this.recognition.continuous = true; this.recognition.interimResults = true;
                         this.recognition.onresult = (e) => {
                             let t = '';
                             for (let i = e.resultIndex; i < e.results.length; i++) t += e.results[i][0].transcript;
                             this.transcript = t;
                         };
                         this.recognition.onerror = () => this.stopRecording();
                     },
                     startRecording() { if (!this.recognition) this.initRecognition(); if (!this.recognition) return; this.isRecording = true; this.recognition.start(); },
                     stopRecording() { this.isRecording = false; if (this.recognition) this.recognition.stop(); },
                     toggleRecording() { this.isRecording ? this.stopRecording() : this.startRecording(); },
                     sendReply() { $wire.call('analyzeSpeaking', '{{ $sp2Id }}', this.transcript); }
                 }">
                <div style="border-bottom:1px solid #e5e5e5;padding-bottom:0.75rem;margin-bottom:1.25rem;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;text-transform:uppercase;font-size:0.85rem;color:#d8232a;">2: Diskussion (Max. {{ $sp2Points }} Punkte)</span>
                    <span style="background:#f7f7f7;padding:0.25rem 0.5rem;font-size:0.8rem;border-radius:4px;">Status: Schritt 3 / 3</span>
                </div>
                <p style="font-size:0.9rem;color:#4a4a4a;background:#fafafa;padding:1rem;border-left:3px solid #d8232a;margin-bottom:1rem;text-align:left;">
                    {{ $discussionTopic }}
                </p>

                @if (!empty($speakingHistory[$sp2Id] ?? []))
                    <div style="background:#f7f9fa;border:1px solid #e5e5e5;border-radius:4px;padding:1.25rem;margin-bottom:1.5rem;text-align:left;max-height:300px;overflow-y:auto;">
                        @foreach ($speakingHistory[$sp2Id] as $turn)
                            <div style="margin-bottom:0.5rem;">
                                <strong style="font-size:0.8rem;color:#666;">{{ $turn['role'] === 'user' ? 'Sie' : 'KI-Prüfer' }}:</strong>
                                @if ($turn['role'] === 'user')
                                    <p style="font-size:0.9rem;white-space:pre-wrap;margin-top:0.1rem;">{{ $turn['text'] }}</p>
                                @else
                                    <div class="markdown-content" style="font-size:0.9rem;line-height:1.6;margin-top:0.1rem;">
                                        {!! \Illuminate\Support\Str::markdown($turn['text']) !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div style="display:flex;gap:1rem;align-items:center;">
                    <button type="button" @click="toggleRecording" class="speaking-record-btn"
                        :style="isRecording ? 'background:#1a1a1a;' : 'background:#d8232a;'"
                        style="color:#fff;border:none;padding:0.85rem 1.5rem;border-radius:4px;font-weight:600;cursor:pointer;">
                        <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Diskutieren'"></span>
                    </button>
                    <input type="text" x-model="transcript" placeholder="Ihr Argument eingeben..."
                        style="flex:1;border:1px solid #ccc;padding:0.85rem 1rem;border-radius:4px;font-size:0.9rem;"
                        @keydown.enter.prevent="sendReply">
                    <button type="button" @click="sendReply" class="telc-button primary"
                        :disabled="$wire.speakingProcessing['{{ $sp2Id }}'] || !transcript.trim()"
                        style="padding:0.85rem 1.5rem;white-space:nowrap;">
                        Senden
                    </button>
                </div>

                @if (!empty($speakingFeedback[$sp2Id] ?? ''))
                    <div style="margin-top:1rem;padding:1rem;background:#f7f9fa;border:1px solid #e5e5e5;border-radius:4px;text-align:left;">
                        <strong style="font-size:0.85rem;color:#666;">KI-Feedback:</strong>
                        <div class="markdown-content" style="font-size:0.9rem;line-height:1.6;margin-top:0.25rem;">
                            {!! \Illuminate\Support\Str::markdown($speakingFeedback[$sp2Id]) !!}
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif

</div>
