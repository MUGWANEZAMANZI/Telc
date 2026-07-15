<div class="mx-auto max-w-4xl space-y-6 p-6"
     x-data="{
         isRecording: false,
         recognition: null,
         transcript: '',
         autoPlay: true,
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
             const text = this.transcript.trim();
             if (text) {
                 $wire.sendVoiceInput(text);
             }
             this.transcript = '';
         },
         speakText(text) {
             if (!('speechSynthesis' in window) || !text) return;
             window.speechSynthesis.cancel();
             const u = new SpeechSynthesisUtterance(text);
             u.lang = 'de-DE';
             u.rate = 0.9;
             window.speechSynthesis.speak(u);
         },
         stopTts() {
             if ('speechSynthesis' in window) window.speechSynthesis.cancel();
         },
         sendOnEnter(event) {
             if (event.key === 'Enter' && !event.shiftKey) {
                 event.preventDefault();
                 $wire.sendMessage();
             }
         }
     }"
     x-on:play-ai-tts.window="
         if (autoPlay && $event.detail.text) {
             speakText($event.detail.text);
         }
     "
     x-init="$nextTick(() => {
         const chatArea = document.getElementById('chatArea');
         if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
     })">

    <style>
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
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.05em;
        }
        .timer-display.danger { color: #ef4444; }
        .timer-display.warning { color: #f59e0b; }
        .timer-display.ok { color: #1a1a1a; }
        .chat-bubble-ai {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #d8232a;
            border-radius: 0 8px 8px 0;
            padding: 1rem 1.25rem;
            max-width: 85%;
            font-size: 0.95rem;
            line-height: 1.7;
            color: #1f2937;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .chat-bubble-user {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px 0 0 8px;
            padding: 1rem 1.25rem;
            max-width: 85%;
            font-size: 0.95rem;
            line-height: 1.7;
            color: #1f2937;
            margin-left: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
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
            width: 1.5rem; height: 1.5rem;
            border: 3px solid #e5e5e5;
            border-top-color: #d8232a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        .chat-input {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            resize: none;
            outline: none;
            transition: border-color 0.2s;
            line-height: 1.5;
        }
        .chat-input:focus { border-color: #d8232a; }
    </style>

    {{-- Header --}}
    <div style="border-bottom: 1px solid #cbcbcb; padding-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #666666;">Prüfungssimulation</span>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 400; color: #1a1a1a; margin-top: 0.25rem;">Mündliche Prüfung (KI)</h1>
            <p style="color: #666666; font-size: 1.05rem; margin-top: 0.5rem;">Live-Gespräch mit KI-Prüfer im exakten Prüfungsformat</p>
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

    {{-- Tier prompt --}}
    @elseif ($showTierPrompt)
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 4rem 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); max-width: 600px; margin: 3rem auto;">
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
        <div class="voice-card" style="border-left: 4px solid {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }};">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: {{ ($result['passed'] ?? false) ? '#166534' : '#ef4444' }};">Prüfung abgeschlossen</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a1a1a; margin-top: 0.25rem;">Ihr Bewertungsergebnis</h2>

            <div style="display: flex; gap: 3rem; margin: 2rem 0; background: #fafafa; padding: 1.5rem; border-radius: 8px; align-items: center; border: 1px solid #e5e5e5; flex-wrap: wrap;">
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;">
                    <div style="font-size: 2.8rem; font-weight: 800; color: #1a1a1a;">{{ $result['earned'] ?? 0 }} <span style="font-size: 1.5rem; color: #666; font-weight: 500;">/ {{ $result['possible'] ?? 0 }}</span></div>
                    <div style="font-size: 0.85rem; color: #666; font-weight: 700; text-transform: uppercase; margin-top: 0.25rem;">Gesamtpunkte</div>
                </div>
                <div style="text-align: center; border-right: 1px solid #ddd; padding-right: 3rem;">
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
            <h3 style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Gesprächsverlauf</h3>
            <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                @foreach($chatHistory as $turn)
                    <div style="margin-bottom: 0.75rem; {{ $turn['role'] === 'user' ? 'text-align: right;' : '' }}">
                        <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: {{ $turn['role'] === 'user' ? '#666' : '#d8232a' }}; letter-spacing: 0.05em;">
                            {{ $turn['role'] === 'user' ? 'Sie' : 'Prüfer' }}
                        </span>
                        <div style="font-size: 0.9rem; color: #374151; line-height: 1.6; {{ $turn['role'] === 'user' ? 'background: #f3f4f6; border-radius: 6px; padding: 0.5rem 0.75rem; display: inline-block; max-width: 80%; text-align: left;' : '' }}">
                            {{ Str::limit($turn['text'], 300) }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Detailed criteria --}}
            <h3 style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Detaillierte Bewertung</h3>
            <div class="space-y-4">
                @foreach (($result['details'] ?? []) as $detail)
                    <div style="border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.25rem; background: #ffffff;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <strong style="font-size: 1rem; color: #1a1a1a;">{{ $detail['label'] ?? '' }}</strong>
                            <strong style="font-size: 1rem; color: #1a1a1a;">
                                {{ $detail['points'] ?? 0 }} / {{ $detail['max_points'] ?? 0 }} Punkte
                            </strong>
                        </div>
                        <div style="font-size: 0.9rem; color: #4b5563; line-height: 1.6; white-space: pre-wrap; background: #f9fafb; padding: 1rem; border-radius: 4px; border-left: 3px solid #6b7280;">{{ $detail['explanation'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Muster-Sätze --}}
            @if (!empty($result['musterSaeften']))
                <h3 style="font-weight: 700; font-size: 1.1rem; color: #1a1a1a; margin-top: 1.5rem; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem;">Muster-Sätze für nächste Prüfung</h3>
                <div style="background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 6px; padding: 1.25rem;">
                    @foreach($result['musterSaeften'] as $satz)
                        <p style="font-size: 0.95rem; color: #065f46; line-height: 1.6; margin-bottom: 0.5rem;">• {{ $satz }}</p>
                    @endforeach
                </div>
            @endif

            <div style="margin-top: 2rem; border-top: 1px solid #cbcbcb; padding-top: 1.5rem; text-align: right;">
                <button type="button" wire:click="repeatExam" class="tab-btn active" style="padding: 0.75rem 2rem;">Erneut üben ↻</button>
            </div>
        </div>

    {{-- ═══ CHAT EXAM SCREEN ═══ --}}
    @else
        {{-- Timer Bar --}}
        <div wire:poll.1s="tick" class="voice-card" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; padding: 0.3rem 0.75rem; border-radius: 4px; background: #fef3c7; color: #92400e;">
                    {{ $currentTeilLabel }}
                </span>
                <span style="font-size: 0.85rem; color: #666; font-weight: 600;">
                    {{ strtoupper($selectedProvider) }} {{ $selectedLevel }}
                </span>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                @php $mins = intdiv($examTimer, 60); $secs = $examTimer % 60; @endphp
                <div class="timer-display {{ $examTimer <= 30 ? 'danger' : ($examTimer <= 60 ? 'warning' : 'ok') }}">
                    {{ sprintf('%02d:%02d', $mins, $secs) }}
                </div>
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #666; cursor: pointer; user-select: none;">
                    <input type="checkbox" x-model="autoPlay" style="accent-color: #d8232a;">
                    🔊 Auto
                </label>
            </div>
        </div>

        {{-- Chat Area --}}
        <div id="chatArea" class="voice-card" style="padding: 1.5rem; min-height: 400px; max-height: 550px; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;">
            @forelse($chatHistory as $index => $turn)
                @if($turn['role'] === 'model')
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #d8232a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.25rem;">
                            <span style="color: #fff; font-size: 0.75rem; font-weight: 700;">KI</span>
                        </div>
                        <div style="flex: 1; display: flex; align-items: flex-start; gap: 0.5rem;">
                            <div class="chat-bubble-ai">{{ $turn['text'] }}</div>
                            <button type="button" @click="speakText({!! \Illuminate\Support\Js::from($turn['text']) !!})" title="Vorlesen" style="background: none; border: none; cursor: pointer; padding: 0.25rem; color: #9ca3af; flex-shrink: 0; font-size: 1rem;">🔊</button>
                        </div>
                    </div>
                @else
                    <div style="display: flex; justify-content: flex-end;">
                        <div class="chat-bubble-user">{{ $turn['text'] }}</div>
                    </div>
                @endif
            @empty
                <div style="text-align: center; color: #9ca3af; padding: 3rem 0;">
                    <p style="font-size: 1rem;">Der KI-Prüfer wird initialisiert...</p>
                </div>
            @endforelse

            {{-- Loading indicator --}}
            @if($aiResponding)
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #d8232a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 0.25rem;">
                        <span style="color: #fff; font-size: 0.75rem; font-weight: 700;">KI</span>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 3px solid #d8232a; border-radius: 0 8px 8px 0; padding: 1rem 1.25rem;">
                        <div class="spinner"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input Area --}}
        <div class="voice-card" style="padding: 1.25rem 1.5rem;">
            {{-- Transcript preview while recording --}}
            @if($isRecording && $transcript)
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 0.75rem; font-size: 0.9rem; color: #991b1b;">
                    <span style="font-weight: 700;">Aufnahme:</span> {{ $transcript }}
                </div>
            @endif

            <div style="display: flex; gap: 0.75rem; align-items: flex-end;">
                {{-- Mic button --}}
                <button type="button"
                    @click="isRecording ? stopRecording() : startRecording()"
                    :style="isRecording ? 'background-color: #1a1a1a; color: #fff;' : 'background-color: #d8232a; color: #fff;'"
                    style="border: none; padding: 0.75rem; border-radius: 6px; cursor: pointer; font-size: 1.1rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; position: relative;">
                    <span x-show="!isRecording">🎤</span>
                    <span x-show="isRecording">
                        ⏹
                        <span class="recording-pulse" style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; position: absolute; top: 4px; right: 4px;"></span>
                    </span>
                </button>

                {{-- Text input --}}
                <div style="flex: 1;">
                    <textarea
                        x-ref="chatInput"
                        wire:model="userInput"
                        @keydown="sendOnEnter($event)"
                        class="chat-input"
                        rows="1"
                        placeholder="{{ $aiResponding ? 'Der Prüfer antwortet...' : 'Tippen Sie Ihre Antwort oder sprechen Sie...' }}"
                        {{ $aiResponding || $examComplete ? 'disabled' : '' }}
                        style="{{ $aiResponding || $examComplete ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"
                    ></textarea>
                </div>

                {{-- Send button --}}
                <button type="button"
                    wire:click="sendMessage"
                    {{ $aiResponding || $examComplete || trim($userInput) === '' ? 'disabled' : '' }}
                    style="background-color: {{ $aiResponding || $examComplete || trim($userInput) === '' ? '#d1d5db' : '#1a1a1a' }}; color: #fff; border: none; padding: 0.75rem; border-radius: 6px; cursor: {{ $aiResponding || $examComplete || trim($userInput) === '' ? 'not-allowed' : 'pointer' }}; font-size: 1.1rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; width: 44px; height: 44px;">
                    ➤
                </button>
            </div>

            {{-- Submit exam button --}}
            @if($examComplete && !$examFinished)
                <div style="margin-top: 1rem; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 1rem;">
                    <button type="button" wire:click="submitExam" class="tab-btn active" style="background-color: #166534; border-color: #166534; padding: 0.75rem 2rem;">
                        Prüfung abschließen & bewerten →
                    </button>
                </div>
            @endif

            {{-- Grading loading --}}
            @if($aiResponding && $examComplete)
                <div style="text-align: center; margin-top: 1rem;">
                    <p style="font-weight: 600; color: #1a1a1a;">Finale Bewertung wird erstellt...</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Auto-scroll script --}}
    <script>
        document.addEventListener('livewire:updated', () => {
            const chatArea = document.getElementById('chatArea');
            if (chatArea) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        });
    </script>
</div>
