@extends('layouts.app')

@section('title', 'Edit Profile - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-gear"></i> Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Banner Preview & Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Gambar Banner</label>
                                <div class="position-relative mb-3"
                                    style="height: 150px; background: linear-gradient(135deg, #00BCD4, #00ACC1); border-radius: 8px; overflow: hidden;">
                                    @if ($user->banner)
                                        <img src="{{ $user->banner_url }}" alt="Banner" class="w-100 h-100"
                                            style="object-fit: cover;" id="bannerPreview">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white"
                                            id="bannerPreview">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                        id="banner" name="banner" accept="image/*" onchange="previewBanner(this)">
                                    @if ($user->banner)
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#removeBannerModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="form-text">Ukuran yang disarankan: 1200x300 piksel (maks 4MB)</div>
                                @error('banner')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Avatar Preview & Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Foto Profil</label>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if ($user->avatar)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle"
                                            id="avatarPreview" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                            id="avatarPreview"
                                            style="width: 100px; height: 100px; background: linear-gradient(135deg, #00BCD4, #00ACC1); font-size: 2rem; font-weight: bold;">
                                            {{ $user->initials }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                            id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(this)">
                                        <div class="form-text">Rekomendasi gambar persegi (maks 2MB)</div>
                                        @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if ($user->avatar)
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#removeAvatarModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Basic Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Nama <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    placeholder="+1 234 567 8900">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="bio" class="form-label fw-bold">Tentang Saya</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4"
                                    placeholder="Ceritakan tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                                <div class="form-text">Maks 1000 karakter</div>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Social Links -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Tautan Sosial</label>
                                <div id="socialLinksContainer">
                                    @if ($user->social_links && count($user->social_links) > 0)
                                        @foreach ($user->social_links as $index => $link)
                                            <div class="row g-2 mb-2 social-link-row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control"
                                                        name="social_links[{{ $index }}][platform]"
                                                        placeholder="Platform (e.g., LinkedIn)"
                                                        value="{{ $link['platform'] ?? '' }}">
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="url" class="form-control"
                                                        name="social_links[{{ $index }}][url]"
                                                        placeholder="https://..." value="{{ $link['url'] ?? '' }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-outline-danger w-100"
                                                        onclick="removeSocialLink(this)">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row g-2 mb-2 social-link-row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control"
                                                    name="social_links[0][platform]"
                                                    placeholder="Platform (e.g., LinkedIn)">
                                            </div>
                                            <div class="col-md-7">
                                                <input type="url" class="form-control" name="social_links[0][url]"
                                                    placeholder="https://...">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-outline-danger w-100"
                                                    onclick="removeSocialLink(this)">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                    onclick="addSocialLink()">
                                    <i class="bi bi-plus"></i> Tambah Tautan Sosial
                                </button>
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.show', $user) }}" class="btn btn-outline-secondary">
                                    Lihat Profil
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Zona Berbahaya</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Setelah Anda menghapus akun, tidak ada jalan kembali. Harap pastikan.</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash"></i> Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Avatar Modal -->
    <div class="modal fade" id="removeAvatarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus foto profil Anda?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('profile.avatar.remove') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Remove Banner Modal -->
    <div class="modal fade" id="removeBannerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus gambar banner Anda?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('profile.banner.remove') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger fw-bold">Tindakan ini tidak dapat dibatalkan!</p>
                        <p>Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda:</p>
                        <input type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun Saya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let socialLinkIndex = {{ $user->social_links ? count($user->social_links) : 1 }};

        function addSocialLink() {
            const container = document.getElementById('socialLinksContainer');
            const row = document.createElement('div');
            row.className = 'row g-2 mb-2 social-link-row';
            row.innerHTML = `
                <div class="col-md-4">
                    <input type="text" class="form-control"
                           name="social_links[${socialLinkIndex}][platform]"
                           placeholder="Platform (e.g., LinkedIn)">
                </div>
                <div class="col-md-7">
                    <input type="url" class="form-control"
                           name="social_links[${socialLinkIndex}][url]"
                           placeholder="https://...">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="removeSocialLink(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
            container.appendChild(row);
            socialLinkIndex++;
        }

        function removeSocialLink(button) {
            const rows = document.querySelectorAll('.social-link-row');
            if (rows.length > 1) {
                button.closest('.social-link-row').remove();
            } else {
                // Clear the inputs instead of removing the last row
                const row = button.closest('.social-link-row');
                row.querySelectorAll('input').forEach(input => input.value = '');
            }
        }

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.id = 'avatarPreview';
                        img.src = e.target.result;
                        img.className = 'rounded-circle';
                        img.style = 'width: 100px; height: 100px; object-fit: cover;';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewBanner(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('bannerPreview').parentElement;
                    previewContainer.innerHTML =
                        `<img src="${e.target.result}" alt="Banner" class="w-100 h-100" style="object-fit: cover;" id="bannerPreview">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
