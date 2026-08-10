{{-- ==================== GOETHE B1 TEIL 5 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-teil5-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
    
    {{-- Header with title and timing --}}
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f766e; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 5' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
            Arbeitszeit: 10 Minuten
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l5-instruction');
        $hausordnungEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l5-hausordnung');
        $questions = collect($section['elements'])->where('type', 'multiple_choice')->all();
    @endphp

    {{-- Instruction box --}}
    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2.5rem; color: #1e293b; background: #f0fdfa; border-left: 4px solid #0f766e; padding: 1.25rem 1.5rem; font-size: 0.98rem; line-height: 1.6; border-radius: 6px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Split Columns: Left = Hausordnung Document, Right = Questions --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; width: 100%; align-items: flex-start;">
        
        {{-- Left: Hausordnung Document --}}
        <div style="flex: 1.2; min-width: 380px;">
            <div style="border: 1px solid #cbd5e1; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); background: #ffffff;">
                
                {{-- Document Header --}}
                <div style="background: #f8fafc; border-bottom: 1.5px solid #cbd5e1; padding: 1rem 1.5rem; text-align: center;">
                    <div style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Dresdner Berufsbildungszentrum BZW
                    </div>
                    <h3 style="font-family: Georgia, serif; font-size: 1.6rem; font-weight: 900; letter-spacing: 0.1em; color: #1e293b; margin: 0; text-transform: uppercase;">
                        Hausordnung
                    </h3>
                </div>

                {{-- Document Body --}}
                <div style="padding: 2.5rem; font-size: 0.95rem; color: #334155; line-height: 1.7; font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;">
                    @if ($hausordnungEl)
                        @php
                            // Parse paragraphs and make headings bold
                            $paragraphs = explode("\n\n", $hausordnungEl['content']);
                        @endphp
                        @foreach ($paragraphs as $paragraph)
                            @php
                                $parts = explode(':', $paragraph, 2);
                            @endphp
                            @if (count($parts) === 2)
                                <div style="margin-bottom: 1.5rem;">
                                    <h4 style="font-size: 1rem; font-weight: 800; color: #0f766e; margin: 0 0 0.35rem 0; text-transform: uppercase; letter-spacing: 0.02em;">
                                        {{ trim($parts[0]) }}
                                    </h4>
                                    <p style="margin: 0; text-align: justify;">
                                        {{ trim($parts[1]) }}
                                    </p>
                                </div>
                            @else
                                <p style="margin-bottom: 1.5rem; text-align: justify;">
                                    {{ $paragraph }}
                                </p>
                            @endif
                        @endforeach
                    @endif
                </div>

            </div>
        </div>

        {{-- Right: Multiple Choice Questions 27 to 30 --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            <h3 style="font-size: 1.15rem; font-weight: 800; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em;">
                Aufgaben
            </h3>

            {{-- Render tasks 27-30 --}}
            @include('partials.premium.goethe-b1-mc-loop', ['questions' => $questions])

        </div>

    </div>

</section>
