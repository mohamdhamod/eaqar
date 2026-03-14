@props([
    'property'       => null,
    'existingImages' => collect(),
    'isEditMode'     => false,
])

@php
    $propSlug  = $property?->slug ?? null;
    $propId    = $property?->id   ?? null;
    $locale    = app()->getLocale();
@endphp

{{-- ═══════════════════════════════════════════════════════════════
     PROPERTY IMAGE UPLOAD COMPONENT
     ─ Professional image gallery with drag-drop upload
     ─ Uses general.js: CRUDManager, ToastManager, SwalHelper, Utils
     ═══════════════════════════════════════════════════════════════ --}}

<div class="prop-img-manager" id="propertyImageUpload">

    {{-- ── EXISTING IMAGES (edit mode only) ───────────────────────── --}}
    @if($isEditMode && $existingImages->isNotEmpty())
    <div class="prop-img-manager__section" id="piuExistingSection">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold mb-0">
                <i class="bi bi-images me-2 text-primary"></i>{{ __('translation.property.existing_images') }}
                <span class="badge bg-primary bg-opacity-10 text-primary ms-2" id="piuExistingCount">{{ $existingImages->count() }}</span>
            </h6>
        </div>

        <div class="row g-3" id="piuExistingGrid">
            @foreach($existingImages as $img)
            <div class="col-6 col-sm-4 col-md-3" data-img-id="{{ $img->id }}">
                <div class="prop-img-card {{ $img->is_main ? 'prop-img-card--main' : '' }}">
                    <div class="prop-img-card__wrapper">
                        <img src="{{ $img->image_path }}" class="prop-img-card__img" loading="lazy" alt="">

                        {{-- Main badge --}}
                        @if($img->is_main)
                        <span class="prop-img-card__badge prop-img-card__badge--main">
                            <i class="bi bi-star-fill"></i> {{ __('translation.property.main') }}
                        </span>
                        @endif

                        {{-- Overlay actions --}}
                        <div class="prop-img-card__overlay">
                            <div class="prop-img-card__actions">
                                <button type="button"
                                        class="prop-img-card__btn prop-img-card__btn--star js-set-main"
                                        data-img="{{ $img->id }}"
                                        title="{{ __('translation.property.set_main') }}">
                                    <i class="bi bi-star-fill"></i>
                                </button>
                                <button type="button"
                                        class="prop-img-card__btn prop-img-card__btn--del js-delete"
                                        data-img="{{ $img->id }}"
                                        title="{{ __('translation.general.delete') }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <hr class="my-4 opacity-10">
    @endif

    {{-- ── NEW IMAGES UPLOAD ────────────────────────────────────────── --}}
    <div class="prop-img-manager__section">
        @if($isEditMode)
        <h6 class="fw-semibold mb-3">
            <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>{{ __('translation.property.add_more_images') }}
        </h6>
        @endif

        {{-- Drop Zone --}}
        <div class="prop-img-dropzone" id="piuDropZone" tabindex="0" role="button"
             aria-label="{{ __('translation.property.drag_drop_images') }}">
            <input type="file" id="piuFileInput" name="images[]" multiple accept="image/*" hidden>
            <div class="prop-img-dropzone__inner">
                <div class="prop-img-dropzone__icon">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <p class="prop-img-dropzone__title">{{ __('translation.property.drag_drop_images') }}</p>
                <p class="prop-img-dropzone__sub">{{ __('translation.property.or_click_to_select') }}</p>
                <span class="prop-img-dropzone__hint">
                    JPG, PNG, WebP, GIF &bull; {{ __('translation.property.max_5mb') }}
                </span>
            </div>
        </div>

        {{-- New images preview --}}
        <div class="row g-3 mt-2" id="piuNewGrid"></div>

        {{-- Upload summary --}}
        <div class="d-none align-items-center justify-content-between mt-3 p-2 rounded-3 bg-light border" id="piuSummary">
            <span class="text-muted small" id="piuSummaryText"></span>
            <button type="button" class="btn btn-sm btn-outline-danger" id="piuClearAll">
                <i class="bi bi-x-circle me-1"></i>{{ __('translation.general.clear_all') }}
            </button>
        </div>
    </div>

    <input type="hidden" id="main_image_index" name="main_image_index" value="0">
</div>

@push('styles')
<style>
/* ─── Image Card ─────────────────────────── */
.prop-img-card { position: relative; border-radius: .75rem; overflow: hidden; }
.prop-img-card__wrapper { position: relative; aspect-ratio: 4/3; background: var(--ins-body-bg, #f8f9fa); }
.prop-img-card__img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
.prop-img-card:hover .prop-img-card__img { transform: scale(1.05); }

.prop-img-card__badge { position: absolute; top: .5rem; left: .5rem; z-index: 2;
    font-size: .7rem; font-weight: 600; padding: .25rem .5rem; border-radius: .375rem;
    display: inline-flex; align-items: center; gap: .25rem; }
.prop-img-card__badge--main { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff;
    box-shadow: 0 2px 8px rgba(245, 158, 11, .3); }

.prop-img-card__overlay { position: absolute; inset: 0; z-index: 3;
    background: rgba(0,0,0,.45); display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .25s ease; }
.prop-img-card:hover .prop-img-card__overlay { opacity: 1; }

.prop-img-card__actions { display: flex; gap: .5rem; }
.prop-img-card__btn { width: 2.25rem; height: 2.25rem; border-radius: 50%; border: none; cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center; font-size: .85rem;
    transition: transform .15s ease, box-shadow .15s ease; }
.prop-img-card__btn:hover { transform: scale(1.15); }
.prop-img-card__btn--star { background: rgba(255,255,255,.95); color: #f59e0b; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
.prop-img-card__btn--star:hover { box-shadow: 0 4px 14px rgba(245,158,11,.4); }
.prop-img-card__btn--del { background: rgba(255,255,255,.95); color: #ef4444; box-shadow: 0 2px 8px rgba(0,0,0,.15); }
.prop-img-card__btn--del:hover { box-shadow: 0 4px 14px rgba(239,68,68,.4); }

.prop-img-card--main { outline: 2px solid #f59e0b; outline-offset: -2px; border-radius: .75rem; }
.prop-img-card--loading { pointer-events: none; opacity: .5; }

/* ─── Drop Zone ──────────────────────────── */
.prop-img-dropzone { border: 2px dashed var(--ins-border-color, #dee2e6); border-radius: .75rem;
    cursor: pointer; transition: all .25s ease; background: var(--ins-body-bg, #f8f9fa); }
.prop-img-dropzone:hover, .prop-img-dropzone.active { border-color: var(--ins-primary, #428177);
    background: rgba(66, 129, 119, .04); }
.prop-img-dropzone__inner { display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 2rem 1rem; text-align: center; }
.prop-img-dropzone__icon { font-size: 2.5rem; color: var(--ins-primary, #428177); margin-bottom: .75rem;
    line-height: 1; opacity: .7; }
.prop-img-dropzone__title { font-weight: 600; font-size: .95rem; margin-bottom: .25rem; color: var(--ins-body-color, #374151); }
.prop-img-dropzone__sub { font-size: .85rem; color: var(--ins-secondary-color, #6b7280); margin-bottom: .5rem; }
.prop-img-dropzone__hint { font-size: .75rem; color: var(--ins-secondary-color, #9ca3af);
    background: rgba(0,0,0,.03); padding: .25rem .75rem; border-radius: 1rem; }

/* ─── New Image Preview Card ─────────────── */
.prop-img-new-card { position: relative; border-radius: .75rem; overflow: hidden; }
.prop-img-new-card__wrapper { position: relative; aspect-ratio: 4/3; background: var(--ins-body-bg, #f8f9fa); }
.prop-img-new-card__img { width: 100%; height: 100%; object-fit: cover; }
.prop-img-new-card__remove { position: absolute; top: .375rem; right: .375rem; z-index: 2;
    width: 1.75rem; height: 1.75rem; border-radius: 50%; border: none; cursor: pointer;
    background: rgba(0,0,0,.6); color: #fff; font-size: .75rem;
    display: inline-flex; align-items: center; justify-content: center;
    transition: background .2s ease; }
.prop-img-new-card__remove:hover { background: #ef4444; }
.prop-img-new-card__name { font-size: .7rem; color: var(--ins-secondary-color, #6b7280);
    text-align: center; padding: .25rem .25rem 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Config ─────────────────────────────── */
    const LOCALE    = '{{ $locale }}';
    const PROP_SLUG = '{{ $propSlug }}';
    const IS_EDIT   = {{ $isEditMode ? 'true' : 'false' }};
    const MAX_SIZE  = 5 * 1024 * 1024;

    /* ── Shared: POST with _method override (Apache-safe) ─── */
    function spoofedFetch(url, method, onSuccess, onError) {
        // URLSearchParams → application/x-www-form-urlencoded so Laravel ParameterBag parses _method
        const body = new URLSearchParams({ _method: method });
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': Utils.getCSRFToken(),
                'X-HTTP-METHOD-OVERRIDE': method,
                'Accept': 'application/json',
            },
            body: body,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { onSuccess(data); }
            else { onError(data); }
        })
        .catch(() => onError(null));
    }

    /* ══════════════════════════════════════════
       EXISTING IMAGE OPERATIONS (edit mode)
       ══════════════════════════════════════════ */
    if (IS_EDIT) {

        /* ── Delete Image ─────────────────── */
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-delete');
            if (!btn) return;

            const propertiesUrl = '{{route("properties.index")}}';
            const imgId   = btn.dataset.img;
            const url     = propertiesUrl + `/${PROP_SLUG}/images/${imgId}`;

            const card = document.querySelector(`#piuExistingGrid [data-img-id="${imgId}"]`);
            card?.querySelector('.prop-img-card')?.classList.add('prop-img-card--loading');

            spoofedFetch(url, 'DELETE',
                function(data) {
                    if (card) {
                        card.style.transition = 'opacity .3s, transform .3s';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            card.remove();
                            const cnt = document.querySelectorAll('#piuExistingGrid [data-img-id]').length;
                            const badge = document.getElementById('piuExistingCount');
                            if (badge) badge.textContent = cnt;
                        }, 300);
                    }
                    Toast.show(data.message || '{{ __("translation.general.deleted_successfully") }}', 'success');
                },
                function(data) {
                    card?.querySelector('.prop-img-card')?.classList.remove('prop-img-card--loading');
                    Toast.show((data && data.message) || '{{ __("translation.general.error_occurred") }}', 'danger');
                }
            );
        });

        /* ── Set Main Image ───────────────── */
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.js-set-main');
            if (!btn) return;

            const propertiesUrl = '{{route("properties.index")}}';
            const imgId = btn.dataset.img;
            const url   = propertiesUrl + `/${PROP_SLUG}/images/${imgId}/main`;
            const card  = document.querySelector(`#piuExistingGrid [data-img-id="${imgId}"]`);

            card?.querySelector('.prop-img-card')?.classList.add('prop-img-card--loading');

            spoofedFetch(url, 'PUT',
                function(data) {
                    // Remove main badge from all
                    document.querySelectorAll('#piuExistingGrid .prop-img-card').forEach(c => {
                        c.classList.remove('prop-img-card--main');
                        c.querySelector('.prop-img-card__badge--main')?.remove();
                    });
                    // Apply to this card
                    const cardEl = card?.querySelector('.prop-img-card');
                    if (cardEl) {
                        cardEl.classList.add('prop-img-card--main');
                        cardEl.classList.remove('prop-img-card--loading');
                        const wrapper = cardEl.querySelector('.prop-img-card__wrapper');
                        if (wrapper && !wrapper.querySelector('.prop-img-card__badge--main')) {
                            const badge = document.createElement('span');
                            badge.className = 'prop-img-card__badge prop-img-card__badge--main';
                            badge.innerHTML = '<i class="bi bi-star-fill"></i> {{ __("translation.property.main") }}';
                            wrapper.appendChild(badge);
                        }
                    }
                    Toast.show(data.message || '{{ __("translation.property.main_image_updated") }}', 'success');
                },
                function(data) {
                    card?.querySelector('.prop-img-card')?.classList.remove('prop-img-card--loading');
                    Toast.show((data && data.message) || '{{ __("translation.general.error_occurred") }}', 'danger');
                }
            );
        });
    }

    /* ══════════════════════════════════════════
       NEW IMAGES UPLOAD
       ══════════════════════════════════════════ */
    const dropZone    = document.getElementById('piuDropZone');
    const fileInput   = document.getElementById('piuFileInput');
    const newGrid     = document.getElementById('piuNewGrid');
    const summary     = document.getElementById('piuSummary');
    const summaryText = document.getElementById('piuSummaryText');
    const clearBtn    = document.getElementById('piuClearAll');

    window.propertyUploadedFiles = [];

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());
        dropZone.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fileInput.click(); }
        });

        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

        dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('active'); });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('active'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault(); dropZone.classList.remove('active');
            handleFiles(e.dataTransfer.files);
        });
    }

    function handleFiles(files) {
        const valid = [];
        for (const file of files) {
            if (!file.type.startsWith('image/')) {
                Toast.show(`${file.name} — {{ __('translation.validation.image_must_be_image') }}`, 'warning');
                continue;
            }
            if (file.size > MAX_SIZE) {
                Toast.show(`${file.name} — {{ __('translation.validation.images_max_size') }}`, 'danger');
                continue;
            }
            valid.push(file);
        }
        if (!valid.length) return;

        window.propertyUploadedFiles.push(...valid);
        renderNewGrid();
        updateSummary();
        Toast.show(`${valid.length} {{ __('translation.property.images_selected') }}`, 'success');
    }

    function renderNewGrid() {
        newGrid.innerHTML = '';
        window.propertyUploadedFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const col = document.createElement('div');
                col.className = 'col-6 col-sm-4 col-md-3';
                col.innerHTML = `
                    <div class="prop-img-new-card">
                        <div class="prop-img-new-card__wrapper">
                            <img src="${e.target.result}" class="prop-img-new-card__img" loading="lazy" alt="">
                            <button type="button" class="prop-img-new-card__remove" data-idx="${idx}" title="{{ __('translation.general.delete') }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="prop-img-new-card__name">${Utils.escapeHtml(file.name)}</div>
                    </div>`;
                newGrid.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }

    // Event delegation for removing new files
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.prop-img-new-card__remove');
        if (!btn) return;
        const idx = parseInt(btn.dataset.idx, 10);
        window.propertyUploadedFiles.splice(idx, 1);
        renderNewGrid();
        updateSummary();
    });

    function updateSummary() {
        const count = window.propertyUploadedFiles.length;
        if (count > 0) {
            summary.classList.remove('d-none');
            summary.classList.add('d-flex');
            summaryText.textContent = `${count} {{ __('translation.property.images_selected') }}`;
        } else {
            summary.classList.add('d-none');
            summary.classList.remove('d-flex');
        }
    }

    clearBtn?.addEventListener('click', () => {
        window.propertyUploadedFiles = [];
        renderNewGrid();
        updateSummary();
    });

})();
</script>
@endpush
