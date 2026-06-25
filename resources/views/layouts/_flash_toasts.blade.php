@php
    $flashMessages = collect([
        [
            'key' => 'success',
            'type' => 'success',
            'title' => 'Thành công',
            'icon' => 'bi-check-circle-fill',
        ],
        [
            'key' => 'status',
            'type' => 'success',
            'title' => 'Thành công',
            'icon' => 'bi-check-circle-fill',
        ],
        [
            'key' => 'error',
            'type' => 'danger',
            'title' => 'Có lỗi xảy ra',
            'icon' => 'bi-exclamation-triangle-fill',
        ],
        [
            'key' => 'danger',
            'type' => 'danger',
            'title' => 'Có lỗi xảy ra',
            'icon' => 'bi-exclamation-triangle-fill',
        ],
        [
            'key' => 'warning',
            'type' => 'warning',
            'title' => 'Cần chú ý',
            'icon' => 'bi-exclamation-circle-fill',
        ],
        [
            'key' => 'info',
            'type' => 'info',
            'title' => 'Thông tin',
            'icon' => 'bi-info-circle-fill',
        ],
    ])->filter(fn ($message) => session()->has($message['key']))->values();
@endphp

@if ($flashMessages->isNotEmpty())
    <div class="toast-container app-toast-container position-fixed bottom-0 end-0 p-3">
        @foreach ($flashMessages as $message)
            <div
                class="toast app-toast app-toast-{{ $message['type'] }} border-0"
                role="status"
                aria-live="polite"
                aria-atomic="true"
                data-bs-delay="4500"
                data-bs-autohide="true"
            >
                <div class="toast-body d-flex align-items-start gap-3">
                    <span class="app-toast-icon flex-shrink-0">
                        <i class="bi {{ $message['icon'] }}"></i>
                    </span>
                    <div class="app-toast-content">
                        <div class="app-toast-title">{{ $message['title'] }}</div>
                        <div class="app-toast-message">{{ session($message['key']) }}</div>
                    </div>
                    <button type="button" class="btn-close app-toast-close ms-auto mt-1" data-bs-dismiss="toast" aria-label="Đóng"></button>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .app-toast-container {
            z-index: 1090;
            width: min(420px, calc(100vw - 1.5rem));
        }

        .app-toast {
            width: 100%;
            margin-top: .75rem;
            overflow: hidden;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 16px 40px rgba(20, 64, 45, .18);
            animation: app-toast-enter .22s ease-out;
        }

        .app-toast .toast-body {
            padding: .95rem 1rem;
        }

        .app-toast-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .app-toast-content {
            min-width: 0;
            flex: 1;
        }

        .app-toast-title {
            color: #24352d;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: .2rem;
        }

        .app-toast-message {
            color: #53645b;
            font-size: .92rem;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .app-toast-close {
            width: .8rem;
            height: .8rem;
            padding: .35rem;
        }

        .app-toast-success {
            border-left: 4px solid var(--semantic-success) !important;
        }

        .app-toast-success .app-toast-icon {
            background: var(--semantic-success-soft);
            color: var(--semantic-success);
        }

        .app-toast-danger {
            border-left: 4px solid var(--semantic-danger) !important;
        }

        .app-toast-danger .app-toast-icon {
            background: var(--semantic-danger-soft);
            color: var(--semantic-danger);
        }

        .app-toast-warning {
            border-left: 4px solid var(--semantic-warning) !important;
        }

        .app-toast-warning .app-toast-icon {
            background: var(--semantic-warning-soft);
            color: var(--semantic-warning);
        }

        .app-toast-info {
            border-left: 4px solid var(--semantic-info) !important;
        }

        .app-toast-info .app-toast-icon {
            background: var(--semantic-info-soft);
            color: var(--semantic-info);
        }

        @keyframes app-toast-enter {
            from {
                opacity: 0;
                transform: translate3d(18px, 10px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @media (max-width: 575.98px) {
            .app-toast-container {
                right: .75rem !important;
                bottom: .75rem !important;
                left: .75rem !important;
                width: auto;
                padding: 0 !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.app-toast').forEach(function (toastEl) {
                bootstrap.Toast.getOrCreateInstance(toastEl).show();
            });
        });
    </script>
@endif
