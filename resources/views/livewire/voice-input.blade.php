<div class="mx-auto max-w-4xl space-y-8 p-6"
     x-data="{
         isRecording: false,
         recognition: null,
         transcript: '',
         initRecognition() {
             const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
             if (!SpeechRecognition) {
                 alert('Spracherkennung (Web Speech API) wird in diesem Browser nicht unterstützt. Bitte nutzen Sie Chrome oder Edge.');
                 return;
             }
             this.recognition = new SpeechRecognition();
             this.recognition.lang = 'de-DE';
             this.recognition.continuous = true;
             this.recognition.interimResults = true;
             this.recognition.onresult = (e) => {
                 let transcriptText = '';
                 for (let i = e.resultIndex; i < e.results.length; i++) {
                     transcriptText += e.results[i][0].transcript;
                 }
                 this.transcript = transcriptText;
             };
             this.recognition.onerror = () => this.stopRecording();
         },
         startRecording() {
             this.transcript = '';
             if (!this.recognition) {
                 this.initRecognition();
             }
             if (!this.recognition) return;
             this.isRecording = true;
             this.recognition.start();
         },
         stopRecording() {
             this.isRecording = false;
             if (this.recognition) {
                 this.recognition.stop();
             }
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
            min-height: 250px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1a1a;
            line-height: 2.2rem;
            font-size: 1.05rem;
        }

        .ruled-paper::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 3.5rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        .ruled-paper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 3.7rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        .ruled-content {
            background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem);
            background-size: 100% 2.2rem;
            min-height: 180px;
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
            padding: 1.25rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            background: #ffffff;
            font-size: 1rem;
            color: #4a4a4a;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }

        .topic-pill:hover {
            border-color: #d8232a;
            background-color: #fff8f8;
        }

        .topic-pill.active {
            border-color: #d8232a;
            background-color: #fdf2f2;
            font-weight: 700;
            color: #d8232a;
        }

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

        .tab-btn:hover {
            border-color: #1a1a1a;
        }

        .tab-btn.active {
            background-color: #1a1a1a;
            color: #ffffff;
            border-color: #1a1a1a;
        }

        .chat-bubble {
            max-width: 80%;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .chat-bubble.user {
            background-color: #f3f4f6;
            color: #1f2937;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }

        .chat-bubble.assistant {
            background-color: #fdf2f2;
            color: #9b1c1c;
            margin-right: auto;
            border-bottom-left-radius: 0;
            border-left: 3px solid #d8232a;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 3px solid #e5e5e5;
            border-top-color: #d8232a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
    </style>

    <!-- Header Section -->
    <div style="border-bottom: 1px solid #cbcbcb; padding-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #666666;">Prüfungssimulation</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 400; color: #1a1a1a; margin-top: 0.25rem;">Mündliche Prüfung (KI)</h1>
            <p style="color: #666666; font-size: 1.05rem; margin-top: 0.5rem;">Simulieren Sie den kompletten mündlichen Prüfungsteil und erhalten Sie ein detailliertes Feedback.</p>
        </div>
        @if($examStarted && !$examFinished)
            <button type="button" wire:click="repeatExam" class="tab-btn" style="border-color: #ef4444; color: #ef4444; font-weight: 600;">
                Prüfung abbrechen
            </button>
        @endif
    </div>

    @if ($errors->has('login'))
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 3rem auto;">
            <div style="background: #fef3c7; width: 4rem; height: 4rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Login erforderlich</h2>
            <p style="color: #666666; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem;">
                Um an der mündlichen Prüfungssimulation teilzunehmen, müssen Sie sich zuerst in Ihr Benutzerkonto einloggen.
            </p>
            <a href="{{ route('login') }}" class="tab-btn active" style="text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">
                Jetzt einloggen
            </a>
        </div>
    @elseif ($errors->has('limit'))
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 3rem auto;">
            <div style="background: #fdf2f2; width: 4rem; height: 4rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d8232a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Übungslimit erreicht</h2>
            <p style="color: #666666; font-size: 1rem; line-height: 1.6; margin-bottom: 2rem;">
                Sie haben Ihre 2 kostenlosen mündlichen Prüfungen abgeschlossen. Bitte abonnieren Sie eines unserer Pakete, um unbegrenzten Zugriff zu erhalten.
            </p>
            <a href="{{ route('pricing') }}" class="tab-btn active" style="background-color: #d8232a; border-color: #d8232a; text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">
                Pakete ansehen
            </a>
        </div>
    @elseif (!$examStarted)
        <!-- Setup Screen -->
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
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" wire:click="$set('selectedLevel', 'B1')" class="tab-btn {{ $selectedLevel === 'B1' ? 'active' : '' }}" style="flex: 1; text-align: center;">B1</button>
                        <button type="button" wire:click="$set('selectedLevel', 'B2')" class="tab-btn {{ $selectedLevel === 'B2' ? 'active' : '' }}" style="flex: 1; text-align: center;">B2</button>
                        <button type="button" wire:click="$set('selectedLevel', 'C1')" class="tab-btn {{ $selectedLevel === 'C1' ? 'active' : '' }}" style="flex: 1; text-align: center;">C1</button>
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
                    <label style="font-size: 0.85rem; color: #666; font-weight: 700; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Verfügbare Themen</label>
                    <div class="space-y-2">
                        @foreach($filteredTopics as $topic)
                            <div class="topic-pill">{{ $topic->title }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" wire:click="startExam" class="tab-btn active" style="padding: 0.8rem 2.5rem; font-size: 1rem;">
                    Sprechprüfung starten →
                </button>
            </div>
        </div>

    @elseif ($examFinished && $result)
        <!-- Result Screen -->
        <div class="voice-card" style="border-left: 4px solid {{ $result['passed'] ? '#166534' : '#ef4444' }};">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: {{ $result['passed'] ? '#166534' : '#ef4444' }};">Prüfung abgeschlossen</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a1a1a; margin-top: 0.25rem;">Ihr Bewertungsergebnis</h2>
            
            <div style="display: flex; gap: 3rem; margin: 2rem 0; background: #fafafa; padding: 1.5rem; border-radius: 8px; align-items: center; border: 1px solid #e5e5e5;">
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
                        {{ $result['passed'] ? 'Bestanden ✓' : 'Nicht bestanden ✗' }}
                    </span>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 0.25rem;">Niveauangemessene Sprechkompetenz nach offiziellen Richtlinien.</p>
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
                <button type="button" wire:click="repeatExam" class="tab-btn active" style="padding: 0.75rem 2rem;">
                    Erneut üben ↻
                </button>
            </div>
        </div>

    @else
        <!-- Simulated Speaking Exam in Progress -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Side: Section Elements Progress -->
            <div class="md:col-span-1 space-y-6">
                <div class="voice-card">
                    <h3 style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: #1a1a1a; margin-bottom: 1rem;">Prüfungsverlauf</h3>
                    <div class="space-y-3">
                        @foreach ($speakingElements as $idx => $element)
                            @if(!str_ends_with($element['id'], '-GLOBAL') && !str_ends_with($element['id'], '-global'))
                                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;
                                        background-color: {{ $activeElementIndex === $idx ? '#d8232a' : ($activeElementIndex > $idx ? '#166534' : '#e5e7eb') }};
                                        color: {{ $activeElementIndex >= $idx ? '#ffffff' : '#6b7280' }};">
                                        {{ $idx + 1 }}
                                    </div>
                                    <span style="font-size: 0.9rem; font-weight: {{ $activeElementIndex === $idx ? '700' : '500' }};
                                        color: {{ $activeElementIndex === $idx ? '#1a1a1a' : ($activeElementIndex > $idx ? '#4b5563' : '#9ca3af') }};">
                                        {{ $element['label'] }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side: Workbench -->
            <div class="md:col-span-2 space-y-6">
                @php $curEl = $speakingElements[$activeElementIndex] ?? null; @endphp

                @if ($curEl)
                    @php $elId = $curEl['id']; @endphp

                    <!-- Stage 1a: Topic choice & Presentation -->
                    @if (str_ends_with($elId, '-SP-1a') || str_ends_with($elId, '-sp-1a'))
                        <div class="voice-card">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #d8232a; letter-spacing: 0.05em;">{{ $curEl['label'] }}</span>
                            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-top: 0.25rem; margin-bottom: 1rem;">Thema auswählen</h2>
                            
                            @if (empty($selectedTopic))
                                <p style="color: #666; font-size: 0.95rem; margin-bottom: 1.5rem;">Bitte wählen Sie eines der folgenden Themen für Ihren Vortrag aus:</p>
                                <div class="space-y-3">
                                    @foreach ($aiGeneratedTopics as $topic)
                                        <button type="button" wire:click="chooseTopic('{{ addslashes($topic) }}')" class="topic-pill">
                                            {{ $topic }}
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div style="background-color: #fafafa; border-left: 3px solid #d8232a; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.95rem;">
                                    <strong>Ausgewähltes Thema:</strong> {{ $selectedTopic }}
                                </div>

                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                                    <button type="button"
                                            @click="isRecording ? (stopRecording(), $wire.submitSpeech('{{ $elId }}', transcript)) : startRecording()"
                                            :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                                            style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                        <span x-show="!isRecording">🎤 Aufnahme starten</span>
                                        <span x-show="isRecording">⏹ Aufnahme beenden & einreichen</span>
                                    </button>
                                    
                                    <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600; font-size: 0.95rem;">
                                        <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-red-500"></span>
                                        <span>Aufnahme läuft...</span>
                                    </div>
                                </div>

                                <!-- concept paper -->
                                <div class="ruled-paper">
                                    <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier / Transkript</div>
                                    <div class="ruled-content" style="height: 100%; min-height: 180px;">
                                        <textarea x-model="transcript"
                                                  class="ruled-content"
                                                  placeholder="Sprechen Sie nach dem Starten der Aufnahme. Ihre Rede wird hier in Echtzeit transkribiert. Sie können auch manuell tippen."
                                                  :disabled="isRecording"
                                                  style="background: transparent; height: 100%; width: 100%; border: none; outline: none;"></textarea>
                                    </div>
                                </div>

                                @if (!empty($speakingProcessing[$elId]))
                                    <div style="text-align: center; margin-top: 1.5rem;">
                                        <div class="spinner"></div>
                                        <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">Bewertung wird geladen...</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                    <!-- Stage 1b: Reaction / AI Presentation -->
                    @elseif (str_ends_with($elId, '-SP-1b') || str_ends_with($elId, '-sp-1b'))
                        <div class="voice-card">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #d8232a; letter-spacing: 0.05em;">{{ $curEl['label'] }}</span>
                            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-top: 0.25rem; margin-bottom: 1rem;">Vortrag des anderen Kandidaten (KI)</h2>
                            
                            @if (!empty($speakingProcessing[$elId]))
                                <div style="text-align: center; padding: 2rem 0;">
                                    <div class="spinner"></div>
                                    <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">AI hält einen Vortrag...</p>
                                </div>
                            @else
                                <div style="background-color: #f3f4f6; border-left: 3px solid #4b5563; padding: 1.25rem; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; white-space: pre-wrap; font-style: italic;">
                                    <strong>Alternative Thema:</strong> {{ $unchosenTopic }}
                                    
                                    <div style="margin-top: 1rem; color: #111827;">{{ $aiPresentationText }}</div>
                                </div>

                                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">Hören/lesen Sie den obigen Vortrag und fassen Sie ihn kurz zusammen, stellen Sie Anschlussfragen oder reagieren Sie darauf:</p>

                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                                    <button type="button"
                                            @click="isRecording ? (stopRecording(), $wire.submitSpeech('{{ $elId }}', transcript)) : startRecording()"
                                            :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                                            style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                        <span x-show="!isRecording">🎤 Aufnahme starten</span>
                                        <span x-show="isRecording">⏹ Aufnahme beenden & einreichen</span>
                                    </button>
                                    
                                    <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600; font-size: 0.95rem;">
                                        <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-red-500"></span>
                                        <span>Aufnahme läuft...</span>
                                    </div>
                                </div>

                                <div class="ruled-paper">
                                    <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier / Transkript</div>
                                    <div class="ruled-content" style="height: 100%; min-height: 180px;">
                                        <textarea x-model="transcript"
                                                  class="ruled-content"
                                                  placeholder="Sprechen Sie nach dem Starten der Aufnahme. Ihre Reaktion wird transkribiert."
                                                  :disabled="isRecording"
                                                  style="background: transparent; height: 100%; width: 100%; border: none; outline: none;"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>

                    <!-- Stage 2: Discussion -->
                    @elseif (str_ends_with($elId, '-SP-2') || str_ends_with($elId, '-sp-2'))
                        <div class="voice-card">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #d8232a; letter-spacing: 0.05em;">{{ $curEl['label'] }}</span>
                            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a1a1a; margin-top: 0.25rem; margin-bottom: 1rem;">Interaktive Diskussion</h2>
                            
                            <div style="background-color: #fafafa; border-left: 3px solid #d8232a; padding: 1rem; margin-bottom: 1.5rem; font-size: 0.95rem; line-height: 1.6;">
                                <strong>Diskussionsthema:</strong> {{ $curEl['content'] }}
                            </div>

                            <!-- Chat Transcript Window -->
                            <div style="border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.5rem; min-height: 250px; max-height: 350px; overflow-y: auto; background-color: #f9fafb; display: flex; flex-direction: column; margin-bottom: 1.5rem;">
                                @forelse ($speakingHistory[$elId] ?? [] as $turn)
                                    <div class="chat-bubble {{ $turn['role'] }}">
                                        <strong>{{ $turn['role'] === 'user' ? 'Lernender' : 'Prüfer' }}:</strong>
                                        <p style="margin-top: 0.25rem;">{{ $turn['text'] }}</p>
                                    </div>
                                @empty
                                    <p style="font-size: 0.9rem; color: #888; font-style: italic; text-align: center; margin: auto;">Das Gespräch hat noch nicht begonnen. Sprechen Sie zuerst, um Ihren ersten Diskussionsbeitrag zu senden.</p>
                                @endforelse
                            </div>

                            <!-- Interactive Speech controls -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <button type="button"
                                            @click="isRecording ? (stopRecording(), $wire.sendDiscussionMessage('{{ $elId }}', transcript), transcript='') : startRecording()"
                                            :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                                            style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                                        <span x-show="!isRecording">🎤 Beitrag sprechen</span>
                                        <span x-show="isRecording">⏹ Beenden & senden</span>
                                    </button>

                                    <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600; font-size: 0.95rem;">
                                        <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-red-500"></span>
                                        <span>Aufnahme läuft...</span>
                                    </div>
                                </div>

                                <button type="button" wire:click="finishDiscussionAndGrade" class="tab-btn active" style="background-color: #166534; border-color: #166534;">
                                    Diskussion beenden & Prüfung bewerten →
                                </button>
                            </div>

                            <div class="ruled-paper">
                                <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier / Transkript</div>
                                <div class="ruled-content" style="height: 100%; min-height: 180px;">
                                    <textarea x-model="transcript"
                                              class="ruled-content"
                                              placeholder="Sprechen Sie nach dem Klicken auf 'Beitrag sprechen'..."
                                              :disabled="isRecording"
                                              style="background: transparent; height: 100%; width: 100%; border: none; outline: none;"></textarea>
                                </div>
                            </div>

                            @if (!empty($speakingProcessing[$elId]))
                                <div style="text-align: center; margin-top: 1.5rem;">
                                    <div class="spinner"></div>
                                    <p style="font-weight: 600; color: #1a1a1a; margin-top: 0.5rem;">Prüfer formuliert Antwort...</p>
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
