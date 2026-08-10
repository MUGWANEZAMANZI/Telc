{{-- ==================== GOETHE B1 TEIL 4 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-teil4-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
    
    {{-- Header with title and timing --}}
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f766e; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 4' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
            Arbeitszeit: 15 Minuten
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l4-instruction');
        $portraits = collect($section['elements'])->where('type', 'goethe_portrait')->all();
        $questions = collect($section['elements'])->where('type', 'true_false')->all();
    @endphp

    {{-- Instruction box --}}
    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2.5rem; color: #1e293b; background: #f0fdfa; border-left: 4px solid #0f766e; padding: 1.25rem 1.5rem; font-size: 0.98rem; line-height: 1.6; border-radius: 6px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Two columns split: Left = Leserbriefe Articles, Right = Survey/Questions --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; width: 100%; align-items: flex-start;">
        
        {{-- Left: Letters list --}}
        <div style="flex: 1.2; min-width: 380px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div style="text-align: center; border-bottom: 2px double #e2e8f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <h1 style="font-family: Georgia, serif; font-size: 2.2rem; font-weight: 900; letter-spacing: 0.1em; color: #1e293b; margin: 0; text-transform: uppercase;">
                    Leserbriefe
                </h1>
                <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                    Meinungen zum Verbot von Gewaltspielen
                </p>
            </div>

            @foreach ($portraits as $p)
                @php
                    $isBeispiel = str_contains(strtolower($p['label']), 'niko');
                @endphp
                <div style="background: {{ $isBeispiel ? '#f8fafc' : '#ffffff' }}; border: 1px solid {{ $isBeispiel ? '#cbd5e1' : '#e2e8f0' }}; border-radius: 10px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01); position: relative;">
                    @if ($isBeispiel)
                        <div style="position: absolute; top: 0; right: 1rem; transform: translateY(-50%); background: #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.15rem 0.5rem; border-radius: 4px;">
                            Beispiel
                        </div>
                    @endif
                    <div style="font-size: 1rem; font-weight: 700; color: #0f766e; margin-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <span>{{ $p['label'] }}</span>
                        @if ($isBeispiel)
                            <span style="font-size: 0.8rem; color: #ef4444; font-weight: 800;">FÜR EIN VERBOT: JA</span>
                        @endif
                    </div>
                    <blockquote style="margin: 0; font-family: Georgia, serif; font-size: 0.98rem; color: #334155; line-height: 1.7; text-align: justify; font-style: italic;">
                        “ {!! e($p['content']) !!} ”
                    </blockquote>
                </div>
            @endforeach

        </div>

        {{-- Right: Survey Booklet --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <h3 style="font-size: 1.15rem; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em; display: flex; justify-content: space-between; align-items: center;">
                <span>Stimmenauswertung</span>
                <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: none; letter-spacing: 0;">Für ein Verbot?</span>
            </h3>

            {{-- Example --}}
            <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="font-size: 0.98rem; font-weight: 700; color: #475569;">
                        Niko
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 0.85rem; font-weight: 700; border: 2px solid #0f766e; background: #f0fdfa; color: #0f766e; padding: 0.25rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: line-through; text-decoration-thickness: 2px;">
                            Ja
                        </span>
                        <span style="font-size: 0.85rem; font-weight: 700; border: 2px solid #cbd5e1; color: #94a3b8; padding: 0.25rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                            Nein
                        </span>
                    </div>
                </div>
            </div>

            {{-- Questions 20 to 26 --}}
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $qNum = substr($elId, strrpos($elId, '-') + 1);
                        $current = $answers[$elId] ?? '';
                        $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
                        $expected = $element['answer'] ?? '';
                        
                        // Extract name from portait or label
                        $name = trim(preg_replace('/Aufgabe \d+\s+\((.+?)\)/', '$1', $element['label'] ?? ''));
                    @endphp

                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        
                        {{-- Task prompt --}}
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span style="background: #0f766e; color: #ffffff; font-weight: 800; font-size: 0.85rem; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">{{ $qNum }}</span>
                                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">{{ $name }}</span>
                            </div>

                            {{-- Ja / Nein buttons --}}
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @foreach (['ja', 'nein'] as $opt)
                                    @php
                                        $isSelected = (strtolower($current) === $opt);
                                        $isCorrect = ($result && $detail && $detail['correct']);
                                        $isIncorrectSelected = ($result && $isSelected && !$isCorrect);
                                        $isExpected = ($result && strtolower($expected) === $opt);

                                        // Button Styles
                                        $btnBorder = '#cbd5e1';
                                        $btnBg = '#ffffff';
                                        $btnColor = '#475569';
                                        $textDec = 'none';

                                        if ($isSelected) {
                                            $btnBorder = '#0f766e';
                                            $btnBg = '#f0fdfa';
                                            $btnColor = '#0f766e';
                                            $textDec = 'line-through';
                                        }

                                        if ($result) {
                                            if ($isExpected) {
                                                $btnBorder = '#16a34a';
                                                $btnBg = '#f0fdf4';
                                                $btnColor = '#16a34a';
                                                $textDec = 'line-through';
                                            } elseif ($isIncorrectSelected) {
                                                $btnBorder = '#dc2626';
                                                $btnBg = '#fdf2f2';
                                                $btnColor = '#dc2626';
                                                $textDec = 'line-through';
                                            }
                                        }
                                    @endphp

                                    <button type="button"
                                        wire:click="$set('answers.{{ $elId }}', '{{ $opt }}')"
                                        {{ $result ? 'disabled' : '' }}
                                        style="font-family: inherit; font-size: 0.85rem; font-weight: 700; border: 2px solid {{ $btnBorder }}; background: {{ $btnBg }}; color: {{ $btnColor }}; padding: 0.25rem 0.95rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: {{ $textDec }}; text-decoration-thickness: 2px; cursor: {{ $result ? 'default' : 'pointer' }}; transition: all 0.15s; outline: none; position: relative;">
                                        {{ ucfirst($opt) }}
                                        @if ($result && $isExpected)
                                            <span style="position: absolute; top: -8px; right: -6px; background: #16a34a; color: white; font-size: 0.55rem; border-radius: 50%; width: 12px; height: 12px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid white;">✓</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Verdict and Explanation --}}
                        @if ($result)
                            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px dashed #cbd5e1; padding-top: 0.5rem; font-size: 0.85rem;">
                                <span style="font-weight: 700; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                    {{ $detail['correct'] ? '✓ Korrekt' : '✗ Falsch' }}
                                </span>
                                @if (!$detail['correct'])
                                    <span style="color: #64748b;">
                                        Richtige Antwort: <strong style="color: #0f766e; text-transform: uppercase;">{{ $expected }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            @if (!empty($element['explanation']))
                                <div style="margin-top: 0.25rem; padding: 0.75rem; background: #f8fafc; border-radius: 6px; border-left: 3px solid #64748b; font-size: 0.85rem; color: #475569; line-height: 1.5;">
                                    <strong>Erklärung:</strong> {{ $element['explanation'] }}
                                </div>
                            @endif
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

    </div>

</section>
