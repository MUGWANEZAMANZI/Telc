{{-- ==================== GOETHE B1 TEIL 3 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-teil3-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
    
    {{-- Header with title and timing --}}
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f766e; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 3' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
            Arbeitszeit: 10 Minuten
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l3-instruction');
        $situations = collect($section['elements'])->where('type', 'matching')->all();
        $ads = collect($section['elements'])->where('type', 'ad_box')->all();
    @endphp

    {{-- Instruction box --}}
    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2.5rem; color: #1e293b; background: #f0fdfa; border-left: 4px solid #0f766e; padding: 1.25rem 1.5rem; font-size: 0.98rem; line-height: 1.6; border-radius: 6px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Split Columns: Left = Situations / Dropdowns, Right = Ads Board --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; width: 100%; align-items: flex-start;">
        
        {{-- Left: Situations --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.25rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em;">
                Situationen
            </h3>

            {{-- Example --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; position: relative;">
                <div style="position: absolute; top: 0; right: 1rem; transform: translateY(-50%); background: #e2e8f0; color: #475569; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.15rem 0.5rem; border-radius: 4px;">
                    Beispiel
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="background: #cbd5e1; color: #475569; font-weight: 800; font-size: 0.85rem; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">0</span>
                    <div style="font-size: 0.95rem; font-weight: 600; color: #475569; line-height: 1.5; flex-grow: 1;">
                        Mario möchte in Wien einen Sommersprachkurs besuchen.
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; padding-left: 2.35rem; font-size: 0.88rem; font-weight: 700; color: #0f766e;">
                    Anzeige:
                    <span style="background: #e6fffa; border: 1.5px solid #0f766e; padding: 0.15rem 0.6rem; border-radius: 6px; text-transform: uppercase; font-size: 0.85rem;">
                        i
                    </span>
                </div>
            </div>

            {{-- Actual Situations 13-19 --}}
            @foreach ($situations as $element)
                @php
                    $elId = $element['id'];
                    $qNum = substr($elId, strrpos($elId, '-') + 1);
                    $current = $answers[$elId] ?? '';
                    $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
                    $expected = $element['answer'] ?? '';
                    
                    $selectBorder = '#cbd5e1';
                    $selectBg = '#ffffff';
                    $selectColor = '#1e293b';

                    if ($result && $detail) {
                        if ($detail['correct']) {
                            $selectBorder = '#16a34a';
                            $selectBg = '#f0fdf4';
                            $selectColor = '#16a34a';
                        } else {
                            $selectBorder = '#dc2626';
                            $selectBg = '#fdf2f2';
                            $selectColor = '#dc2626';
                        }
                    }
                @endphp

                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    
                    {{-- Text --}}
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="background: #0f766e; color: #ffffff; font-weight: 800; font-size: 0.9rem; width: 1.7rem; height: 1.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                        <div style="font-size: 0.98rem; font-weight: 600; color: #1e293b; line-height: 1.5;">{{ $element['instruction'] ?? '' }}</div>
                    </div>

                    {{-- Selection Option Dropdown --}}
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 2.45rem; margin-top: 0.25rem;">
                        <span style="font-size: 0.88rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.03em;">Anzeige:</span>
                        <select wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }}
                            style="font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.35rem 0.75rem; border: 2px solid {{ $selectBorder }}; background: {{ $selectBg }}; color: {{ $selectColor }}; border-radius: 8px; outline: none; cursor: pointer; transition: all 0.15s; width: 110px;">
                            <option value="">Wählen...</option>
                            @foreach (range('a', 'j') as $letter)
                                <option value="{{ $letter }}"
                                    @if ($result && $letter === $expected)
                                        style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                    @elseif ($result && $current === $letter && !$detail['correct'])
                                        style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                    @endif
                                >
                                    Anzeige {{ strtoupper($letter) }}
                                </option>
                            @endforeach
                            <option value="0"
                                @if ($result && $expected === '0')
                                    style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                @elseif ($result && $current === '0' && !$detail['correct'])
                                    style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                @endif
                            >
                                0 (Keine)
                            </option>
                        </select>

                        @if ($result && $detail)
                            @if ($detail['correct'])
                                <span style="font-weight: 700; font-size: 0.88rem; color: #16a34a;">✓ Richtig</span>
                            @else
                                <span style="font-weight: 700; font-size: 0.88rem; color: #dc2626;">✗ Falsch</span>
                                <span style="font-weight: 700; font-size: 0.88rem; color: #16a34a; border: 1.5px solid #16a34a; padding: 0.15rem 0.5rem; border-radius: 6px; background: #f0fdf4; margin-left: 0.5rem;">
                                    Lösung: {{ $expected === '0' ? '0' : strtoupper($expected) }}
                                </span>
                            @endif
                        @endif
                    </div>

                    {{-- Explanation --}}
                    @if ($result && !empty($element['explanation']))
                        <div style="margin-top: 0.5rem; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 6px; border-left: 3px solid #64748b; font-size: 0.85rem; color: #475569; line-height: 1.5; margin-left: 2.45rem;">
                            <strong>Erklärung:</strong> {{ $element['explanation'] }}
                        </div>
                    @endif

                </div>
            @endforeach

        </div>

        {{-- Right: Ads Board --}}
        <div style="flex: 1.3; min-width: 380px; display: flex; flex-direction: column; gap: 1.5rem;">
            <h3 style="font-size: 1.15rem; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em;">
                Anzeigenboard
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; width: 100%;">
                @foreach ($ads as $ad)
                    <div style="background: #fdfdfb; border: 1.5px solid #e2e4e6; border-radius: 8px; padding: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03); position: relative; display: flex; flex-direction: column; gap: 0.75rem;">
                        
                        {{-- Ad Pin/Badge --}}
                        <div style="position: absolute; top: -10px; left: 1.25rem; background: #0f766e; color: #ffffff; width: 1.5rem; height: 1.5rem; border-radius: 50%; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.15); text-transform: uppercase;">
                            {{ $ad['label'] }}
                        </div>
                        
                        {{-- Content --}}
                        <div style="font-size: 0.88rem; color: #374151; line-height: 1.6; text-align: justify; white-space: pre-line; font-family: 'Plus Jakarta Sans', sans-serif; padding-top: 0.5rem;">{!! e($ad['content']) !!}</div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>

</section>
