{{-- ==================== GOETHE B2 TEIL 1 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-teil1-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 1' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
            <span>vorgeschlagene Arbeitszeit: 18 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l1-instruction');
        $persons = collect($section['elements'])->where('type', 'goethe_portrait')->all();
        $questions = collect($section['elements'])->where('type', 'matching')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Flex columns: Left = Questions list, Right = Portraits with text --}}
    <div style="display: flex; gap: 2rem; flex-wrap: wrap; width: 100%; margin-top: 1.5rem;">
        {{-- Left column: Questions --}}
        <div style="flex: 1; min-width: 320px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.25rem; color: #1f2937; text-transform: uppercase; letter-spacing: 0.05em;">Aussagen</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $qNum = substr($elId, strrpos($elId, '-') + 1);
                        $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? 'a,b,c,d'));
                    @endphp
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 0.75rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                            <div style="font-size: 0.95rem; font-weight: 550; color: #1f2937; line-height: 1.5;">{{ $element['instruction'] ?? '' }}</div>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 2.5rem; margin-top: 0.25rem;">
                            <span style="font-size: 0.82rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.03em;">Lösung:</span>
                            @php
                                $selectBorder = '#cbd5e1';
                                $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
                                $current = $answers[$elId] ?? '';
                                $expected = $element['answer'] ?? '';
                                if ($result && $detail) {
                                    $selectBorder = $detail['correct'] ? '#16a34a' : '#dc2626';
                                    $expected = $detail['expected'] ?? $expected;
                                }
                            @endphp
                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.35rem 0.65rem; border: 1.5px solid {{ $selectBorder }}; border-radius: 6px; background: #ffffff; font-size: 0.88rem; font-weight: 700; color: #1f2937; min-width: 80px; transition: border-color 0.15s; outline: none; cursor: pointer;">
                                <option value="">---</option>
                                @foreach ($matchOptions as $opt)
                                    <option value="{{ $opt }}"
                                        @if($result && $opt === $expected)
                                            style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                        @elseif($result && $current === $opt && !$detail['correct'])
                                            style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                        @endif
                                    >{{ strtoupper($opt) }}</option>
                                @endforeach
                            </select>
                            @if ($result && $detail)
                                @if ($detail['correct'])
                                    <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #16a34a;">
                                        ✓ Richtig
                                    </span>
                                @else
                                    @if ($current !== '')
                                        <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #dc2626;">
                                            ✗ Falsch
                                        </span>
                                    @endif
                                    <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #16a34a; border: 1px solid #16a34a; padding: 0.1rem 0.4rem; border-radius: 4px; background-color: #f0fdf4;">
                                        Lösung: {{ strtoupper($expected) }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right column: Persons --}}
        <div style="flex: 1.2; min-width: 380px; display: flex; flex-direction: column; gap: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1f2937; text-transform: uppercase; letter-spacing: 0.05em;">Beiträge</h3>
            @foreach ($persons as $person)
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; display: flex; gap: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); align-items: flex-start;">
                    @if (!empty($person['image_url']))
                        <img src="{{ $person['image_url'] }}" style="width: 90px; height: 110px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);" alt="{{ $person['label'] }}">
                    @endif
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <h4 style="font-size: 1.05rem; font-weight: 800; color: #111827; margin: 0; text-transform: capitalize;">{{ $person['label'] }}</h4>
                        <p style="font-size: 0.88rem; color: #374151; line-height: 1.65; text-align: justify; margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">{{ $person['content'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
