<div class="mx-auto max-w-7xl space-y-6 p-6"
     x-data="{
         isListening: false,
         isSpeaking: false,
         statusText: 'ERFOLG wird initialisiert...',
         silenceTimer: null,
         lastSpeechTime: 0,
         recognition: null,
         transcript: '',
         
         scrollToBottom() {
             this.$nextTick(() => {
                 const chatArea = document.getElementById('chatArea');
                 if (chatArea) {
                     chatArea.scrollTop = chatArea.scrollHeight;
                 }
             });
         },

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
             
             this.recognition.onstart = () => {
                 this.isListening = true;
                 this.statusText = 'ERFOLG hört zu... Sprechen Sie jetzt';
                 this.lastSpeechTime = Date.now();
             };
             
             this.recognition.onresult = (e) => {
                 let t = '';
                 let lastChunk = null;
                 for (let i = 0; i < e.results.length; i++) {
                     let chunk = e.results[i][0].transcript.trim();
                     if (chunk === '') continue;
                     if (chunk === lastChunk) {
                         continue;
                     }
                     lastChunk = chunk;
                     t += e.results[i][0].transcript + ' ';
                 }
                 this.transcript = t.trim();
                 this.lastSpeechTime = Date.now();
                 this.scrollToBottom();
             };
             
             this.recognition.onerror = (e) => {
                 console.error('Speech recognition error:', e);
                 this.stopListening();
             };
             
             this.recognition.onend = () => {
                 if (this.isListening && !$wire.waitingForTopicSelection) {
                     try { this.recognition.start(); } catch (err) {}
                 }
             };
         },
         
         startListening() {
             if ($wire.waitingForTopicSelection) {
                 this.stopListening();
                 return;
             }
             if ('speechSynthesis' in window) window.speechSynthesis.cancel();
             this.isSpeaking = false;
             $wire.set('isAiSpeaking', false);
             this.transcript = '';
             if (!this.recognition) this.initRecognition();
             if (!this.recognition) return;
             
             this.isListening = true;
             try {
                 this.recognition.start();
             } catch (e) {
                 // Already started
             }
             
             const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
             const silenceLimit = isMobile ? 5000 : 2500;

             if (this.silenceTimer) clearInterval(this.silenceTimer);
             this.silenceTimer = setInterval(() => {
                 if (this.isListening && this.transcript.trim() !== '') {
                     const idleTime = Date.now() - this.lastSpeechTime;
                     if (idleTime > silenceLimit) {
                         this.submitAnswer();
                     }
                 }
             }, 100);
         },
         
         stopListening() {
             this.isListening = false;
             if (this.silenceTimer) {
                 clearInterval(this.silenceTimer);
                 this.silenceTimer = null;
             }
             if (this.recognition) {
                 try { this.recognition.stop(); } catch (e) {}
             }
         },
         
         submitAnswer() {
             const text = this.transcript.trim();
             this.stopListening();
             if (text) {
                 this.statusText = 'Verarbeitung...';
                 $wire.sendVoiceInput(text);
             }
             this.transcript = '';
         },
         
         destroy() {
             this.stopListening();
             if ('speechSynthesis' in window) window.speechSynthesis.cancel();
         },

         speakQuestion(text) {
             if (!('speechSynthesis' in window) || !text) return;
             this.stopListening();
             window.speechSynthesis.cancel();
             
             this.isSpeaking = true;
             $wire.set('isAiSpeaking', true);
             this.statusText = 'ERFOLG spricht...';
             
             let cleanText = text
                 .replace(/[*#_~`]/g, '')
                 .replace(/(?:^|\n)\s*[-+*]\s+/g, ' ')
                 .replace(/\[EXAM_COMPLETE\]/gi, '')
                 .trim();

             const u = new SpeechSynthesisUtterance(cleanText);
             u.lang = 'de-DE';
             u.rate = 0.95;
             
             u.onend = () => {
                 this.isSpeaking = false;
                 $wire.set('isAiSpeaking', false);
                 this.startListening();
             };
             
             u.onerror = () => {
                 this.isSpeaking = false;
                 $wire.set('isAiSpeaking', false);
                 this.startListening();
             };
             
             window.speechSynthesis.speak(u);
         },

         startExamAction() {
             if ('speechSynthesis' in window) {
                 try {
                     let dummy = new SpeechSynthesisUtterance('');
                     window.speechSynthesis.speak(dummy);
                 } catch(e){}
             }
             if (!this.recognition) {
                 this.initRecognition();
             }
             try {
                 this.recognition.start();
                 setTimeout(() => {
                     try { this.recognition.stop(); } catch(e){}
                 }, 50);
             } catch(e){}
             $wire.startExam();
         }
     }"
     x-init="
         $watch('$wire.waitingForTopicSelection', (val) => {
             if (val) {
                 stopListening();
             }
         });
         $watch('$wire.chatHistory', () => {
             scrollToBottom();
         });
         $nextTick(() => {
             @if(count($chatHistory) > 0 && end($chatHistory)['role'] === 'model')
                 speakQuestion({!! \Illuminate\Support\Js::from(end($chatHistory)['text']) !!});
             @endif
             scrollToBottom();
         });
     "
     x-on:play-ai-tts.window="
         speakQuestion($event.detail.text);
     "
     x-on:stop-all-speech.window="
         stopListening();
         if ('speechSynthesis' in window) window.speechSynthesis.cancel();
         isSpeaking = false;
         statusText = 'Prüfung beendet';
     "
>
    <style>
        .voice-card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        .dark .voice-card {
            background: #18181b;
            border-color: #27272a;
            color: #f4f4f5;
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

        .dark .topic-pill {
            background: #27272a;
            border-color: #3f3f46;
            color: #e4e4e7;
        }
        .dark .topic-pill:hover { border-color: #eab308; background-color: #3f3f46; }
        .dark .topic-pill.active { border-color: #eab308; background-color: #3f3f46; color: #eab308; }

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

        .dark .tab-btn {
            background-color: #27272a;
            border-color: #3f3f46;
            color: #f4f4f5;
        }
        .dark .tab-btn:hover { border-color: #a1a1aa; color: #ffffff; }
        .dark .tab-btn.active { background-color: #e4e4e7; color: #09090b; border-color: #e4e4e7; }
        
        .sf-radio-card { display: flex; align-items: center; gap: 0.75rem; background: #fff; border: 2px solid #e5e5e5; padding: 1rem 1.25rem; border-radius: 6px; cursor: pointer; transition: all 0.2s; text-align: left; font-weight: 600; font-size: 0.9rem; }
        .sf-radio-card:hover { border-color: #d8232a; background: #fff8f8; }
        .sf-radio-card.selected { border-color: #d8232a; background: #fef2f2; }

        .dark .sf-radio-card {
            background-color: #18181b;
            border-color: #27272a;
            color: #e4e4e7;
        }
        .dark .sf-radio-card:hover {
            border-color: #eab308;
            background-color: #27272a;
        }
        .dark .sf-radio-card.selected {
            border-color: #eab308;
            background-color: #27272a;
        }
        .dark .sf-radio-card div {
            color: #e4e4e7 !important;
        }
    </style>

    {{-- Header --}}
    <div style="border-bottom: 1px solid #cbcbcb; padding-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #666666;">Prüfungssimulation</span>
            <h1 class="dark:text-white" style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 400; color: #1a1a1a; margin-top: 0.25rem;">Mündliche Prüfung (KI)</h1>
            <p style="color: #666666; font-size: 1.05rem; margin-top: 0.5rem;">Live-Gespräch mit KI-Prüfer im exakten Prüfungsformat</p>
        </div>
        @if($examStarted && !$examFinished)
            <button type="button" @click="if ('speechSynthesis' in window) window.speechSynthesis.cancel();" wire:click="repeatExam" class="tab-btn" style="border-color: #ef4444; color: #ef4444; font-weight: 600;">
                Prüfung abbrechen
            </button>
        @endif
    </div>

    {{-- Login error --}}
    @if ($errors->has('login'))
        <div class="voice-card" style="text-align: center; max-width: 600px; margin: 3rem auto;">
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Login erforderlich</h2>
            <p style="color: #666666; font-size: 1rem; margin-bottom: 2rem;">Um an der mündlichen Prüfungssimulation teilzunehmen, müssen Sie sich zuerst einloggen.</p>
            <a href="{{ route('login') }}" class="tab-btn active" style="text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Jetzt einloggen</a>
        </div>

    {{-- Tier prompt --}}
    @elseif ($showTierPrompt)
        <div class="voice-card" style="text-align: center; max-width: 600px; margin: 3rem auto;">
            <div style="width:64px;height:64px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <svg style="width:32px;height:32px;color:#d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem;">Weitere Übung erforderlich</h2>
            <p style="color: #666666; font-size: 1rem; margin-bottom: 2rem;">{{ $tierPromptMessage }}</p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                @if ($isGuest)
                    <a href="{{ route('register') }}" class="tab-btn active" style="background-color: #d8232a; border-color: #d8232a; color: #fff; text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Konto erstellen</a>
                    <a href="{{ route('login') }}" class="tab-btn" style="text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Einloggen</a>
                @else
                    <a href="{{ route('pricing') }}" class="tab-btn active" style="background-color: #d8232a; border-color: #d8232a; color: #fff; text-decoration: none; padding: 0.85rem 2.5rem; display: inline-block;">Premium kaufen</a>
                @endif
            </div>
        </div>

    {{-- ═══ SETUP SCREEN ═══ --}}
    @elseif (!$examStarted)
        @if ($isGuest && $guestPracticeCount === 0)
            <div style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.75rem;">
                <svg style="width:20px;height:20px;color:#059669;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span style="font-size:0.85rem;color:#065f46;">
                    <strong>Kostenlose Sprechübung:</strong> Sie können diese Übung kostenlos absolvieren. Für weitere Übungen erstellen Sie ein Konto.
                </span>
            </div>
        @endif

        <div class="voice-card">
            <h2 class="dark:text-white" style="font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1a1a1a; margin-bottom: 1.5rem;">Prüfungskonfiguration</h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
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
                        @php
                            $levels = ['A1', 'A2', 'B1', 'B2', 'C1'];
                        @endphp
                        @foreach($levels as $lvl)
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

            @if ($selectedProvider === 'telc')
                <div style="margin-bottom: 1.5rem; background: #fafafa; border: 1px dashed #d9dde5; border-radius: 8px; padding: 2rem; text-align: center; color: #666;" class="dark:bg-zinc-900 dark:border-zinc-800">
                    💡 Diese Prüfung hat keine manuelle Themenauswahl. Die Themen werden während der Sprechprüfung automatisch bestimmt.
                </div>
            @elseif($this->hasPresentationInTeil2())
                <div style="margin-bottom: 1.5rem; background: #fafafa; border: 1px dashed #d9dde5; border-radius: 8px; padding: 2rem; text-align: center; color: #666;" class="dark:bg-zinc-900 dark:border-zinc-800">
                    💡 Das Thema für Ihre Präsentation wird nach Teil 1 ausgewählt.
                </div>
            @elseif($filteredTopics->isNotEmpty())
                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.85rem; color: #666; font-weight: 700; display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Wählen Sie ein Thema</label>
                    <div>
                        @foreach($filteredTopics as $i => $topic)
                            <label class="sf-radio-card {{ $activeVoiceTopicId === $topic->id ? 'selected' : '' }}" style="margin-bottom: 0.5rem;"
                                wire:click="chooseTopic({{ $topic->id }})">
                                <input type="radio" name="voice-topic" value="{{ $topic->id }}" {{ $activeVoiceTopicId === $topic->id ? 'checked' : '' }}>
                                <div>
                                    <div style="font-weight:700;color:#1a1a1a;" class="dark:text-white">{{ $topic->title }}</div>
                                    @if($topic->counter_topic)
                                        <div style="font-size:0.8rem;color:#9ca3af;margin-top:0.15rem;font-weight:400;">Gegen-Thema: {{ $topic->counter_topic }}</div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="background: #fafafa; border: 1px dashed #d9dde5; border-radius: 8px; padding: 2rem; text-align: center; color: #666; margin-bottom: 1.5rem;" class="dark:bg-zinc-900 dark:border-zinc-800">
                    Für diese Konfiguration sind keine aktiven Themen vorhanden.
                </div>
            @endif

            <div style="border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" 
                    @click="startExamAction()"
                    class="tab-btn active" 
                    style="padding: 0.8rem 2.5rem; font-size: 1rem;"
                    @if($selectedProvider !== 'telc' && !$activeVoiceTopicId && !$this->hasPresentationInTeil2()) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                    Sprechprüfung starten →
                </button>
            </div>
        </div>

    {{-- ═══ RESULT SCREEN ═══ --}}
    @elseif ($examFinished && $result)
        <div class="voice-card" style="border-left: 4px solid {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }};">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }};">Prüfung abgeschlossen</span>
            <h2 class="dark:text-white font-serif" style="font-size: 2.2rem; color: #1a1a1a; margin-top: 0.25rem;">Ihr Bewertungsergebnis</h2>

            <div style="display: flex; gap: 3rem; margin: 2rem 0; background: #fafafa; padding: 1.5rem; border-radius: 8px; align-items: center; border: 1px solid #e5e5e5; flex-wrap: wrap;" class="dark:bg-zinc-900 dark:border-zinc-800">
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;" class="dark:border-zinc-800">
                    <div style="font-size: 2.8rem; font-weight: 800; color: #1a1a1a;" class="dark:text-white">{{ $result['earned'] ?? 0 }} <span style="font-size: 1.5rem; color: #666; font-weight: 500;">/ {{ $result['possible'] ?? 0 }}</span></div>
                    <div style="font-size: 0.85rem; color: #666; font-weight: 700; text-transform: uppercase; margin-top: 0.25rem;">Gesamtpunkte</div>
                </div>
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;" class="dark:border-zinc-800">
                    <div style="font-size: 2.5rem; font-weight: 800; color: {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }};">{{ $result['percentage'] ?? 0 }}%</div>
                    <div style="font-size: 0.85rem; color: #666; font-weight: 700; text-transform: uppercase; margin-top: 0.25rem;">Ergebnisanteil</div>
                </div>
                <div>
                    <span style="font-size: 1.25rem; font-weight: 800; color: {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }}; text-transform: uppercase;">
                        {{ ($result['passed'] ?? false) ? 'Bestanden' : 'Nicht bestanden' }}
                    </span>
                </div>
            </div>

            {{-- Conversation history --}}
            <h3 class="dark:text-white" style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem; border-color: #eee;">Gesprächsverlauf</h3>
            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;" class="dark:bg-zinc-900 dark:border-zinc-800">
                @foreach($chatHistory as $turn)
                    <div style="margin-bottom: 0.75rem; {{ $turn['role'] === 'user' ? 'text-align: right;' : '' }}">
                        <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: {{ $turn['role'] === 'user' ? '#666' : '#d8232a' }}; letter-spacing: 0.05em;">
                            {{ $turn['role'] === 'user' ? 'Sie' : 'ERFOLG (Prüfer)' }}
                        </span>
                        <div class="dark:text-zinc-200" style="font-size: 0.9rem; color: #374151; line-height: 1.6; {{ $turn['role'] === 'user' ? 'background: #f3f4f6; border-radius: 6px; padding: 0.5rem 0.75rem; display: inline-block; max-width: 80%; text-align: left;' : '' }}" x-html="renderMarkdown({!! \Illuminate\Support\Js::from(Str::limit($turn['text'], 300)) !!})"></div>
                    </div>
                @endforeach
            </div>

            {{-- Detailed criteria --}}
            <h3 class="dark:text-white" style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem; border-color: #eee;">Detaillierte Bewertung</h3>
            <div class="space-y-4">
                @foreach (($result['details'] ?? []) as $detail)
                    <div style="border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.25rem; background: #ffffff;" class="dark:bg-zinc-900 dark:border-zinc-800">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <strong style="font-size: 1rem; color: #1a1a1a;" class="dark:text-white">{{ $detail['label'] ?? '' }}</strong>
                            <strong style="font-size: 1rem; color: #1a1a1a;" class="dark:text-white">
                                {{ $detail['points'] ?? 0 }} / {{ $detail['max_points'] ?? 0 }} Punkte
                            </strong>
                        </div>
                        <div class="dark:bg-zinc-950 dark:border-zinc-800 dark:text-zinc-300" style="font-size: 0.9rem; color: #4b5563; line-height: 1.6; white-space: pre-wrap; background: #f9fafb; padding: 1rem; border-radius: 4px; border-left: 3px solid #6b7280;">{{ $detail['explanation'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Muster-Sätze --}}
            @if (!empty($result['musterSaeften']))
                <h3 class="dark:text-white" style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-top: 1.5rem; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem; border-color: #eee;">Muster-Sätze für nächste Prüfung</h3>
                <div style="background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 6px; padding: 1.25rem;" class="dark:bg-zinc-950 dark:border-emerald-950">
                    @foreach($result['musterSaeften'] as $satz)
                        <p style="font-size: 0.95rem; color: #065f46; line-height: 1.6; margin-bottom: 0.5rem;" class="dark:text-emerald-400">• {{ $satz }}</p>
                    @endforeach
                </div>
            @endif

            <div style="margin-top: 2rem; border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" wire:click="repeatExam" class="tab-btn active" style="padding: 0.75rem 2rem;">Erneut üben ↻</button>
            </div>
        </div>

    {{-- ═══ ZOHO/MICRO1 ACTIVE EXAM SCREEN ═══ --}}
    @else
         <div wire:poll.1s="tick" class="min-h-[550px] bg-zinc-950 text-white rounded-2xl p-8 border border-zinc-800 shadow-2xl relative"
             x-data-unused="{
                 isListening: false,
                 isSpeaking: false,
                 statusText: 'ERFOLG wird initialisiert...',
                 silenceTimer: null,
                 lastSpeechTime: 0,
                 recognition: null,
                 transcript: '',
                 
                 scrollToBottom() {
                     this.$nextTick(() => {
                         const chatArea = document.getElementById('chatArea');
                         if (chatArea) {
                             chatArea.scrollTop = chatArea.scrollHeight;
                         }
                     });
                 },

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
                     
                     this.recognition.onstart = () => {
                         this.isListening = true;
                         this.statusText = 'ERFOLG hört zu... Sprechen Sie jetzt';
                         this.lastSpeechTime = Date.now();
                     };
                     
                     this.recognition.onresult = (e) => {
                         let t = '';
                         let lastChunk = null;
                         for (let i = 0; i < e.results.length; i++) {
                             let chunk = e.results[i][0].transcript.trim();
                             if (chunk === '') continue;
                             if (chunk === lastChunk) {
                                 continue;
                             }
                             lastChunk = chunk;
                             t += e.results[i][0].transcript + ' ';
                         }
                         this.transcript = t.trim();
                         this.lastSpeechTime = Date.now();
                         this.scrollToBottom();
                     };
                     
                     this.recognition.onerror = (e) => {
                         console.error('Speech recognition error:', e);
                         this.stopListening();
                     };
                     
                     this.recognition.onend = () => {
                         if (this.isListening && !$wire.waitingForTopicSelection) {
                             try { this.recognition.start(); } catch (err) {}
                         }
                     };
                 },
                 
                 startListening() {
                     if ($wire.waitingForTopicSelection) {
                         this.stopListening();
                         return;
                     }
                     if ('speechSynthesis' in window) window.speechSynthesis.cancel();
                     this.isSpeaking = false;
                     $wire.set('isAiSpeaking', false);
                     this.transcript = '';
                     if (!this.recognition) this.initRecognition();
                     if (!this.recognition) return;
                     
                     this.isListening = true;
                     try {
                         this.recognition.start();
                     } catch (e) {
                         // Already started
                     }
                     
                     const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                     const silenceLimit = isMobile ? 5000 : 2500;

                     if (this.silenceTimer) clearInterval(this.silenceTimer);
                     this.silenceTimer = setInterval(() => {
                         if (this.isListening && this.transcript.trim() !== '') {
                             const idleTime = Date.now() - this.lastSpeechTime;
                             if (idleTime > silenceLimit) {
                                 this.submitAnswer();
                             }
                         }
                     }, 100);
                 },
                 
                 stopListening() {
                     this.isListening = false;
                     if (this.silenceTimer) {
                         clearInterval(this.silenceTimer);
                         this.silenceTimer = null;
                     }
                     if (this.recognition) {
                         try { this.recognition.stop(); } catch (e) {}
                     }
                 },
                 
                 submitAnswer() {
                     const text = this.transcript.trim();
                     this.stopListening();
                     if (text) {
                         this.statusText = 'Verarbeitung...';
                         $wire.sendVoiceInput(text);
                     }
                     this.transcript = '';
                 },
                 
                 destroy() {
                     this.stopListening();
                     if ('speechSynthesis' in window) window.speechSynthesis.cancel();
                 },

                 speakQuestion(text) {
                     if (!('speechSynthesis' in window) || !text) return;
                     this.stopListening();
                     window.speechSynthesis.cancel();
                     
                     this.isSpeaking = true;
                     $wire.set('isAiSpeaking', true);
                     this.statusText = 'ERFOLG spricht...';
                     
                     let cleanText = text
                         .replace(/[*#_~`]/g, '')
                         .replace(/(?:^|\n)\s*[-+*]\s+/g, ' ')
                         .replace(/\[EXAM_COMPLETE\]/gi, '')
                         .trim();

                     const u = new SpeechSynthesisUtterance(cleanText);
                     u.lang = 'de-DE';
                     u.rate = 0.95;
                     
                     u.onend = () => {
                         this.isSpeaking = false;
                         $wire.set('isAiSpeaking', false);
                         this.startListening();
                     };
                     
                     u.onerror = () => {
                         this.isSpeaking = false;
                         $wire.set('isAiSpeaking', false);
                         this.startListening();
                     };
                     
                     window.speechSynthesis.speak(u);
                 }
             }"
             x-init-unused="
                 this.$watch('$wire.waitingForTopicSelection', (val) => {
                     if (val) {
                         this.stopListening();
                     }
                 });
                 this.$watch('$wire.chatHistory', () => {
                     this.scrollToBottom();
                 });
                 $nextTick(() => {
                     @if(count($chatHistory) > 0 && end($chatHistory)['role'] === 'model')
                         speakQuestion({!! \Illuminate\Support\Js::from(end($chatHistory)['text']) !!});
                     @endif
                     this.scrollToBottom();
                 });
             "
             x-on-unused-1="
                 speakQuestion($event.detail.text);
             "
             x-on-unused-2="
                 stopListening();
                 if ('speechSynthesis' in window) window.speechSynthesis.cancel();
                 isSpeaking = false;
                 statusText = 'Prüfung beendet';
             "
        >
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch h-full">
                <!-- Left Column: Avatar/Visual Panel -->
                <div class="lg:col-span-5 bg-zinc-900 border border-zinc-800 rounded-xl p-6 flex flex-col justify-between items-center relative overflow-hidden min-h-[380px]">
                    <div class="w-full flex items-center justify-between z-10">
                        <div class="flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest">ERFOLG Simulator</span>
                        </div>
                    </div>

                    <!-- Central Interactive Animated Examiner Avatar Orb -->
                    <div class="flex-1 flex items-center justify-center relative">
                        <!-- Speaking Halo / Ripple Wave -->
                        <div class="absolute w-48 h-48 rounded-full bg-yellow-500/10 transition-all duration-500"
                             :class="isSpeaking ? 'animate-[ping_2s_infinite_ease-in-out]' : 'scale-75 opacity-0'">
                        </div>
                        <div class="absolute w-40 h-40 rounded-full bg-indigo-500/10 transition-all duration-500 delay-150"
                             :class="isSpeaking ? 'animate-[ping_2.5s_infinite_ease-in-out]' : 'scale-75 opacity-0'">
                        </div>

                        <!-- Glowing Core Orb -->
                        <div class="relative w-32 h-32 rounded-full flex items-center justify-center transition-all duration-500 shadow-[0_0_50px_rgba(99,102,241,0.2)] border-2 border-zinc-700 bg-zinc-800"
                             :class="isSpeaking ? 'border-yellow-500 bg-zinc-950 scale-105 shadow-[0_0_60px_rgba(234,179,8,0.4)]' : ''">
                            <span class="text-5xl font-black tracking-tighter select-none font-serif text-white transition-all duration-500"
                                  :class="isSpeaking ? 'text-yellow-400 scale-110' : ''">e.</span>
                        </div>
                    </div>

                    <!-- Mic Status Badge -->
                    <div class="w-full bg-zinc-950/80 border border-zinc-800 px-4 py-2.5 rounded-lg flex items-center justify-between text-xs text-zinc-400 z-10">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11a7 7 0 0 1-7 7m0 0a7 7 0 0 1-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 0 1-3-3V5a3 3 0 1 1 6 0v6a3 3 0 0 1-3 3z" />
                            </svg>
                            <span>Standard-Mikrofon</span>
                        </div>
                        <div class="flex gap-0.5 items-end h-3" x-show="isListening">
                            <span class="w-0.5 h-1 bg-yellow-500 rounded animate-[pulse_0.4s_infinite]"></span>
                            <span class="w-0.5 h-2 bg-yellow-500 rounded animate-[pulse_0.6s_infinite_delay-100]"></span>
                            <span class="w-0.5 h-3 bg-yellow-500 rounded animate-[pulse_0.5s_infinite_delay-200]"></span>
                            <span class="w-0.5 h-1 bg-yellow-500 rounded animate-[pulse_0.4s_infinite_delay-300]"></span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Dynamic Transcript Panel -->
                <div class="lg:col-span-7 flex flex-col justify-between bg-zinc-900 border border-zinc-800 rounded-xl p-6 min-h-[450px]">
                    
                    <!-- Top Navigation/Progress Tabs (Micro1 Style) -->
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-4 mb-4">
                        <div class="flex flex-wrap gap-4">
                            @foreach($teilTimings as $i => $timing)
                                <div class="flex flex-col gap-1">
                                    <span class="text-[0.65rem] font-bold uppercase tracking-wider transition-colors duration-300"
                                          :class="{{ $currentTeil }} === {{ $timing['teil'] }} ? 'text-yellow-400' : 'text-zinc-500'">
                                        Teil {{ $timing['teil'] }}
                                    </span>
                                    <div class="w-16 h-1 rounded-full bg-zinc-800 transition-all duration-300"
                                         :class="{{ $currentTeil }} === {{ $timing['teil'] }} ? 'bg-yellow-500' : ({{ $currentTeil }} > {{ $timing['teil'] }} ? 'bg-zinc-600' : '')">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Clock/Timer -->
                        <div class="bg-zinc-950 border border-zinc-800 px-3 py-1.5 rounded-lg flex items-center gap-2 text-xs font-mono font-bold text-zinc-300">
                            <svg class="h-4 w-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @php $mins = intdiv($examTimer, 60); $secs = $examTimer % 60; @endphp
                            <span>{{ sprintf('%02d:%02d', $mins, $secs) }}</span>
                        </div>
                    </div>

                    <!-- Scrolling Transcript Area -->
                    <div class="flex-1 overflow-y-auto mb-6 pr-2 space-y-4 max-h-[300px]" id="chatArea">
                        @foreach($chatHistory as $index => $turn)
                            <div class="space-y-1">
                                <span class="text-[0.65rem] font-bold tracking-widest uppercase"
                                      :class="'{{ $turn['role'] }}' === 'model' ? 'text-yellow-500' : 'text-zinc-500'">
                                    {{ $turn['role'] === 'model' ? 'ERFOLG (Prüfer)' : 'Sie (Transkript)' }}
                                </span>
                                @if($turn['role'] === 'model')
                                    <div class="text-sm font-medium text-zinc-100 leading-relaxed bg-zinc-950 border border-zinc-800 p-4 rounded-xl relative"
                                         x-html="renderMarkdown({!! \Illuminate\Support\Js::from($turn['text']) !!})">
                                    </div>
                                @else
                                    <div class="text-sm text-zinc-300 leading-relaxed bg-zinc-800/40 p-4 rounded-xl">
                                        {{ $turn['text'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Mid-exam topic selection list -->
                    <div x-show="$wire.waitingForTopicSelection" class="bg-zinc-950 border border-yellow-500/30 p-6 rounded-xl space-y-4 mb-4">
                        <span class="text-xs font-bold text-yellow-400 uppercase tracking-widest block">Wählen Sie ein Präsentationsthema:</span>
                        <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1">
                            @foreach($filteredTopics as $topic)
                                <button type="button" wire:click="selectTransitionTopic({{ $topic->id }})" 
                                        class="w-full text-left bg-zinc-900 hover:bg-zinc-850 border border-zinc-800 hover:border-yellow-500/50 p-3 rounded-lg text-sm text-zinc-100 transition-all font-medium">
                                    {{ $topic->title }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Live status / interim results -->
                    <div class="space-y-4" x-show="!$wire.waitingForTopicSelection">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex items-center gap-2 bg-zinc-950 border border-zinc-800 px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-300"
                                 :class="isListening ? 'text-rose-400 bg-rose-500/10 border-rose-500/20' : (isSpeaking ? 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20' : 'text-zinc-500')">
                                <span class="h-2 w-2 rounded-full"
                                      :class="isListening ? 'bg-rose-500 animate-ping' : (isSpeaking ? 'bg-yellow-500 animate-pulse' : 'bg-zinc-700')"></span>
                                <span x-text="statusText"></span>
                            </div>
                        </div>

                        <div x-show="isListening && transcript.trim() !== ''" class="bg-rose-500/5 border border-rose-500/20 rounded-xl p-4 text-sm text-rose-200">
                            <span class="text-[0.65rem] font-extrabold text-rose-400 uppercase tracking-widest block mb-1">Sie sprechen:</span>
                            <span x-text="transcript" class="italic"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Auto-scroll & markdown renderer --}}
    <script>
        function renderMarkdown(text) {
            if (!text) return '';
            let html = text
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
            const lines = html.split('<br>');
            let out = '';
            let inOl = false;
            for (const line of lines) {
                const m = line.match(/^(\d+)\.\s+(.*)/);
                if (m) {
                    if (!inOl) { out += '<ol style="margin:0.5rem 0;padding-left:1.5rem;">'; inOl = true; }
                    out += '<li style="margin-bottom:0.25rem;">' + m[2] + '</li>';
                } else {
                    if (inOl) { out += '</ol>'; inOl = false; }
                    out += line + '<br>';
                }
            }
            if (inOl) out += '</ol>';
            return out.replace(/<br><\/ol>/g, '</ol>').replace(/<br><br>/g, '<br>');
        }

        document.addEventListener('livewire:updated', () => {
            const chatArea = document.getElementById('chatArea');
            if (chatArea) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        });
    </script>
</div>
