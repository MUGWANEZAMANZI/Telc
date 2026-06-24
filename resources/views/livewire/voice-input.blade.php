<div class="mx-auto max-w-3xl space-y-6 p-6"
     x-data="{
         isRecording: false,
         recognition: null,
         interimText: '',
         initRecognition() {
             const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
             if (!SpeechRecognition) return;
             this.recognition = new SpeechRecognition();
             this.recognition.lang = 'de-DE';
             this.recognition.continuous = true;
             this.recognition.interimResults = true;
             this.recognition.onresult = (e) => {
                 let transcript = '';
                 for (let i = e.resultIndex; i < e.results.length; i++) {
                     transcript += e.results[i][0].transcript;
                 }
                 this.interimText = transcript;
                 $wire.set('transcribedText', transcript);
             };
             this.recognition.onerror = () => this.stopRecording();
         },
         startRecording() {
             if (!this.recognition) this.initRecognition();
             if (!this.recognition) return;
             this.isRecording = true;
             $wire.set('transcribedText', '');
             $wire.set('aiResponse', null);
             this.recognition.start();
         },
         stopRecording() {
             this.isRecording = false;
             if (this.recognition) {
                 this.recognition.stop();
             }
         },
         toggleRecording() {
             this.isRecording ? this.stopRecording() : this.startRecording();
         }
     }">
    <div>
        <flux:heading size="lg">{{ __('Sprechübung') }}</flux:heading>
        <flux:subheading>{{ __('Übe deine mündliche Ausdrucksfähigkeit mit KI-Feedback') }}</flux:subheading>
    </div>

    <flux:card class="space-y-4">
        <div>
            <flux:heading size="base">{{ __('Frage') }}</flux:heading>
            <p class="mt-2 text-lg font-medium text-zinc-800 dark:text-zinc-200">{{ $question }}</p>
        </div>

        <div>
            <div class="flex items-center gap-3">
                <flux:button
                    @click="toggleRecording"
                    :color="isRecording ? 'red' : 'primary'"
                    :icon="isRecording ? 'stop-circle' : 'microphone'"
                    class="cursor-pointer"
                >
                    <span x-text="isRecording ? '{{ __('Aufnahme stoppen') }}' : '{{ __('Aufnahme starten') }}'"></span>
                </flux:button>
                <template x-if="isRecording">
                    <span class="flex items-center gap-2 text-sm text-red-500">
                        <span class="inline-block h-2 w-2 animate-pulse rounded-full bg-red-500"></span>
                        {{ __('Aufnahme läuft...') }}
                    </span>
                </template>
            </div>
        </div>

        <div>
            <flux:field>
                <flux:label>{{ __('Deine Antwort') }}</flux:label>
                <flux:textarea
                    wire:model="transcribedText"
                    :placeholder="__('Hier erscheint deine transkribierte Antwort...')"
                    class="min-h-[120px]"
                />
            </flux:field>
        </div>

        <div class="flex items-center gap-3">
            <flux:button
                wire:click="analyzeWithGemini"
                :disabled="$isProcessing || empty($transcribedText)"
                icon="sparkles"
                class="cursor-pointer"
            >
                {{ __('Mit KI analysieren') }}
            </flux:button>
            <template x-if="$wire.isProcessing">
                <span class="text-sm text-zinc-500">{{ __('Analysiere...') }}</span>
            </template>
        </div>
    </flux:card>

    @if ($aiResponse)
        <flux:card class="space-y-2">
            <flux:heading size="base">{{ __('KI-Feedback') }}</flux:heading>
            <div class="prose prose-sm dark:prose-invert max-w-none whitespace-pre-wrap">
                {{ $aiResponse }}
            </div>
        </flux:card>
    @endif
</div>
