{{-- ==================== GOETHE B2 TEIL 5 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-teil5-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 5' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
            <span>vorgeschlagene Arbeitszeit: 6 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l5-instruction');
        $tocEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l5-table-of-contents');
        $paragraphs = collect($section['elements'])->where('type', 'matching')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Split view: Left = Table of Contents, Right = Document paragraphs --}}
    <div style="display: flex; gap: 2.5rem; flex-wrap: wrap; width: 100%;">
        {{-- Left: Inhaltsverzeichnis --}}
        <div style="flex: 1; min-width: 280px; max-width: 360px;">
            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.5rem; position: sticky; top: 1rem;">
                <h3 style="font-size: 1.05rem; font-weight: 800; border-bottom: 1.5px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #111827; text-transform: uppercase; letter-spacing: 0.05em;">Inhaltsverzeichnis</h3>
                @if ($tocEl)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @php
                            $lines = explode("\n", trim($tocEl['content']));
                        @endphp
                        @foreach ($lines as $line)
                            @php
                                $trimmed = trim($line);
                                if (empty($trimmed)) continue;
                                
                                $firstChar = strtolower(substr($trimmed, 0, 1));
                                $isUsed = false;
                                
                                $selectedAnswers = $answers ?? [];
                                foreach ($paragraphs as $p) {
                                    if (isset($selectedAnswers[$p['id']]) && strtolower($selectedAnswers[$p['id']]) === $firstChar) {
                                        $isUsed = true;
                                        break;
                                    }
                                }
                            @endphp
                            <div style="display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.92rem; color: #374151; font-weight: 600; @if($isUsed) opacity: 0.45; text-decoration: line-through; @endif">
                                <span style="background: #cbd5e1; color: #111827; font-weight: 850; font-size: 0.85rem; width: 1.4rem; height: 1.4rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; flex-shrink: 0; text-transform: uppercase;">{{ $firstChar }}</span>
                                <span>{{ trim(substr($trimmed, 2)) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Paragraphs of Studienordnung --}}
        <div style="flex: 1.8; min-width: 400px; display: flex; flex-direction: column; gap: 1.5rem; background: #ffffff; border: 1.5px solid #d1d5db; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="text-align: center; border-bottom: 2px solid #e5e7eb; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <h2 style="font-family: Georgia, serif; font-size: 1.6rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem 0;">Studienordnung</h2>
                <h3 style="font-family: Georgia, serif; font-size: 1.25rem; font-weight: 700; color: #374151; margin: 0;">für den Bachelorstudiengang Theaterwissenschaft</h3>
            </div>

            {{-- Example § 0 --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                    <span style="font-family: Georgia, serif; font-weight: 700; font-size: 1.1rem; color: #111827;">§ 0</span>
                    <span style="font-size: 0.82rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; background: #e2e8f0; padding: 0.2rem 0.5rem; border-radius: 4px;">c</span>
                </div>
                <p style="font-family: Georgia, serif; font-size: 1rem; color: #374151; line-height: 1.7; text-align: justify; margin: 0;">Das Studium kann nur zum Wintersemester aufgenommen werden.</p>
            </div>

            {{-- Actual Paragraphs --}}
            @foreach ($paragraphs as $element)
                @php
                    $elId = $element['id'];
                    $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? 'a,b,c,d,e,f,g,h'));
                @endphp
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                        <span style="font-family: Georgia, serif; font-weight: 700; font-size: 1.1rem; color: #111827;">{{ $element['instruction'] }}</span>
                        
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.8rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.03em;">Überschrift:</span>
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
                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.25rem 0.5rem; border: 1.5px solid {{ $selectBorder }}; border-radius: 4px; background: #ffffff; font-size: 0.88rem; font-weight: 700; color: #1f2937; min-width: 80px; transition: border-color 0.15s; outline: none; cursor: pointer;">
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
                    <p style="font-family: Georgia, serif; font-size: 1rem; color: #374151; line-height: 1.7; text-align: justify; margin: 0; white-space: pre-line;">{{ $element['content'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
