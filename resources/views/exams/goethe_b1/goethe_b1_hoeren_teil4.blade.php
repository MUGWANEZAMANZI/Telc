{{-- ==================== GOETHE B1 HÖREN TEIL 4 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-hoeren-teil4-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Hören Teil 4' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">
            <span>Wählen Sie bei jeder Aufgabe: Wer sagt das?</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-h4-instruction');
        $questions = collect($section['elements'])->where('type', 'matching')->all();
        
        $speakers = [
            'a' => [
                'name' => 'Moderator',
                'role' => 'Gesprächsleiter',
                'img' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&q=80'
            ],
            'b' => [
                'name' => 'Dana Schneider',
                'role' => 'Mutter',
                'img' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80'
            ],
            'c' => [
                'name' => 'Florian Bader',
                'role' => 'Vater',
                'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80'
            ],
        ];
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Speaker profile row at the top --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        @foreach ($speakers as $char => $speaker)
            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; display: flex; gap: 1rem; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02); justify-content: flex-start;">
                <img src="{{ $speaker['img'] }}" style="width: 65px; height: 65px; object-fit: cover; border-radius: 50%; border: 1.5px solid #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.08); flex-shrink: 0;" alt="{{ $speaker['name'] }}">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.4rem;">
                        <span style="background: #1e293b; color: #ffffff; font-weight: 850; font-size: 0.8rem; width: 1.3rem; height: 1.3rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase;">{{ $char }}</span>
                        <span style="font-weight: 800; font-size: 0.95rem; color: #111827;">{{ $speaker['name'] }}</span>
                    </div>
                    <span style="font-size: 0.8rem; font-weight: 600; color: #4b5563; display: block; margin-top: 0.15rem;">{{ $speaker['role'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Example statement (0) --}}
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; opacity: 0.85;">
        <div style="display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1rem;">
            <span style="background: #64748b; color: #ffffff; font-weight: 800; font-size: 0.85rem; padding: 0.2rem 0.5rem; border-radius: 4px; flex-shrink: 0;">Beispiel (0)</span>
            <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">Für kleine Kinder sind die ersten drei Jahre sehr wichtig.</span>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; padding-left: 2rem;">
            @foreach ($speakers as $char => $sp)
                <div style="background: {{ $char === 'b' ? '#f1f5f9' : '#ffffff' }}; border: 1.5px solid {{ $char === 'b' ? '#64748b' : '#e2e8f0' }}; border-radius: 6px; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 700; color: {{ $char === 'b' ? '#1e293b' : '#64748b' }}; min-width: 150px; position: relative;">
                    <span style="width: 1.2rem; height: 1.2rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; background: {{ $char === 'b' ? '#475569' : '#e2e8f0' }}; color: #fff; font-size: 0.75rem;">{{ $char }}</span>
                    <span>{{ $sp['name'] }}</span>
                    @if ($char === 'b')
                        <span style="position: absolute; right: 0.5rem; font-size: 1.1rem; color: #0f766e; font-weight: 900;">✕</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Interactive questions --}}
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @foreach ($questions as $element)
            @php
                $elId = $element['id'];
                $qNum = substr($elId, strrpos($elId, '-') + 1);
                $selected = $answers[$elId] ?? '';
            @endphp
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                    <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ $qNum }}</span>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $element['instruction'] }}</span>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap; padding-left: 2rem;">
                    @foreach ($speakers as $char => $sp)
                        @php
                            $optSelected = $selected === $char;
                            $optStyle = 'background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569;';
                            $badgeStyle = 'background: #e2e8f0; color: #475569;';
                            
                            if ($optSelected) {
                                $optStyle = 'background: #eff6ff; border-color: #3b82f6; color: #1e3a8a; font-weight: 800;';
                                $badgeStyle = 'background: #3b82f6; color: #ffffff;';
                            }

                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', $elId);
                                $correct = $detail['correct'] ?? false;
                                $expected = $detail['expected'] ?? '';
                                
                                if ($optSelected) {
                                    if ($correct) {
                                        $optStyle = 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight: 800;';
                                        $badgeStyle = 'background: #10b981; color: #ffffff;';
                                    } else {
                                        $optStyle = 'background: #fdf2f2; border-color: #ef4444; color: #9b1c1c; font-weight: 800;';
                                        $badgeStyle = 'background: #ef4444; color: #ffffff;';
                                    }
                                } elseif ($char === $expected) {
                                    $optStyle = 'background: #f0fdf4; border-color: #86efac; color: #166534;';
                                    $badgeStyle = 'background: #86efac; color: #166534;';
                                }
                            }
                        @endphp

                        <button type="button" wire:click="$set('answers.{{ $elId }}', '{{ $char }}')" {{ $result ? 'disabled' : '' }}
                            style="display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.88rem; cursor: pointer; transition: all 0.2s; outline: none; min-width: 150px; text-align: left; {{ $optStyle }}">
                            <span style="width: 1.3rem; height: 1.3rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 0.75rem; font-weight: 800; {{ $badgeStyle }}">{{ $char }}</span>
                            <span>{{ $sp['name'] }}</span>
                        </button>
                    @endforeach

                    @if ($result)
                        @php
                            $detail = collect($result['details'])->firstWhere('id', $elId);
                            $correct = $detail['correct'] ?? false;
                            $expected = $detail['expected'] ?? '';
                        @endphp
                        <div style="font-weight: 800; font-size: 0.9rem; margin-top: 0.25rem; color: {{ $correct ? '#10b981' : '#ef4444' }}; flex-basis: 100%;">
                            {{ $correct ? '✓ Richtig' : '✗ Falsch (Lösung: ' . strtoupper($expected) . ' - ' . $speakers[$expected]['name'] . ')' }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
