                <header style="text-align: center; margin-bottom: 5rem;">
                    <div class="c1-badge">
                        {{ ($template['exam_provider'] ?? 'telc') === 'goethe' ? 'Goethe' : 'telc' }} {{ $template['level'] ?? 'C1' }}
                    </div>
                    <h1 class="c1-title">GERMAN SUCCESS</h1>
                    <p class="c1-subtitle">Deutsches Sprachzertifikat — Offizieller Übungstest</p>

                    @if ($result)
                        <div style="background: #ffffff; border: 2px solid #1a1a1a; border-radius: 8px; padding: 2.5rem; max-width: 600px; margin: 0 auto; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); font-family: 'Plus Jakarta Sans', sans-serif;">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #666666; display: block; margin-bottom: 0.5rem;">Offizielles Zertifikatsergebnis</span>
                            <h3 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a1a1a; margin-bottom: 1.5rem;">{{ $template['level'] ?? 'C1' }} Deutschprüfung</h3>

                            @if (($result['oral_max'] ?? 0) > 0)
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; padding: 1.5rem 0; margin-bottom: 1.5rem;">
                                    <div style="text-align: center; border-right: 1px solid #e5e5e5; padding-right: 1rem;">
                                        <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: #666666; display: block;">Schriftlicher Teil</span>
                                        <strong style="font-size: 1.8rem; color: #1a1a1a; display: block; margin-top: 0.25rem;">{{ $result['written_earned'] ?? 0 }} / {{ $result['written_max'] ?? 166 }}</strong>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: {{ ($result['written_earned'] ?? 0) >= (($result['written_max'] ?? 166) * 0.6) ? '#137333' : '#c5221f' }};">
                                            {{ ($result['written_earned'] ?? 0) >= (($result['written_max'] ?? 166) * 0.6) ? 'Bestanden' : 'Nicht bestanden' }}
                                        </span>
                                    </div>
                                    <div style="text-align: center; padding-left: 1rem;">
                                        <span style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: #666666; display: block;">Mündlicher Teil</span>
                                        <strong style="font-size: 1.8rem; color: #1a1a1a; display: block; margin-top: 0.25rem;">{{ $result['oral_earned'] ?? 0 }} / {{ $result['oral_max'] ?? 48 }}</strong>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: {{ ($result['oral_earned'] ?? 0) >= (($result['oral_max'] ?? 48) * 0.6) ? '#137333' : '#c5221f' }};">
                                            {{ ($result['oral_earned'] ?? 0) >= (($result['oral_max'] ?? 48) * 0.6) ? 'Bestanden' : 'Nicht bestanden' }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div style="border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; padding: 1.5rem 0; margin-bottom: 1.5rem; text-align: center;">
                                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: #666666; display: block;">Schriftlicher Teil</span>
                                    <strong style="font-size: 1.8rem; color: #1a1a1a; display: block; margin-top: 0.25rem;">{{ $result['written_earned'] ?? 0 }} / {{ $result['written_max'] ?? 166 }}</strong>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: {{ ($result['written_earned'] ?? 0) >= (($result['written_max'] ?? 166) * 0.6) ? '#137333' : '#c5221f' }};">
                                        {{ ($result['written_earned'] ?? 0) >= (($result['written_max'] ?? 166) * 0.6) ? 'Bestanden' : 'Nicht bestanden' }}
                                    </span>
                                </div>
                            @endif

                            <div style="margin-bottom: 1rem;">
                                <span style="font-size: 0.9rem; color: #666666;">Gesamtpunktzahl:</span>
                                <strong style="font-size: 2.2rem; color: #1a1a1a; display: block; font-family: 'Playfair Display', serif; margin-top: 0.25rem;">{{ $result['earned'] }} / {{ $result['possible'] }}</strong>
                            </div>

                            <div style="background-color: {{ ($result['passed'] ?? false) ? '#e6f4ea' : '#fce8e6' }}; padding: 0.75rem; border-radius: 4px; font-weight: 700; font-size: 1.1rem; color: {{ ($result['passed'] ?? false) ? '#137333' : '#c5221f' }}; margin-bottom: 1.5rem;">
                                Status: {{ $result['grade'] ?? 'Nicht bestanden' }}
                            </div>

                            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                                <button type="button" wire:click="repeat" style="background-color: #137333; color: #ffffff; border: none; padding: 0.6rem 1.5rem; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Prüfung wiederholen
                                </button>
                                <button type="button" @click="if ('speechSynthesis' in window) window.speechSynthesis.cancel();" wire:click="exitExam" style="background-color: #1a1a1a; color: #ffffff; border: none; padding: 0.6rem 1.5rem; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Zurück zur Übersicht
                                </button>
                            </div>
                        </div>
                    @endif
                </header>
