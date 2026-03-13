<!-- Add/Edit Subscription Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.subscription.add_subscription') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" action="{{ route('subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">
                                {{ __('translation.subscription.subscription_key') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   placeholder="e.g., basic, professional"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="form-text">{{ __('translation.subscription.validation.key_format') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                {{ __('translation.subscription.subscription_name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="e.g., Basic Plan"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Price Field -->
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">
                                {{ __('translation.subscription.subscription_price') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="price" 
                                   name="price" 
                                   placeholder="0.00"
                                   step="0.01"
                                   min="0"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Currency Field -->
                        <div class="col-md-6 mb-3">
                            <label for="currency_id" class="form-label">
                                {{ __('translation.subscription.subscription_currency') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2" 
                                    id="currency_id" 
                                    name="currency_id" 
                                    required>
                                <option value="">{{ __('translation.common.select') }}</option>
                                @foreach(\App\Models\Configuration::where('key', 'currency')->where('active', true)->get() as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Duration Field -->
                        <div class="col-md-6 mb-3">
                            <label for="duration_days" class="form-label">
                                {{ __('translation.subscription.subscription_duration') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="duration_days" 
                                   name="duration_days" 
                                   placeholder="e.g., 30"
                                   min="1"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Max Properties Field -->
                        <div class="col-md-6 mb-3">
                            <label for="max_properties" class="form-label">
                                {{ __('translation.subscription.subscription_max_properties') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="max_properties" 
                                   name="max_properties" 
                                   placeholder="e.g., 5 (0 for unlimited)"
                                   min="0"
                                   required>
                            <div class="form-text">{{ __('translation.subscription.validation.max_properties_integer') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Icon Field -->
                        <div class="col-md-4 mb-3">
                            <label for="icon" class="form-label">
                                {{ __('translation.subscription.subscription_icon') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="icon-preview" class="fas fa-star"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="icon" 
                                       name="icon" 
                                       placeholder="e.g., fa-star"
                                       value="fa-star">
                            </div>
                            <div class="form-text">Font Awesome 6 class</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Color Field -->
                        <div class="col-md-4 mb-3">
                            <label for="color" class="form-label">
                                {{ __('translation.subscription.subscription_color') }}
                            </label>
                            <div class="input-group">
                                <input type="color" 
                                       class="form-control form-control-color" 
                                       id="color" 
                                       name="color" 
                                       value="#667eea"
                                       title="{{ __('translation.subscription.subscription_color') }}">
                                <input type="text" 
                                       class="form-control" 
                                       id="color_text" 
                                       placeholder="#667eea"
                                       value="#667eea"
                                       pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Sort Order Field -->
                        <div class="col-md-4 mb-3">
                            <label for="sort_order" class="form-label">
                                {{ __('translation.subscription.sort_order') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('translation.subscription.subscription_description') }}
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="{{ __('translation.subscription.subscription_description') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.common.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync color picker with text input
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('color_text');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');

    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value.toUpperCase();
        });

        colorText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }

    // Update icon preview
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            iconPreview.className = 'fas ' + this.value;
        });
    }
});

// Fill form from data
function fillForm(modal, data) {
    if (!modal) return;
    
    modal.querySelector('#key').value = data.key || '';
    modal.querySelector('#name').value = data.name || '';
    modal.querySelector('#description').value = data.description || '';
    modal.querySelector('#price').value = data.price || '';
    modal.querySelector('#currency_id').value = data.currency_id || '';
    modal.querySelector('#duration_days').value = data.duration_days || '';
    modal.querySelector('#max_properties').value = data.max_properties || '';
    modal.querySelector('#icon').value = data.icon || '';
    modal.querySelector('#color').value = data.color || '#667eea';
    
    const colorText = modal.querySelector('#color_text');
    if (colorText) {
        colorText.value = data.color || '#667eea';
    }
    
    modal.querySelector('#sort_order').value = data.sort_order || 0;
}

// Clear form errors
function clearFormErrors(modal) {
    if (!modal) return;
    modal.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
    });
    modal.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('is-invalid');
    });
}

// Show modal with tooltip
function showModal(modal) {
    if (!modal) return;
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
    
    // Initialize tooltips
    modal.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
}

// Handle form submission
window.handleFormSubmit = function(e, form) {
    e.preventDefault();
    
    const formData = new FormData(form);
    const modal = form.closest('.modal');
    
    fetch(form.action, {
        method: form.method === 'POST' ? 'POST' : 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            if (modal) {
                bootstrap.Modal.getInstance(modal).hide();
            }
            // Reload page
            window.location.href = data.redirect;
        } else {
            // Show error message
            if (data.errors) {
                // Display field errors
                for (const [field, messages] of Object.entries(data.errors)) {
                    const fieldEl = form.querySelector(`[name="${field}"]`);
                    if (fieldEl) {
                        fieldEl.classList.add('is-invalid');
                        const feedback = fieldEl.parentElement.querySelector('.invalid-feedback');
                        if (feedback) {
                            feedback.textContent = messages[0];
                        }
                    }
                }
            } else {
                alert(data.message || 'An error occurred');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
};

// Delete item
function confirmDelete(model, url, i18n, itemName) {
    const modal = document.getElementById('confirmModal');
    const confirmBtn = modal.querySelector('#confirmDeleteBtn');
    const message = modal.querySelector('#confirmMessage');
    
    if (message) {
        const deleteMsg = i18n.modal?.confirm_delete?.message_with_item || 'Are you sure you want to delete ":item"?';
        message.textContent = deleteMsg.replace(':item', itemName);
    }
    
    // Clear previous handler
    confirmBtn.onclick = null;
    
    // Add new handler
    confirmBtn.onclick = function() {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(modal).hide();
                window.location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    };
    
    showModal(modal);
}

// Confirm activate
function confirmActivate(model, url, i18n) {
    const modal = document.getElementById('confirmActivateModal');
    const confirmBtn = modal.querySelector('#confirmActivateBtn');
    const message = modal.querySelector('#confirmActivateMessage');
    
    if (message) {
        const activateMsg = model.active 
            ? (i18n.modal?.confirm_activate?.deactivate_message || 'Do you want to deactivate ":item"?')
            : (i18n.modal?.confirm_activate?.activate_message || 'Do you want to activate ":item"?');
        message.textContent = activateMsg.replace(':item', model.name);
    }
    
    // Clear previous handler
    confirmBtn.onclick = null;
    
    // Add new handler
    confirmBtn.onclick = function() {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(modal).hide();
                window.location.reload();
            } else {
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    };
    
    showModal(modal);
}
</script>
