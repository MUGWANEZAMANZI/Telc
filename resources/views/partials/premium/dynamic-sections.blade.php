{{-- Dynamic section/element loop for templated levels (B1, B2, C2, etc.) --}}
@foreach ($template['sections'] as $sectionIdx => $section)
    @php
        $isSpeaking = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
        $record = \App\Models\ExamTemplate::find($selectedTemplateId);
        $status = $record ? $this->getTemplateFreeStatus($record) : ['is_free' => false, 'free_sections' => 0];
        $isSubscribed = $this->hasActiveSubscription();
        $isFullyEnrolled = $this->isFullyEnrolled($selectedTemplateId);
        $isLocked = !$isSubscribed && !$isFullyEnrolled && ($sectionIdx >= $status['free_sections']);
    @endphp

    @if ($isLocked)
        <section class="c1-section" style="background: #ffffff; border: 2px dashed #cbcbcb; border-radius: 8px; padding: 3rem 2rem; margin: 2rem 0; text-align: center; font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔒</div>
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 0.5rem; color: #1a1a1a;">{{ $section['title'] ?? 'Abschnitt gesperrt' }}</h3>
            <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                Dieser Abschnitt ist Teil der Premium-Funktionen. Bitte erwerben Sie ein Abonnement, um vollen Zugang zu allen Abschnitten zu erhalten.
            </p>
            <a href="{{ route('pricing') }}" class="telc-button primary" style="display: inline-block; text-decoration: none;">
                Jetzt Abonnement ansehen
            </a>
        </section>
    @elseif ($isSpeaking)
        @include('partials.premium.speaking-flow')
    @else
        <section class="c1-section" data-section-idx="{{ $sectionIdx }}" style="position: relative;">
            <div class="section-label">{{ $section['title'] ?? ('Teil ' . ($sectionIdx + 1)) }}</div>
            @foreach ($section['elements'] ?? [] as $element)
                @php
                    $elId = $element['id'] ?? ('el_' . $sectionIdx . '_' . $loop->index);
                    $elementLabel = $element['label'] ?? '';
                    $tabLabel = '';
                    if (str_contains(strtolower($elementLabel), 'leseverstehen')) {
                        $tabLabel = 'Leseverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'sprachbausteine')) {
                        $tabLabel = 'Sprachbausteine';
                    } elseif (str_contains(strtolower($elementLabel), 'hörverstehen') || str_contains(strtolower($elementLabel), 'hoerverstehen')) {
                        $tabLabel = 'Hörverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'schriftlicher') || str_contains(strtolower($elementLabel), 'schreiben')) {
                        $tabLabel = 'Schreiben';
                    } elseif (str_contains(strtolower($elementLabel), 'mündliche') || str_contains(strtolower($elementLabel), 'sprechen')) {
                        $tabLabel = 'Sprechen';
                    }
                    $isTaskInput = preg_match('/^Aufgabe\s+\d+/i', $elementLabel);
                @endphp

                @if ($elementLabel && !$isTaskInput)
                    <div style="position: relative; margin-top: 2rem; margin-bottom: 1.5rem;">
                        @if ($tabLabel)
                            <div class="document-section-tab" style="top: 0.5rem;">
                                <div class="icon-grid">
                                    <div></div><div></div>
                                    <div></div><div></div>
                                </div>
                                <div class="tab-text">{{ $tabLabel }}</div>
                            </div>
                        @endif

                        <h3 class="section-heading" style="margin-bottom: 0.5rem; font-family: var(--font-serif); font-size: 1.6rem; font-weight: 600; color: #1a1a1a;">{{ $elementLabel }}</h3>
                        @if ($element['instruction'] ?? null)
                            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                                {!! nl2br(e($element['instruction'])) !!}
                            </div>
                        @endif
                    </div>
                @endif

                @if (($element['type'] ?? '') === 'text')
                    <article class="c1-passage" style="background: #fdfdfd; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
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
                            <p class="cloze-text" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.5rem; margin-bottom: 1.5rem; line-height: 2.2;">
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
                    @if (isset($element['pairs']) && count($element['pairs']) > 0)
                        @foreach ($element['pairs'] as $pair)
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
                    @else
                        <div style="display:flex;flex-direction:row;align-items:center;gap:1.5rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif; background: #fafafa; padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #e5e5e5;">
                            <span style="font-weight:600; color: #1a1a1a; min-width: 100px;">{{ $element['label'] ?? '' }}</span>
                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.35rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; background: #ffffff;">
                                <option value="">---</option>
                                @foreach ($matchOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                                @if ($detail)
                                    <span style="margin-left: auto; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }}; font-size: 0.85rem;">
                                        {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif
                @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false']))
                    @php
                        $rawOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                    @endphp
                    <fieldset style="border:none;padding:0;margin:0 0 1.25rem 0; font-family:'Plus Jakarta Sans',sans-serif; background:#fafafa; border:1px solid #e5e5e5; padding: 0.75rem 1rem; border-radius: 6px;">
                        <legend style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:0.5rem; color:#1a1a1a; padding: 0 0.5rem;">
                            {{ $element['label'] ?? 'Aufgabe' }}@if(!empty($element['instruction'])): {{ $element['instruction'] }}@endif
                        </legend>
                        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                            @foreach ($rawOptions as $opt)
                                <label class="mc-option-item" style="display:inline-flex; align-items:center; gap:0.35rem; cursor:pointer;">
                                    <input type="radio" name="{{ $elId }}" value="{{ $opt }}" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }}>
                                    <span style="font-weight: 500; text-transform: uppercase;">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if ($result)
                            @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                            @if ($detail)
                                <div style="margin-top:0.5rem; font-size:0.85rem; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                    {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                </div>
                            @endif
                        @endif
                    </fieldset>
                @elseif (($element['type'] ?? '') === 'writing')
                    @php
                        $writingDetail = $result['details'][$elId] ?? [];
                    @endphp
                    <div style="margin-bottom:2rem;">
                        <label style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:block;margin-bottom:0.5rem;">{{ $element['instruction'] ?? 'Schreiben Sie einen Text' }} (max. {{ $writingDetail['max_points'] ?? '—' }} Punkte)</label>
                        <textarea class="writing-textarea-lined" wire:model.debounce.500ms="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} placeholder="Ihre Antwort..."></textarea>
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
