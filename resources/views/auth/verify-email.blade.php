<x-guest-layout>
    <h4 class="text-center mb-3">Verify Email</h4>

    <p class="text-muted small mb-4">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we
        just emailed to you?
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success" role="alert">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none small" style="color: #00BCD4;">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
