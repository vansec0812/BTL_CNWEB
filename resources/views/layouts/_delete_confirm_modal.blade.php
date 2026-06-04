<!-- Custom Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center">
                <!-- Icon warning with pulse animation -->
                <div class="warning-icon-wrapper">
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                </div>
                
                <!-- Title -->
                <h5 class="modal-title mb-2" id="confirmDeleteModalLabel">Xác nhận xóa</h5>
                
                <!-- Message text -->
                <p class="modal-text mb-4 px-2" id="confirmDeleteModalMessage">
                    Bạn có chắc chắn muốn xóa mục này? Hành động này không thể hoàn tác và dữ liệu liên quan sẽ bị xóa vĩnh viễn.
                </p>
                
                <!-- Actions -->
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-confirm-cancel flex-grow-1" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="button" class="btn btn-danger btn-confirm-submit flex-grow-1" id="confirmDeleteSubmitBtn">Xóa dữ liệu</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #confirmDeleteModal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
    }

    #confirmDeleteModal .modal-body {
        padding: 2.25rem 2rem !important;
    }

    #confirmDeleteModal .warning-icon-wrapper {
        width: 72px;
        height: 72px;
        background-color: rgba(220, 53, 69, 0.08);
        color: #dc3545;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        animation: pulse-danger 2s infinite;
    }

    @keyframes pulse-danger {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(220, 53, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    #confirmDeleteModal .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    #confirmDeleteModal .modal-text {
        font-size: 0.925rem;
        color: #64748b;
        line-height: 1.5;
    }

    #confirmDeleteModal .btn-confirm-cancel {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        color: #475569;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    #confirmDeleteModal .btn-confirm-cancel:hover {
        background-color: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    #confirmDeleteModal .btn-confirm-submit {
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(220, 53, 69, 0.2), 0 2px 4px -2px rgba(220, 53, 69, 0.2);
    }

    #confirmDeleteModal .btn-confirm-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(220, 53, 69, 0.3), 0 4px 6px -4px rgba(220, 53, 69, 0.3);
    }

    #confirmDeleteModal .btn-confirm-submit:active {
        transform: translateY(0);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('confirmDeleteModal');
    if (!modalEl) return;
    
    const bsModal = new bootstrap.Modal(modalEl);
    const confirmBtn = document.getElementById('confirmDeleteSubmitBtn');
    const messageEl = document.getElementById('confirmDeleteModalMessage');
    const titleEl = document.getElementById('confirmDeleteModalLabel');
    const iconWrapper = modalEl.querySelector('.warning-icon-wrapper');
    const iconEl = iconWrapper.querySelector('i');
    
    let activeForm = null;
    let activeTrigger = null;

    // Standard styling configs mapping confirm types to CSS class names
    const styleConfigs = {
        danger: {
            btnClass: 'btn btn-danger btn-confirm-submit flex-grow-1',
            iconClass: 'bi bi-exclamation-triangle fs-2',
            iconBg: 'rgba(220, 53, 69, 0.08)',
            iconColor: '#dc3545',
            pulseColor: 'rgba(220, 53, 69, 0.4)'
        },
        warning: {
            btnClass: 'btn btn-warning btn-confirm-submit flex-grow-1 text-dark',
            iconClass: 'bi bi-exclamation-circle fs-2',
            iconBg: 'rgba(255, 193, 7, 0.1)',
            iconColor: '#ffc107',
            pulseColor: 'rgba(255, 193, 7, 0.4)'
        },
        info: {
            btnClass: 'btn btn-info btn-confirm-submit flex-grow-1 text-white',
            iconClass: 'bi bi-info-circle fs-2',
            iconBg: 'rgba(13, 202, 240, 0.1)',
            iconColor: '#0dcaf0',
            pulseColor: 'rgba(13, 202, 240, 0.4)'
        }
    };

    // Apply color and styles dynamically to the modal icon and button
    function applyStyles(type) {
        const config = styleConfigs[type] || styleConfigs.danger;
        
        // Update confirm button class
        confirmBtn.className = config.btnClass;
        
        // Update icon and its background/color/animation
        iconEl.className = config.iconClass;
        iconWrapper.style.backgroundColor = config.iconBg;
        iconWrapper.style.color = config.iconColor;
        
        // Inject dynamic keyframe animation for pulsed glow matching the color type
        let styleTag = document.getElementById('modal-pulse-dynamic');
        if (!styleTag) {
            styleTag = document.createElement('style');
            styleTag.id = 'modal-pulse-dynamic';
            document.head.appendChild(styleTag);
        }
        styleTag.innerHTML = `
            @keyframes pulse-dynamic {
                0% { box-shadow: 0 0 0 0 ${config.pulseColor}; }
                70% { box-shadow: 0 0 0 15px rgba(0, 0, 0, 0); }
                100% { box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
            }
            #confirmDeleteModal .warning-icon-wrapper {
                animation: pulse-dynamic 2s infinite !important;
            }
        `;
    }

    // Expose global function to parse existing window.confirm on demand (e.g. dynamic elements)
    window.setupConfirmForms = function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const onsubmitAttr = form.getAttribute('onsubmit');
            if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
                const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
                const message = match ? match[1] : 'Bạn có chắc chắn muốn thực hiện hành động này?';
                
                form.setAttribute('data-confirm', message);
                form.removeAttribute('onsubmit');
            }
        });
    };
    
    // Scan initial DOM elements
    window.setupConfirmForms();

    // 1. Intercept forms using submit event
    document.addEventListener('submit', function (e) {
        const form = e.target;
        
        if (form.hasAttribute('data-confirm')) {
            if (form.dataset.confirmed === 'true') {
                return; // Let the submission complete
            }
            
            e.preventDefault();
            activeForm = form;
            activeTrigger = null;
            
            const message = form.getAttribute('data-confirm');
            const customTitle = form.getAttribute('data-confirm-title') || 'Xác nhận xóa';
            const customButtonText = form.getAttribute('data-confirm-button') || 'Xóa dữ liệu';
            const type = form.getAttribute('data-confirm-type') || 'danger';
            
            messageEl.textContent = message;
            titleEl.textContent = customTitle;
            confirmBtn.textContent = customButtonText;
            
            applyStyles(type);
            bsModal.show();
        }
    });

    // 2. Intercept custom links or independent buttons with data-confirm
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;
        
        const form = trigger.closest('form');
        if (form && trigger.type === 'submit') {
            return; // Let submit handler handle forms
        }
        
        if (trigger.dataset.confirmed === 'true') {
            return;
        }
        
        e.preventDefault();
        activeTrigger = trigger;
        activeForm = null;
        
        const message = trigger.getAttribute('data-confirm');
        const customTitle = trigger.getAttribute('data-confirm-title') || 'Xác nhận';
        const customButtonText = trigger.getAttribute('data-confirm-button') || 'Xác nhận';
        const type = trigger.getAttribute('data-confirm-type') || 'danger';
        
        messageEl.textContent = message;
        titleEl.textContent = customTitle;
        confirmBtn.textContent = customButtonText;
        
        applyStyles(type);
        bsModal.show();
    });

    // Handle confirm action
    confirmBtn.addEventListener('click', function () {
        if (activeForm) {
            activeForm.dataset.confirmed = 'true';
            activeForm.submit();
        } else if (activeTrigger) {
            activeTrigger.dataset.confirmed = 'true';
            if (activeTrigger.tagName === 'A' && activeTrigger.getAttribute('href')) {
                window.location.href = activeTrigger.getAttribute('href');
            } else {
                activeTrigger.click();
            }
        }
        bsModal.hide();
    });
});
</script>
