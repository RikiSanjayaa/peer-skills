<x-guest-layout>
    <h4 class="text-center mb-3">Konfirmasi Kata Sandi</h4>

    <p class="text-muted small text-center mb-4">
        Ini adalah area aplikasi yang aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Konfirmasi
            </button>
        </div>
    </form>
</x-guest-layout>
