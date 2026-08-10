@php
    if (!function_exists('parseGoetheB1Options')) {
        function parseGoetheB1Options($options) {
            if (empty($options)) {
                return [];
            }
            if (is_array($options)) {
                return $options;
            }
            $decoded = json_decode($options, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // Handle comma-separated option strings like "a|Text 1,b|Text 2,c|Text 3"
            $parts = preg_split('/,(?=[a-zA-Z]\|)/', $options);
            $parsed = [];
            foreach ($parts as $part) {
                $sub = explode('|', $part, 2);
                if (count($sub) === 2) {
                    $parsed[] = [
                        'key' => trim($sub[0]),
                        'text' => trim($sub[1])
                    ];
                } else {
                    $parsed[] = [
                        'key' => trim($part),
                        'text' => trim($part)
                    ];
                }
            }
            return $parsed;
        }
    }
@endphp

<div style="display: flex; flex-direction: column; gap: 1.25rem;">
    @foreach ($questions as $element)
        @php
            $elId = $element['id'];
            $qNum = substr($elId, strrpos($elId, '-') + 1);
            $current = $answers[$elId] ?? '';
            $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
            $expected = $element['answer'] ?? '';
            $parsedOptions = parseGoetheB1Options($element['options'] ?? '');
        @endphp
        
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            
            {{-- Statement/Question --}}
            <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                <span style="background: #0f766e; color: #ffffff; font-weight: 800; font-size: 0.9rem; width: 1.7rem; height: 1.7rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                <div style="font-size: 0.98rem; font-weight: 700; color: #1e293b; line-height: 1.5;">{{ $element['instruction'] ?? '' }}</div>
            </div>

            {{-- Interactive bubbles --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 2.45rem; margin-top: 0.25rem;">
                @foreach ($parsedOptions as $opt)
                    @php
                        $optKey = strtolower($opt['key']);
                        $isSelected = (strtolower($current) === $optKey);
                        $isCorrect = ($result && $detail && $detail['correct']);
                        $isIncorrectSelected = ($result && $isSelected && !$isCorrect);
                        $isExpected = ($result && strtolower($expected) === $optKey);

                        // Bubble styles
                        $bubbleBorder = '#e2e8f0';
                        $bubbleBg = '#ffffff';
                        $bubbleColor = '#334155';
                        $keyBg = '#f1f5f9';
                        $keyColor = '#475569';
                        $textDec = 'none';

                        if ($isSelected) {
                            $bubbleBorder = '#0f766e';
                            $bubbleBg = '#f0fdfa';
                            $bubbleColor = '#0f766e';
                            $keyBg = '#0f766e';
                            $keyColor = '#ffffff';
                            $textDec = 'line-through';
                        }

                        if ($result) {
                            if ($isExpected) {
                                $bubbleBorder = '#16a34a';
                                $bubbleBg = '#f0fdf4';
                                $bubbleColor = '#16a34a';
                                $keyBg = '#16a34a';
                                $keyColor = '#ffffff';
                                $textDec = 'line-through';
                            } elseif ($isIncorrectSelected) {
                                $bubbleBorder = '#dc2626';
                                $bubbleBg = '#fdf2f2';
                                $bubbleColor = '#dc2626';
                                $keyBg = '#dc2626';
                                $keyColor = '#ffffff';
                                $textDec = 'line-through';
                            }
                        }
                    @endphp

                    <button type="button"
                        wire:click="$set('answers.{{ $elId }}', '{{ $optKey }}')"
                        {{ $result ? 'disabled' : '' }}
                        style="width: 100%; text-align: left; font-family: inherit; font-size: 0.9rem; font-weight: 550; border: 1.5px solid {{ $bubbleBorder }}; background: {{ $bubbleBg }}; color: {{ $bubbleColor }}; padding: 0.5rem 0.75rem; border-radius: 8px; cursor: {{ $result ? 'default' : 'pointer' }}; display: flex; align-items: center; gap: 0.75rem; transition: all 0.15s; outline: none;">
                        
                        {{-- Alphabet Circle Key --}}
                        <span style="background: {{ $keyBg }}; color: {{ $keyColor }}; font-weight: 800; font-size: 0.8rem; width: 1.4rem; height: 1.4rem; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; text-decoration: {{ $textDec }}; text-decoration-thickness: 2px;">
                            {{ strtoupper($opt['key']) }}
                        </span>
                        
                        <span style="flex-grow: 1;">{{ $opt['text'] }}</span>
                        
                        @if ($result && $isExpected)
                            <span style="background: #16a34a; color: white; font-size: 0.6rem; border-radius: 50%; width: 14px; height: 14px; display: inline-flex; align-items: center; justify-content: center;">✓</span>
                        @endif
                    </button>
                @endforeach
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
