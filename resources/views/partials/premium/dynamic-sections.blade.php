{{-- Dynamic section/element loop for templated levels (B1, B2, C2, etc.) --}}
@foreach ($template['sections'] as $sectionIdx => $section)
    @php
        $isSpeaking = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
    @endphp

    @if ($isSpeaking)
        @include('partials.premium.speaking-flow')
    @else
        <section class="c1-section" data-section-idx="{{ $sectionIdx }}">
            <div class="section-label">{{ $section['title'] ?? ('Teil ' . ($sectionIdx + 1)) }}</div>
            @foreach ($section['elements'] ?? [] as $element)
                @php $elId = $element['id'] ?? ('el_' . $sectionIdx . '_' . $loop->index); @endphp
                @if (($element['type'] ?? '') === 'text')
                    <article class="c1-passage">
                        {!! nl2br(e($element['content'] ?? '')) !!}
                    </article>
                @elseif (in_array($element['type'] ?? '', ['inline_gap', 'fill_blank']))
                    @php
                        $gaps = method_exists($this, 'gapsFor') ? $this->gapsFor($element) : (json_decode($element['options'] ?? '[]', true) ?: []);
                    @endphp
                    @if (preg_match_all('/--(\d+)--/', $element['content'] ?? '', $gapMatches))
                        @php $gapCount = count($gapMatches[0]); @endphp
                        @php $parts = preg_split('/--\d+--/', $element['content'] ?? ''); @endphp
                        @if (count($parts) > 0)
                            <p class="cloze-text">
                                @foreach ($parts as $partIdx => $part)
                                    {!! nl2br(e($part)) !!}
                                    @if ($partIdx < $gapCount)
                                        @php
                                            $gapIdx = (int)($gapMatches[1][$partIdx] ?? 0);
                                            $gapDef = $gaps[$gapIdx] ?? null;
                                            $gapOptionList = [];
                                            if ($gapDef) {
                                                $parts2 = array_map('trim', explode(',', $gapDef['options'] ?? ''));
                                                foreach ($parts2 as $p) {
                                                    $kv = explode('|', $p, 2);
                                                    $gapOptionList[] = ['key' => trim($kv[0]), 'text' => trim($kv[1] ?? $kv[0])];
                                                }
                                            }
                                        @endphp
                                        <span class="cloze-select-wrapper">
                                            <span class="cloze-gap-num">{{ $gapIdx }}</span>
                                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}.{{ $gapIdx }}" {{ $result ? 'disabled' : '' }}>
                                                <option value="">---</option>
                                                @foreach ($gapOptionList as $opt)
                                                    <option value="{{ $opt['key'] }}">{{ $opt['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    @endif
                                @endforeach
                            </p>
                        @endif
                    @endif
                @elseif (in_array($element['type'] ?? '', ['matching']))
                    @php $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? '')); @endphp
                    @foreach ($element['pairs'] ?? [] as $pair)
                        <div style="display:flex;flex-direction:row;align-items:center;gap:1rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif;">
                            <span style="min-width:150px;font-weight:600;">{{ $pair['left'] ?? '' }}</span>
                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}.{{ $loop->index }}" {{ $result ? 'disabled' : '' }}>
                                <option value="">---</option>
                                @foreach ($matchOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false']))
                    @php
                        $rawOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                    @endphp
                    <fieldset style="border:none;padding:0;margin:0 0 1rem 0;">
                        <legend style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:0.5rem;">{{ $element['instruction'] ?? '' }}</legend>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                            @foreach ($rawOptions as $opt)
                                <label class="mc-option-item">
                                    <input type="radio" name="{{ $elId }}" value="{{ $opt }}" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }}>
                                    <span>{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @elseif (($element['type'] ?? '') === 'writing')
                    @php
                        $writingDetail = $result['details'][$elId] ?? [];
                    @endphp
                    <div style="margin-bottom:2rem;">
                        <label style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:block;margin-bottom:0.5rem;">{{ $element['instruction'] ?? 'Schreiben Sie einen Text' }} (max. {{ $writingDetail['max_points'] ?? '—' }} Punkte)</label>
                        <div class="writing-textarea-lined">
                            <textarea wire:model.debounce.500ms="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} rows="6" placeholder="Ihre Antwort..."></textarea>
                        </div>
                        @if ($result && ($writingDetail['feedback'] ?? ''))
                            <div style="margin-top:0.75rem;padding:0.75rem;background:#f9f9f9;border-left:3px solid #d8232a;border-radius:4px;font-size:0.85rem;">
                                <strong>Feedback:</strong> {{ $writingDetail['feedback'] }}
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </section>
    @endif
@endforeach
