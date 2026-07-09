                 <!-- Doc Floating Right Timer / Submit widget -->
                 <div class="doc-timer-bar">
                     @if (isset($template['sections'][$activeSection]))
                         <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #666666; border-bottom: 1px solid #e5e5e5; padding-bottom: 0.25rem; width: 100%; text-align: center;">
                             {{ $template['sections'][$activeSection]['title'] }}
                         </div>
                     @endif

                     <div class="doc-timer-text">
                         <svg style="color: #d8232a;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                             <circle cx="12" cy="12" r="10"></circle>
                             <polyline points="12 6 12 12 16 14"></polyline>
                         </svg>
                         <span>Restzeit:</span>
                         <strong style="color: #d8232a;">{{ sprintf('%02d:%02d', $minutes, $seconds) }} Min.</strong>
                     </div>

                     <div class="save-indicator" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #666666;">
                         <div style="display: flex; align-items: center; gap: 0.25rem;">
                             <span class="save-pulse"></span>
                             <span>Auto-Safe aktiv</span>
                         </div>
                         <button type="button" wire:click="exitExam" style="background: none; border: none; color: #666666; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; cursor: pointer; text-decoration: underline;">Menü verlassen</button>
                     </div>

                     @if (!$result)
                         <button type="button" wire:click="submit" class="submit-button" style="width: 100%; white-space: nowrap;">
                             Prüfung abgeben
                         </button>
                     @else
                         <button type="button" wire:click="loadTemplate({{ $selectedTemplateId }})" class="submit-button" style="background-color: #d8232a; width: 100%; white-space: nowrap;">
                             Neustarten
                         </button>
                     @endif
                 </div>
