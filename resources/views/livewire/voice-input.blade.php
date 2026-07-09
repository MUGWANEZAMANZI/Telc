<div class="mx-auto max-w-4xl space-y-8 p-6"
     x-data="{
         isRecording: false,
         recognition: null,
         transcript: '',
         selectedDuration: 60,
         remainingSeconds: 60,
         timerInterval: null,
         showGradeNotice: false,
         selectedTopic: '{{ addslashes($topics->first()?->title ?? '') }}',
         topics: [
             @foreach ($topics as $topic)
                 '{{ addslashes($topic->title) }}',
             @endforeach
         ],
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
                 $wire.set('transcribedText', transcriptText);
             };
             this.recognition.onerror = () => this.stopRecording();
         },
         startRecording() {
             this.showGradeNotice = false;
             this.transcript = '';
             this.remainingSeconds = this.selectedDuration;
             $wire.set('evaluated', false);
             $wire.set('aiFeedback', '');

             if (!this.recognition) {
                 this.initRecognition();
             }
             if (!this.recognition) return;

             this.isRecording = true;
             this.recognition.start();

             this.timerInterval = setInterval(() => {
                 if (this.remainingSeconds > 0) {
                     this.remainingSeconds--;
                 } else {
                     this.stopRecording();
                 }
             }, 1000);
         },
         stopRecording() {
             this.isRecording = false;
             if (this.recognition) {
                 this.recognition.stop();
             }
             if (this.timerInterval) {
                 clearInterval(this.timerInterval);
             }
             if (this.transcript.trim().length > 0) {
                 this.showGradeNotice = true;
                 $wire.set('selectedTopic', this.selectedTopic);
                 $wire.call('evaluateSpeech');
             }
         },
         toggleRecording() {
             this.isRecording ? this.stopRecording() : this.startRecording();
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
            min-height: 350px;
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
            min-height: 250px;
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
            border-radius: 6px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.01);
        }

        .topic-pill {
            display: block;
            width: 100%;
            text-align: left;
            padding: 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background: none;
            font-size: 0.95rem;
            color: #4a4a4a;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 0.75rem;
        }

        .topic-pill:hover {
            border-color: #1a1a1a;
            background-color: #fafafa;
        }

        .topic-pill.active {
            border-color: #1a1a1a;
            background-color: #f7f7f7;
            font-weight: 600;
            color: #1a1a1a;
        }

        .duration-btn {
            border: 1px solid #e5e5e5;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 4px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .duration-btn:hover {
            border-color: #1a1a1a;
        }

        .duration-btn.active {
            background-color: #1a1a1a;
            color: #ffffff;
            border-color: #1a1a1a;
        }

        @keyframes spin{to{transform:rotate(360deg)}}
    </style>

    <!-- Header Section -->
    <div style="border-bottom: 1px solid #cbcbcb; padding-bottom: 1.5rem;">
        <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #666666;">Laborbereich</span>
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 400; color: #1a1a1a; margin-top: 0.25rem;">Sprechübungs-Labor</h1>
        <p style="color: #666666; font-size: 1.05rem; margin-top: 0.5rem;">Verbessern Sie Ihre TELC mündliche Ausdrucksstärke durch freies Sprechen und simulierte Zeitvorgaben.</p>
    </div>

    <!-- Topics and Timing Setup -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- left col: topics & duration -->
        <div class="md:col-span-1 space-y-6">
            <!-- Duration -->
            <div class="voice-card">
                <h3 style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: #1a1a1a; margin-bottom: 1rem;">1. Redezeit wählen</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="button" @click="selectedDuration = 60; remainingSeconds = 60" class="duration-btn" :class="selectedDuration === 60 ? 'active' : ''">1 Min</button>
                    <button type="button" @click="selectedDuration = 120; remainingSeconds = 120" class="duration-btn" :class="selectedDuration === 120 ? 'active' : ''">2 Min</button>
                    <button type="button" @click="selectedDuration = 300; remainingSeconds = 300" class="duration-btn" :class="selectedDuration === 300 ? 'active' : ''">5 Min</button>
                </div>
            </div>

            <!-- Topics -->
            <div class="voice-card">
                <h3 style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.05em; color: #1a1a1a; margin-bottom: 1rem;">2. Thema wählen</h3>
                <div style="max-height: 380px; overflow-y: auto; padding-right: 0.25rem;">
                    <template x-for="(topic, idx) in topics" :key="idx">
                        <button type="button"
                                @click="selectedTopic = topic"
                                class="topic-pill"
                                :class="selectedTopic === topic ? 'active' : ''"
                                x-text="topic">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- right col: active workbench & paper representation -->
        <div class="md:col-span-2 space-y-6">
            <!-- Active setup card -->
            <div class="voice-card" style="border-left: 4px solid #d8232a;">
                <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #d8232a; letter-spacing: 0.05em;">Aktuelles Thema</span>
                <p style="font-size: 1.1rem; font-weight: 600; color: #1a1a1a; margin-top: 0.25rem;" x-text="selectedTopic"></p>

                <!-- Recording Controls -->
                <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <button type="button"
                                @click="toggleRecording"
                                :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'"
                                style="color: #ffffff; border: none; padding: 0.85rem 1.75rem; border-radius: 4px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                            <span x-show="!isRecording">🎤 Sprechen starten</span>
                            <span x-show="isRecording">⏹ Sprechen beenden</span>
                        </button>

                        <div x-show="isRecording" style="display: flex; align-items: center; gap: 0.5rem; color: #d8232a; font-weight: 600; font-size: 0.95rem;">
                            <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-red-500"></span>
                            <span>Aufnahme läuft...</span>
                        </div>
                    </div>

                    <!-- Timer Countdown -->
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #666666;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span style="font-size: 0.95rem; color: #666666; font-weight: 500;">Verbleibende Zeit:</span>
                        <strong style="font-size: 1.25rem; color: #1a1a1a;" x-text="Math.floor(remainingSeconds / 60) + ':' + String(remainingSeconds % 60).padStart(2, '0')"></strong>
                    </div>
                </div>
            </div>

            <!-- Lined Ruled Paper Sheet -->
            <div class="ruled-paper">
                <div style="position: absolute; top: 1.5rem; right: 2rem; font-size: 0.75rem; font-weight: 700; color: #cbcbcb; text-transform: uppercase; letter-spacing: 0.1em;">Konzeptpapier</div>

                <div class="ruled-content" style="height: 100%; min-height: 250px;">
                    <textarea x-model="transcript"
                              class="ruled-content"
                              placeholder="Sobald Sie die Aufnahme starten und sprechen, wird Ihr gesprochener Text hier in Echtzeit auf dem Papier niedergeschrieben...&#10;&#10;Alternativ können Sie dieses Textfeld nutzen, um Ihre Notizen manuell einzutippen, falls kein Mikrofon angeschlossen ist."
                              :disabled="isRecording"
                              style="background: transparent; height: 100%; width: 100%; border: none; outline: none;"></textarea>
                </div>
            </div>

            <!-- Evaluation Result -->
            <div x-show="showGradeNotice"
                 x-transition
                 style="background-color: #f7f9fa; border: 1px solid #d9dde5; border-left: 4px solid #137333; border-radius: 6px; padding: 2rem; text-align: left;">

                <!-- Evaluating spinner -->
                <div x-show="$wire.evaluating" style="text-align:center;padding:1rem 0;">
                    <div style="display:inline-block;width:2.5rem;height:2.5rem;border:3px solid #e5e5e5;border-top-color:#d8232a;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                    <p style="margin-top:1rem;font-weight:600;color:#1a1a1a;">KI bewertet Ihren Vortrag...</p>
                    <p style="font-size:0.85rem;color:#666;margin-top:0.25rem;">Analyse nach offiziellen TELC-Kriterien</p>
                </div>

                <!-- Evaluation result -->
                <div x-show="!$wire.evaluating && $wire.evaluated">
                    <div style="display:flex;gap:0.75rem;align-items:flex-start;margin-bottom:0.75rem;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#137333" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:0.1rem;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <div>
                            <h4 style="font-weight:700;font-size:1.1rem;color:#1a1a1a;">Sprechleistung bewertet</h4>
                            <p style="font-size:0.95rem;color:#666666;margin-top:0.25rem;">
                                Gesamtpunktzahl: <strong style="color:#1a1a1a;" x-text="$wire.score + ' / ' + $wire.maxPoints"></strong>
                                <span x-show="$wire.score >= Math.ceil($wire.maxPoints * 0.6)" style="color:#137333;font-weight:600;"> — Bestanden</span>
                                <span x-show="$wire.score < Math.ceil($wire.maxPoints * 0.6)" style="color:#d8232a;font-weight:600;"> — Nicht bestanden</span>
                            </p>
                        </div>
                    </div>

                    <div style="background:#ffffff;border:1px solid #e5e5e5;border-radius:4px;padding:1.25rem;margin-top:1rem;font-size:0.9rem;line-height:1.7;color:#4a4a4a;white-space:pre-wrap;"
                         x-text="$wire.aiFeedback">
                    </div>

                    <p style="font-size:0.8rem;color:#999;margin-top:1rem;text-align:center;">
                        Neue Aufnahme starten, um erneut zu üben.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
