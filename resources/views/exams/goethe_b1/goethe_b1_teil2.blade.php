{{-- ==================== GOETHE B1 TEIL 2 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-teil2-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
    
    {{-- Header with title and timing --}}
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f766e; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 2' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
            Arbeitszeit: 20 Minuten
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-l2-instruction');
        $art1El = collect($section['elements'])->firstWhere('id', 'goethe-b1-l2-article1');
        $questions1 = collect($section['elements'])->whereIn('id', ['goethe-b1-l2-7', 'goethe-b1-l2-8', 'goethe-b1-l2-9'])->all();
        
        $art2El = collect($section['elements'])->firstWhere('id', 'goethe-b1-l2-article2');
        $questions2 = collect($section['elements'])->whereIn('id', ['goethe-b1-l2-10', 'goethe-b1-l2-11', 'goethe-b1-l2-12'])->all();
    @endphp

    {{-- Instruction box --}}
    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2.5rem; color: #1e293b; background: #f0fdfa; border-left: 4px solid #0f766e; padding: 1.25rem 1.5rem; font-size: 0.98rem; line-height: 1.6; border-radius: 6px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- ARTICLE 1 & QUESTIONS --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; align-items: flex-start; width: 100%; margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
        
        {{-- Left: Article 1 --}}
        <div style="flex: 1.2; min-width: 380px;">
            <div style="border: 1px solid #cbd5e1; border-radius: 12px; background: #fafaf9; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="background: #e7e5e4; padding: 0.75rem 1.5rem; font-size: 0.85rem; font-weight: 700; color: #57534e; text-transform: uppercase; letter-spacing: 0.05em; display: flex; justify-content: space-between;">
                    <span>Pressebericht</span>
                    <span>Brandenburg, Deutschland</span>
                </div>
                
                <div style="padding: 2rem 2.5rem;">
                    @if ($art1El)
                        <div style="font-size: 0.95rem; color: #57534e; font-weight: 700; line-height: 1.4; border-left: 3px solid #0f766e; padding-left: 1rem; margin-bottom: 1.5rem;">
                            {{ $art1El['instruction'] }}
                        </div>
                        <h3 style="font-size: 1.6rem; font-weight: 900; color: #1c1917; text-align: center; margin-top: 0; margin-bottom: 1.5rem; font-family: Georgia, serif;">
                            Ein Dorf für grüne Energie
                        </h3>
                        <div style="font-size: 0.98rem; color: #292524; line-height: 1.75; text-align: justify; font-family: Georgia, serif;">
                            {!! nl2br(e($art1El['content'])) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Questions 7 to 9 --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            {{-- Example (Beispiel) --}}
            <div style="background: #f5f5f4; border: 1px solid #e7e5e4; border-radius: 10px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; position: relative;">
                <div style="position: absolute; top: 0; right: 1rem; transform: translateY(-50%); background: #e7e5e4; color: #57534e; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.15rem 0.5rem; border-radius: 4px;">
                    Beispiel
                </div>
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="background: #d6d3d1; color: #57534e; font-weight: 800; font-size: 0.85rem; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">0</span>
                    <div style="font-size: 0.98rem; font-weight: 700; color: #57534e; line-height: 1.5;">Die Bio-Gasanlage ...</div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 2.35rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #78716c;">
                        <span style="border: 2.5px solid #d6d3d1; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">a</span>
                        gehört Eckhard Meier.
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #78716c;">
                        <span style="border: 2.5px solid #d6d3d1; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">b</span>
                        gibt es seit einem Dreivierteljahr.
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #0f766e; font-weight: 700;">
                        <span style="border: 2.5px solid #0f766e; border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; background: #f0fdfa; text-decoration: line-through; text-decoration-thickness: 2px;">c</span>
                        produziert Strom und Wärme.
                    </div>
                </div>
            </div>

            {{-- Questions 7-9 loop --}}
            @include('partials.premium.goethe-b1-mc-loop', ['questions' => $questions1])

        </div>

    </div>

    {{-- ARTICLE 2 & QUESTIONS --}}
    <div style="display: flex; gap: 3rem; flex-wrap: wrap; align-items: flex-start; width: 100%;">
        
        {{-- Left: Article 2 --}}
        <div style="flex: 1.2; min-width: 380px;">
            <div style="border: 1px solid #cbd5e1; border-radius: 12px; background: #fafaf9; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="background: #e7e5e4; padding: 0.75rem 1.5rem; font-size: 0.85rem; font-weight: 700; color: #57534e; text-transform: uppercase; letter-spacing: 0.05em; display: flex; justify-content: space-between;">
                    <span>Reisebroschüre</span>
                    <span>Murten, Schweiz</span>
                </div>
                
                <div style="padding: 2rem 2.5rem;">
                    @if ($art2El)
                        <div style="font-size: 0.95rem; color: #57534e; font-weight: 700; line-height: 1.4; border-left: 3px solid #0f766e; padding-left: 1rem; margin-bottom: 1.5rem;">
                            {{ $art2El['instruction'] }}
                        </div>
                        <h3 style="font-size: 1.6rem; font-weight: 900; color: #1c1917; text-align: center; margin-top: 0; margin-bottom: 1.5rem; font-family: Georgia, serif;">
                            Tour durch Murtens Geschichte
                        </h3>
                        <div style="font-size: 0.98rem; color: #292524; line-height: 1.75; text-align: justify; font-family: Georgia, serif;">
                            {!! nl2br(e($art2El['content'])) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Questions 10 to 12 --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            
            {{-- Questions 10-12 loop --}}
            @include('partials.premium.goethe-b1-mc-loop', ['questions' => $questions2])

        </div>

    </div>

</section>
