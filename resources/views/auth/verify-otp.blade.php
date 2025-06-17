<x-layouts.auth :title="__('Verify OTP')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Verify OTP') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter the OTP code sent to your {{ session('type') }}: 
                <strong>{{ session('identifier') }}</strong>
            </p>
        </div>

        @if (session('message'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-700">
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verify-otp.post') }}" class="mt-8 space-y-6">
            @csrf
            <input type="hidden" name="identifier" value="{{ session('identifier') }}">
            <input type="hidden" name="type" value="{{ session('type') }}">

            <!-- OTP Code -->
            <div>
                <x-forms.input 
                    id="otp" 
                    name="otp"
                    type="text"
                    label="OTP Code"
                    maxlength="6"
                    required
                    autofocus
                    placeholder="000000"
                    class="text-center text-2xl tracking-widest"
                />
            </div>

            <div class="flex items-center justify-between">
                <button type="button" id="resendOtp" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Resend OTP
                </button>

                <x-button type="submit">
                    {{ __('Verify') }}
                </x-button>
            </div>

            <div class="text-center">
                <a href="{{ route('auth.login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Back to Login
                </a>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const otpInput = document.getElementById('otp');
                const resendBtn = document.getElementById('resendOtp');
                let countdown = 0;

                // Auto-focus and format OTP input
                otpInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });

                // Resend OTP functionality
                resendBtn.addEventListener('click', function() {
                    if (countdown > 0) return;

                    const identifier = '{{ session("identifier") }}';
                    const type = '{{ session("type") }}';

                    this.disabled = true;
                    this.textContent = 'Sending...';

                    fetch('{{ route("auth.send-otp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            identifier: identifier,
                            type: type
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert('OTP sent successfully!');
                            startCountdown();
                        } else {
                            alert('Failed to send OTP');
                        }
                    })
                    .catch(error => {
                        alert('Error sending OTP');
                    })
                    .finally(() => {
                        if (countdown === 0) {
                            this.disabled = false;
                            this.textContent = 'Resend OTP';
                        }
                    });
                });

                function startCountdown() {
                    countdown = 60;
                    const interval = setInterval(() => {
                        if (countdown > 0) {
                            resendBtn.textContent = `Resend OTP (${countdown}s)`;
                            resendBtn.disabled = true;
                            countdown--;
                        } else {
                            resendBtn.textContent = 'Resend OTP';
                            resendBtn.disabled = false;
                            clearInterval(interval);
                        }
                    }, 1000);
                }
            });
        </script>
    </div>
</x-layouts.auth>
