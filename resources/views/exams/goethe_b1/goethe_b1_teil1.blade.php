{{-- ==================== GOETHE B1 TEIL 1 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-teil1-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
    
    {{-- Header with title and timing --}}
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f766e; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 1' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
            Arbeitszeit: 10 Minuten
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l1-instruction');
        $blogEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l1-blog');
        $questions = collect($section['elements'])->where('type', 'true_false')->all();
    @endphp

    {{-- Instruction box --}}
    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2.5rem; color: #1e293b; background: #f0fdfa; border-left: 4px solid #0f766e; padding: 1.25rem 1.5rem; font-size: 0.98rem; line-height: 1.6; border-radius: 6px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Left/Right Split Layout: Blog on Left, Questions on Right --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; align-items: flex-start; width: 100%;">
        
        {{-- Left: Blog Mockup --}}
        <div style="flex: 1.2; min-width: 380px;">
            <div style="border: 1px solid #cbd5e1; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
                
                {{-- Browser Header Mockup --}}
                <div style="background: #f1f5f9; padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid #cbd5e1;">
                    <div style="display: flex; gap: 0.35rem;">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #eab308; display: inline-block;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                    </div>
                    <div style="background: #ffffff; flex-grow: 1; border-radius: 6px; padding: 0.25rem 1rem; font-size: 0.75rem; color: #64748b; font-family: monospace; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: #94a3b8;">https://</span>www.susannesalltagsblog.at
                    </div>
                </div>

                {{-- Blog Content Window --}}
                <div style="background: #ffffff; padding: 2.5rem 2rem;">
                    
                    {{-- Blog branding --}}
                    <div style="text-align: center; border-bottom: 1px dashed #cbd5e1; padding-bottom: 1.5rem; margin-bottom: 2rem;">
                        <h1 style="margin: 0; font-size: 2.2rem; font-weight: 900; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif;">
                            Susannes<span style="color: #64748b; font-weight: 400;">Alltags</span>Blog.at
                        </h1>
                        <p style="margin: 0.25rem 0 0; font-size: 0.9rem; color: #64748b; font-style: italic;">
                            Mein Alltag, meine Gedanken, mein Leben ...
                        </p>
                    </div>

                    {{-- Blog Date and Content --}}
                    @if ($blogEl)
                        <div style="font-size: 0.85rem; font-weight: 700; color: #0f766e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">
                            {{ $blogEl['instruction'] ?? 'Donnerstag, den 23. Juni' }}
                        </div>
                        <div style="font-size: 0.98rem; color: #334155; line-height: 1.75; text-align: justify; font-family: Georgia, serif;">
                            {!! nl2br(e($blogEl['content'])) !!}
                        </div>
                        <div style="margin-top: 2rem; border-top: 1px dashed #cbd5e1; padding-top: 1.5rem; font-style: italic; font-size: 1rem; color: #475569; font-family: Georgia, serif;">
                            Bis bald<br>
                            <strong>eure Susanne</strong>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Right: Question Booklet --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <h3 style="font-size: 1.15rem; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; justify-content: space-between;">
                <span>Aufgaben</span>
                <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Lösung wählen</span>
            </h3>

            {{-- Example (Beispiel) --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; position: relative;">
                <div style="position: absolute; top: 0; right: 1rem; transform: translateY(-50%); background: #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.15rem 0.5rem; border-radius: 4px;">
                    Beispiel
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="background: #cbd5e1; color: #475569; font-weight: 800; font-size: 0.85rem; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">0</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #475569; line-height: 1.5;">Zu Mittag klingelte Susannes Telefon.</div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding-left: 2.35rem; margin-top: 0.25rem;">
                    <span style="font-size: 0.85rem; font-weight: 600; border: 2px solid #0f766e; background: #f0fdfa; color: #0f766e; padding: 0.25rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: line-through; text-decoration-thickness: 2px; position: relative;">
                        Richtig
                    </span>
                    <span style="font-size: 0.85rem; font-weight: 600; border: 2px solid #cbd5e1; color: #94a3b8; padding: 0.25rem 1rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                        Falsch
                    </span>
                </div>
            </div>

            {{-- Questions 1 to 6 --}}
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $qNum = substr($elId, strrpos($elId, '-') + 1);
                        $current = $answers[$elId] ?? '';
                        $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
                        $expected = $element['answer'] ?? '';
                    @endphp
                    
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); transition: border-color 0.2s;">
                        
                        {{-- Task statement --}}
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="background: #0f766e; color: #ffffff; font-weight: 800; font-size: 0.9rem; width: 1.7rem; height: 1.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                            <div style="font-size: 0.98rem; font-weight: 600; color: #1e293b; line-height: 1.5;">{{ $element['instruction'] ?? '' }}</div>
                        </div>

                        {{-- Interactive Options --}}
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 2.45rem; margin-top: 0.25rem;">
                            @foreach (['richtig', 'falsch'] as $opt)
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
                                        $textDec = 'line-through'; // simulate handwritten crossed out
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
                                    style="font-family: inherit; font-size: 0.85rem; font-weight: 700; border: 2px solid {{ $btnBorder }}; background: {{ $btnBg }}; color: {{ $btnColor }}; padding: 0.35rem 1.25rem; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: {{ $textDec }}; text-decoration-thickness: 2px; cursor: {{ $result ? 'default' : 'pointer' }}; transition: all 0.15s; outline: none; position: relative;">
                                    {{ ucfirst($opt) }}
                                    @if ($result && $isExpected)
                                        <span style="position: absolute; top: -10px; right: -8px; background: #16a34a; color: white; font-size: 0.6rem; border-radius: 50%; width: 14px; height: 14px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid white;">✓</span>
                                    @endif
                                </button>
                            @endforeach

                            {{-- Small inline result badge --}}
                            @if ($result && $detail)
                                @if ($detail['correct'])
                                    <span style="font-weight: 700; font-size: 0.85rem; color: #16a34a; margin-left: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                                        ✓ Korrekt
                                    </span>
                                @else
                                    <span style="font-weight: 700; font-size: 0.85rem; color: #dc2626; margin-left: 0.5rem;">
                                        ✗ Falsch
                                    </span>
                                @endif
                            @endif
                        </div>

                        {{-- Explanation block --}}
                        @if ($result && !empty($element['explanation']))
                            <div style="margin-top: 0.5rem; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 6px; border-left: 3px solid #64748b; font-size: 0.85rem; color: #475569; line-height: 1.5; margin-left: 2.45rem;">
                                <strong>Erklärung:</strong> {{ $element['explanation'] }}
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

    </div>

</section>
