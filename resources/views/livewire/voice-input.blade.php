<div class="mx-auto max-w-4xl space-y-8 p-6"
     x-data="{
         isRecording: false,
         recognition: null,
         transcript: '',
         ttsPlaying: false,
         initRecognition() {
             const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
             if (!SR) {
                 alert('Spracherkennung wird in diesem Browser nicht unterstützt. Bitte nutzen Sie Chrome oder Edge.');
                 return;
             }
             this.recognition = new SR();
             this.recognition.lang = 'de-DE';
             this.recognition.continuous = true;
             this.recognition.interimResults = true;
             this.recognition.onresult = (e) => {
                 let t = '';
                 for (let i = e.resultIndex; i < e.results.length; i++) {
                     t += e.results[i][0].transcript;
                 }
                 this.transcript = t;
             };
             this.recognition.onerror = () => this.stopRecording();
         },
         startRecording() {
             this.transcript = '';
             if (!this.recognition) this.initRecognition();
             if (!this.recognition) return;
             this.isRecording = true;
             this.recognition.start();
         },
         stopRecording() {
             this.isRecording = false;
             if (this.recognition) this.recognition.stop();
         },
         speakAiText(text) {
             if (!('speechSynthesis' in window)) return;
             window.speechSynthesis.cancel();
             const u = new SpeechSynthesisUtterance(text);
             u.lang = 'de-DE';
             u.rate = 0.9;
             u.onstart = () => { this.ttsPlaying = true; };
             u.onend = () => { this.ttsPlaying = false; $wire.set('aiSpeakingDone', true); };
             u.onerror = () => { this.ttsPlaying = false; $wire.set('aiSpeakingDone', true); };
             window.speechSynthesis.speak(u);
         },
         stopTts() {
             if ('speechSynthesis' in window) window.speechSynthesis.cancel();
             this.ttsPlaying = false;
             $wire.set('aiSpeakingDone', true);
         }
     }">

    <style>
        .ruled-paper {
            background: #ffffff;
            border: 1px solid #d9dde5;
            border-radius: 8px;
            padding: 4rem 3rem 4rem 5rem;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            min-height: 200px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1a1a;
            line-height: 2.2rem;
            font-size: 1.05rem;
        }
        .ruled-paper::before {
            content: '';
            position: absolute;
            top: 0; bottom: 0; left: 3.5rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }
        .ruled-paper::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0; left: 3.7rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }
        .ruled-content {
            background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem);
            background-size: 100% 2.2rem;
            min-height: 160px;
            width: 100%;
            outline: none;
            border: none;
            resize: none;
            white-space: pre-wrap;
            color: #1a1a1a;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }
        .voice-card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        .topic-pill {
            display: block;
            width: 100%;
            text-align: left;
            padding: 1rem 1.25rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            background: #ffffff;
            font-size: 0.95rem;
            color: #4a4a4a;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .topic-pill:hover { border-color: #d8232a; background-color: #fff8f8; }
        .topic-pill.active { border-color: #d8232a; background-color: #fdf2f2; font-weight: 700; color: #d8232a; }
        .tab-btn {
            border: 1px solid #e5e5e5;
            padding: 0.6rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 6px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn:hover { border-color: #1a1a1a; }
        .tab-btn.active { background-color: #1a1a1a; color: #ffffff; border-color: #1a1a1a; }
        .timer-display {
            font-family: 'JetBrains Mono', monospace;
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            padding: 1rem 0;
            letter-spacing: 0.05em;
        }
        .timer-display.danger { color: #ef4444; }
        .timer-display.warning { color: #f59e0b; }
        .timer-display.ok { color: #1a1a1a; }
        .stage-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 0.3rem 0.75rem;
            border-radius: 4px;
            margin-bottom: 0.75rem;
        }
        .ai-text-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #d8232a;
            border-radius: 6px;
            padding: 1.5rem;
            font-size: 1rem;
            line-height: 1.8;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            color: #1f2937;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        .recording-pulse {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .recording-pulse::before {
            content: '';
            position: absolute;
            width: 100%; height: 100%;
            border-radius: 50%;
            background: #ef4444;
            animation: pulse-ring 1.2s ease-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            display: inline-block;
            width: 2rem; height: 2rem;
            border: 3px solid #e5e5e5;
            border-top-color: #d8232a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
    </style>

    {{-- Header --}}
    <div style="border-bottom: 1px solid #cbcbcb; padding-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #666666;">Prüfungssimulation</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 400; color: #1a1a1a; margin-top: 0.25rem;">Mündliche Prüfung (KI)</h1>
            <p style="color: #666666; font-size: 1.05rem; margin-top: 0.5rem;">3 Teile: Ihre Präsentation (3 Min) → KI-Vortrag (4 Min) → Ihre Zusammenfassung (2 Min)</p>
        </div>
        @if($examStarted && !$examFinished)
            <button type="button" wire:click="repeatExam" class="tab-btn" style="border-color: #ef4444; color: #ef4444; font-weight: 600;">
                Prüfung abbrechen
            </button>
        @endif
    </div>

    {{-- Login error --}}
    @if ($errors->has('login'))
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 3rem auto;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Login erforderlich</h2>
            <p style="color: #666666; font-size: 1rem; margin-bottom: 2rem;">Um an der mündlichen Prüfungssimulation teilzunehmen, müssen Sie sich zuerst einloggen.</p>
            <a href="{{ route('login') }}" class="tab-btn active" style="text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Jetzt einloggen</a>
        </div>

    {{-- Limit error --}}
    @elseif ($errors->has('limit'))
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 3rem auto;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Übungslimit erreicht</h2>
            <p style="color: #666666; font-size: 1rem; margin-bottom: 2rem;">Sie haben Ihre 2 kostenlosen mündlichen Prüfungen abgeschlossen. Bitte abonnieren Sie ein Premium-Paket.</p>
            <a href="{{ route('pricing') }}" class="tab-btn active" style="background-color: #d8232a; border-color: #d8232a; text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Pakete ansehen</a>
        </div>

    {{-- ═══ STAGE 0: Setup Screen ═══ --}}
    @elseif (!$examStarted)
        <div class="voice-card">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1a1a1a; margin-bottom: 1.5rem;">Prüfungskonfiguration</h2>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <label style="font-size: 0.85rem; color: #666; font-weight: 700; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Prüfungsanbieter</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" wire:click="$set('selectedProvider', 'telc')" class="tab-btn {{ $selectedProvider === 'telc' ? 'active' : '' }}" style="flex: 1; text-align: center;">TELC</button>
                        <button type="button" wire:click="$set('selectedProvider', 'goethe')" class="tab-btn {{ $selectedProvider === 'goethe' ? 'active' : '' }}" style="flex: 1; text-align: center;">Goethe</button>
                    </div>
                </div>
                <div>
                    <label style="font-size: 0.85rem; color: #666; font-weight: 700; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Sprachniveau</label>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach(['A1','A2','B1','B2','C1','C2'] as $lvl)
                            <button type="button" wire:click="$set('selectedLevel', '{{ $lvl }}')" class="tab-btn {{ $selectedLevel === $lvl ? 'active' : '' }}" style="flex: 1; min-width: 48px; text-align: center; font-size: 0.85rem;">{{ $lvl }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($errors->has('exam'))
                <div style="background-color: #fdf2f2; border: 1px solid #f8b4b4; color: #9b1c1c; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.95rem;">
                    {{ $errors->first('exam') }}
                </div>
            @endif

            @if($filteredTopics->isNotEmpty())
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.85rem; color: #666; font-weight: 700; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Wählen Sie ein Thema</label>
                    <div>
                        @foreach($filteredTopics as $topic)
                            <button type="button" wire:click="chooseTopic({{ $topic->id }})"
                                class="topic-pill {{ $activeVoiceTopicId === $topic->id ? 'active' : '' }}">
                                <strong>{{ $topic->title }}</strong>
                                @if($topic->counter_topic)
                                    <span style="display: block; font-size: 0.8rem; color: #9ca3af; margin-top: 0.25rem; font-weight: 400;">Gegen-Thema: {{ $topic->counter_topic }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="background: #fafafa; border: 1px dashed #d9dde5; border-radius: 8px; padding: 2rem; text-align: center; color: #666; margin-bottom: 1.5rem;">
                    Für diese Konfiguration sind keine aktiven Themen vorhanden.
                </div>
            @endif

            <div style="border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" wire:click="startExam" class="tab-btn active" style="padding: 0.8rem 2.5rem; font-size: 1rem;"
                    @unless($activeVoiceTopicId) disabled style="opacity: 0.5; cursor: not-allowed;" @endunless>
                    Sprechprüfung starten →
                </button>
            </div>
        </div>

    {{-- ═══ RESULT SCREEN ═══ --}}
    @elseif ($examFinished && $result)
        <div class="voice-card" style="border-left: 4px solid {{ $result['passed'] ? '#166534' : '#ef4444' }};">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $result['passed'] ? '#166534' : '#ef4444' }};">Prüfung abgeschlossen</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a1a1a; margin-top: 0.25rem;">Ihr Bewertungsergebnis</h2>

            <div style="display: flex; gap: 3rem; margin: 2rem 0; background: #fafafa; padding: 1.5rem; border-radius: 8px; align-items: center; border: 1px solid #e5e5e5; flex-wrap: wrap;">
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;">
                    <div style="font-size: 2.8rem; font-weight: 800; color: #1a1a1a;">{{ $result['earned'] }} <span style="font-size: 1.5rem; color: #666; font-weight: 500;">/ {{ $result['possible'] }}</span></div>
                    <div style="font-size: 0.85rem; color: #666; font-weight: 700; text-transform: uppercase; margin-top: 0.25rem;">Gesamtpunkte</div>
                </div>
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: {{ $result['passed'] ? '#166534' : '#ef4444' }};">{{ $result['percentage'] }}%</div>
                    <div style="font-size: 0.85rem; color: #666; font-weight: 700; text-transform: uppercase; margin-top: 0.25rem;">Ergebnisanteil</div>
                </div>
                <div>
                    <span style="font-size: 1.25rem; font-weight: 800; color: {{ $result['passed'] ? '#166534' : '#ef4444' }}; text-transform: uppercase;">
                        {{ $result['passed'] ? 'Bestanden' : 'Nicht bestanden' }}
                    </span>
                </div>
            </div>

            <h3 style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Detaillierte Kriterienbewertung</h3>
            <div class="space-y-4">
                @foreach ($result['details'] as $detail)
                    <div style="border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.25rem; background: #ffffff;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <strong style="font-size: 1rem; color: #1a1a1a;">{{ $detail['label'] }}</strong>
                            <strong style="font-size: 1rem; color: {{ ($detail['correct'] ?? false) ? '#166534' : '#ef4444' }};">
                                {{ $detail['points'] }} / {{ $detail['max_points'] }} Punkte
                            </strong>
                        </div>
                        <div style="font-size: 0.9rem; color: #4b5563; line-height: 1.6; white-space: pre-wrap; background: #f9fafb; padding: 1rem; border-radius: 4px; border-left: 3px solid #6b7280;">{{ $detail['explanation'] }}</div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 2rem; border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" wire:click="repeatExam" class="tab-btn active" style="padding: 0.75rem 2rem;">Erneut üben ↻</button>
            </div>
        </div>

    {{-- ═══ STAGE 1: User Presentation (3 min) ═══ --}}
    @elseif ($currentStage === 1)
        <div class="voice-card">
            <span class="stage-badge" style="background: #fef3c7; color: #92400e;">Teil 1 von 3</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-bottom: 0.5rem;">Ihre Präsentation</h2>
            <p style="color: #666; font-size: 0.95rem; margin-bottom: 1.5rem;">Wählen Sie Ihr Thema und halten Sie einen Vortrag (max. 3 Minuten).</p>

            <div style="background-color: #fafafa; border-left: 3px solid #d8232a; padding: 1rem 1.25rem; margin-bottom: 1.5rem; border-radius: 0 6px 6px 0;">
                <strong style="color: #1a1a1a;">Ihr Thema:</strong> {{ $selectedTopic }}
            </div>

            {{-- Timer --}}
            <div wire:poll.1s="tick" style="margin-bottom: 1.5rem;">
                @php $mins = intdiv($stageTimer, 60); $secs = $stageTimer % 60; @endphp
                <div class="timer-display {{ $stageTimer <= 30 ? 'danger' : ($stageTimer <= 60 ? 'warning' : 'ok') }}">
                    {{ sprintf('%02d:%02d', $mins, $secs) }}
                </div>
                <div style="text-align: center; font-size: 0.8rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                    @if($timerRunning) Aufnahme läuft @else Timer läuft @endif
                </div>
            </div>

            {{-- Record controls --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button"
                        @click="isRecording ? (stopRecording(), $wire.set('transcribedText', transcript)) : startRecording()"
                        :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                        style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                        <span x-show="!isRecording">🎤 Aufnahme starten</span>
                        <span x-show="isRecording">⏹ Aufnahme beenden</span>
                    </button>
                    @if($stageTimer > 0)
                        <button type="button" wire:click="submitStage1" class="tab-btn" :disabled="$speakingProcessing['SP-1a'] ?? false"
                            :style="($speakingProcessing['SP-1a'] ?? false) ? 'opacity: 0.5; cursor: not-allowed;' : 'border-color: #166534; color: #166534; font-weight: 700;'">
                            Vorzeitig abgeben →
                        </button>
                    @endif
                    <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600;">
                        <span class="recording-pulse" style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></span>
                        <span>Aufnahme läuft...</span>
                    </div>
                </div>
            </div>

            {{-- Transcript paper --}}
            <div class="ruled-paper">
                <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier / Transkript</div>
                <textarea x-model="transcript" class="ruled-content"
                    placeholder="Sprechen Sie nach dem Starten der Aufnahme. Ihre Rede wird hier transkribiert. Sie können auch manuell tippen."
                    :disabled="isRecording"
                    style="background: transparent; height: 100%; width: 100%; border: none; outline: none; min-height: 160px;"></textarea>
            </div>

            @if (!empty($speakingProcessing['SP-1a']))
                <div style="text-align: center; margin-top: 1.5rem;">
                    <div class="spinner"></div>
                    <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">Bewertung wird geladen...</p>
                </div>
            @endif
        </div>

    {{-- ═══ STAGE 2: AI Counter-Presentation (4 min) ═══ --}}
    @elseif ($currentStage === 2)
        <div class="voice-card" wire:poll.1s="tick">
            <span class="stage-badge" style="background: #ede9fe; color: #5b21b6;">Teil 2 von 3</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-bottom: 0.5rem;">Vortrag des Prüfers (KI)</h2>
            <p style="color: #666; font-size: 0.95rem; margin-bottom: 1.5rem;">Hören Sie sich den Vortrag der KI zum Gegen-Thema an. Merken Sie sich die Kernpunkte für die Zusammenfassung.</p>

            <div style="background-color: #ede9fe; border-left: 3px solid #7c3aed; padding: 1rem 1.25rem; margin-bottom: 1.5rem; border-radius: 0 6px 6px 0;">
                <strong style="color: #5b21b6;">Gegen-Thema:</strong> {{ $selectedCounterTopic }}
            </div>

            {{-- Timer --}}
            <div style="margin-bottom: 1.5rem;">
                @php $mins = intdiv($stageTimer, 60); $secs = $stageTimer % 60; @endphp
                <div class="timer-display {{ $stageTimer <= 30 ? 'danger' : ($stageTimer <= 60 ? 'warning' : 'ok') }}">
                    {{ sprintf('%02d:%02d', $mins, $secs) }}
                </div>
                <div style="text-align: center; font-size: 0.8rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">KI spricht</div>
            </div>

            {{-- AI Text + TTS --}}
            @if (!empty($speakingProcessing['SP-1b']))
                <div style="text-align: center; padding: 3rem 0;">
                    <div class="spinner"></div>
                    <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">KI generiert Vortrag...</p>
                </div>
            @else
                <div class="ai-text-box">{{ $aiPresentationText }}</div>

                <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" @click="ttsPlaying ? stopTts() : speakAiText({!! \Illuminate\Support\Js::from($aiPresentationText) !!})"
                        :style="ttsPlaying ? 'background-color: #1a1a1a;' : 'background-color: #7c3aed;'"
                        style="color: #ffffff; border: none; padding: 0.85rem 2rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                        <span x-show="!ttsPlaying">🔊 KI-Vortrag anhören</span>
                        <span x-show="ttsPlaying">⏹ Stoppen</span>
                    </button>

                    @if($aiSpeakingDone || $stageTimer <= 0)
                        <button type="button" wire:click="advanceToStage3"
                            style="background-color: #166534; color: #ffffff; border: none; padding: 0.85rem 2rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer;">
                            Weiter zu Teil 3 →
                        </button>
                    @endif
                </div>
            @endif
        </div>

    {{-- ═══ STAGE 3: User Summary (2 min) ═══ --}}
    @elseif ($currentStage === 3)
        <div class="voice-card">
            <span class="stage-badge" style="background: #d1fae5; color: #065f46;">Teil 3 von 3</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-bottom: 0.5rem;">Ihre Zusammenfassung</h2>
            <p style="color: #666; font-size: 0.95rem; margin-bottom: 1.5rem;">Fassen Sie zusammen, was der KI-Prüfer in seinem Vortrag gesagt hat (max. 2 Minuten).</p>

            <div style="background-color: #f3f4f6; border-left: 3px solid #6b7280; padding: 1rem 1.25rem; margin-bottom: 1.5rem; border-radius: 0 6px 6px 0; font-size: 0.9rem; color: #4b5563;">
                <strong>Erinnerung — Gegen-Thema:</strong> {{ $selectedCounterTopic }}
            </div>

            {{-- Timer --}}
            <div wire:poll.1s="tick" style="margin-bottom: 1.5rem;">
                @php $mins = intdiv($stageTimer, 60); $secs = $stageTimer % 60; @endphp
                <div class="timer-display {{ $stageTimer <= 30 ? 'danger' : ($stageTimer <= 60 ? 'warning' : 'ok') }}">
                    {{ sprintf('%02d:%02d', $mins, $secs) }}
                </div>
                <div style="text-align: center; font-size: 0.8rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                    @if($timerRunning) Aufnahme läuft @else Timer läuft @endif
                </div>
            </div>

            {{-- Record controls --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button"
                        @click="isRecording ? (stopRecording(), $wire.set('transcribedText', transcript)) : startRecording()"
                        :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                        style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                        <span x-show="!isRecording">🎤 Aufnahme starten</span>
                        <span x-show="isRecording">⏹ Aufnahme beenden</span>
                    </button>
                    @if($stageTimer > 0)
                        <button type="button" wire:click="submitStage3" class="tab-btn" :disabled="$speakingProcessing['SP-2'] ?? false"
                            :style="($speakingProcessing['SP-2'] ?? false) ? 'opacity: 0.5; cursor: not-allowed;' : 'border-color: #166534; color: #166534; font-weight: 700;'">
                            Vorzeitig abgeben →
                        </button>
                    @endif
                    <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600;">
                        <span class="recording-pulse" style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></span>
                        <span>Aufnahme läuft...</span>
                    </div>
                </div>
            </div>

            {{-- Transcript paper --}}
            <div class="ruled-paper">
                <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier / Transkript</div>
                <textarea x-model="transcript" class="ruled-content"
                    placeholder="Fassen Sie zusammen, was der KI-Prüfer gesagt hat..."
                    :disabled="isRecording"
                    style="background: transparent; height: 100%; width: 100%; border: none; outline: none; min-height: 160px;"></textarea>
            </div>

            @if (!empty($speakingProcessing['SP-2']))
                <div style="text-align: center; margin-top: 1.5rem;">
                    <div class="spinner"></div>
                    <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">Zusammenfassung wird bewertet...</p>
                </div>
            @endif
        </div>

        @if (!empty($speakingProcessing['global']))
            <div style="text-align: center; padding: 2rem;">
                <div class="spinner"></div>
                <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">Finale Bewertung wird erstellt...</p>
            </div>
        @endif
    @endif
</div>
