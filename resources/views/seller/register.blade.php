@extends('layouts.app')

@section('title', isset($isResubmit) ? 'Ajukan Ulang Penjual - PeerSkill' : 'Daftar Sebagai Penjual - PeerSkill')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Rejection Alert for Resubmit --}}
            @if (isset($isResubmit) && isset($rejectedSeller))
                <div class="alert alert-danger border-danger mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading fw-bold mb-2">Pengajuan Sebelumnya Ditolak</h5>
                            <p class="mb-2">{{ $rejectedSeller->rejection_reason }}</p>
                            <small class="text-muted">
                                Ditolak pada {{ $rejectedSeller->rejected_at?->format('d M Y, H:i') ?? '-' }}
                            </small>
                            <hr class="my-3">
                            <p class="mb-0 small">
                                <i class="bi bi-info-circle me-1"></i>
                                Silakan perbaiki data pengajuan Anda berdasarkan alasan penolakan di atas dan ajukan
                                kembali.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="mb-4">
                        @if (isset($isResubmit))
                            <i class="bi bi-arrow-repeat me-2"></i>Ajukan Ulang Penjual
                        @else
                            Daftar Sebagai Penjual
                        @endif
                    </h2>
                    <p class="text-muted mb-4">
                        @if (isset($isResubmit))
                            Perbaiki data pengajuan Anda dan kirim ulang untuk direview.
                        @else
                            Mulailah perjalanan freelance Anda di PeerSkill. Isi formulir di bawah ini untuk membuat
                            profil penjual Anda.
                        @endif
                    </p>

                    <form method="POST" action="{{ route('seller.register') }}">
                        @csrf

                        @if (isset($isResubmit) && isset($rejectedSeller))
                            <input type="hidden" name="resubmit_id" value="{{ $rejectedSeller->id }}">
                        @endif

                        <!-- Business Name -->
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nama Bisnis <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                id="business_name" name="business_name"
                                value="{{ old('business_name', $rejectedSeller->business_name ?? '') }}" required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4" maxlength="1000" required>{{ old('description', $rejectedSeller->description ?? '') }}</textarea>
                            <div class="form-text">Jelaskan keahlian Anda dan layanan apa yang Anda tawarkan (maks 1000
                                karakter)</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Major -->
                        <div class="mb-3">
                            <label for="major" class="form-label">Jurusan/Bidang Studi <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('major') is-invalid @enderror"
                                id="major" name="major" value="{{ old('major', $rejectedSeller->major ?? '') }}"
                                required>
                            @error('major')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- University -->
                        <div class="mb-3">
                            <label for="university" class="form-label">Universitas <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('university') is-invalid @enderror"
                                id="university" name="university"
                                value="{{ old('university', $rejectedSeller->university ?? '') }}" required>
                            @error('university')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Portfolio URL -->
                        <div class="mb-3">
                            <label for="portfolio_url" class="form-label">URL Portofolio</label>
                            <input type="url" class="form-control @error('portfolio_url') is-invalid @enderror"
                                id="portfolio_url" name="portfolio_url"
                                value="{{ old('portfolio_url', $rejectedSeller->portfolio_url ?? '') }}"
                                placeholder="https://yourportfolio.com">
                            @error('portfolio_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @php
                            $previousSkills = old('skills', $rejectedSeller->skills ?? []);
                        @endphp

                        <!-- Skills -->
                        <div class="mb-4">
                            <label class="form-label">Keahlian <span class="text-danger">*</span> <small
                                    class="text-muted">(Pilih hingga 10)</small></label>
                            <div id="skills-container">
                                @foreach ($skills->groupBy('category') as $category => $categorySkills)
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">{{ $category }}</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($categorySkills as $skill)
                                                <label class="skill-badge" data-skill="{{ $skill->name }}">
                                                    {{ $skill->name }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Custom Skills Input -->
                                <div class="mt-3">
                                    <h6 class="fw-bold mb-2">Tambah Keahlian Kustom</h6>
                                    <div id="custom-skills-container">
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="custom-skill-input"
                                                placeholder="Masukkan keahlian kustom">
                                            <button class="btn btn-outline-primary" type="button"
                                                id="add-custom-skill">
                                                Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div id="custom-skills-list" class="d-flex flex-wrap gap-2 mt-2"></div>
                                </div>
                            </div>
                            <div class="form-text">Pilih atau tambahkan keahlian yang paling menggambarkan keahlian Anda (maksimum
                                10 total)</div>
                            @error('skills')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @error('skills.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <div id="skill-count" class="text-muted small mt-2">Terpilih: <span
                                    id="count">0</span>/10</div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">Buat Profil Penjual</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 14px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        background-color: #ffffff;
    }
    .form-control:focus {
        border-color: #74c0fc;
        box-shadow: 0 2px 6px rgba(116,192,252,0.25);
        outline: none;
    }

    .skill-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.9rem;
        border-radius: 50px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        background-color: #ffffff;
        color: #495057;
        margin-bottom: 4px;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    .skill-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }

    .skill-badge.selected {
        background-color: #74c0fc;
        border-color: #74c0fc;
        color: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }

    .skill-badge.disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .custom-skill-tag {
        font-size: 13px;
        padding: 0.45rem 0.9rem;
        border-radius: 50px;
        background-color: #f8f9fa;
        color: #212529;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: all 0.2s ease;
    }

    .custom-skill-tag:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }

    .skill-category {
        padding: 10px 12px;
        background-color: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 12px;
    }

    .skill-category h6 {
        margin-bottom: 8px;
        font-weight: 600;
        color: #343a40;
    }

    #custom-skills-list {
        min-height: 36px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    #skill-count {
        font-weight: 600;
        margin-top: 4px;
    }

    #custom-skills-container .form-control {
        border-radius: 8px 0 0 8px;
        border-right: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    #add-custom-skill {
        border-radius: 0 8px 8px 0;
        background-color: #91caff;
        color: #212529;
        border: 1px solid #91caff;
        transition: all 0.2s ease;
    }
    #add-custom-skill:hover {
        background-color: #74c0fc;
        color: #fff;
        border-color: #74c0fc;
    }
</style>
@endpush

@push('scripts')
<script>
    
    // Skill label handling tanpa checkbox
    document.querySelectorAll('.skill-badge').forEach(function(label){
        const skillName = label.dataset.skill;
        if(@json($previousSkills).includes(skillName)) {
            label.classList.add('selected');
        }

        label.addEventListener('click', function(){
            if(label.classList.contains('disabled')) return;
            label.classList.toggle('selected');
            updateCount();
        });
    });

    function updateCount() {
        const selectedLabels = document.querySelectorAll('.skill-badge.selected').length;
        const customSkills = document.querySelectorAll('.custom-skill-tag').length;
        const total = selectedLabels + customSkills;
        document.getElementById('count').textContent = total;

        const maxSkills = 10;
        document.querySelectorAll('.skill-badge').forEach(label => {
            if (!label.classList.contains('selected')) {
                label.classList.toggle('disabled', total >= maxSkills);
            }
        });

        document.getElementById('add-custom-skill').disabled = total >= maxSkills;
        document.getElementById('custom-skill-input').disabled = total >= maxSkills;
    }

    const addCustomSkillBtn = document.getElementById('add-custom-skill');
    const customSkillInput = document.getElementById('custom-skill-input');
    const customSkillsList = document.getElementById('custom-skills-list');

    addCustomSkillBtn.addEventListener('click', function(){
        const skillName = customSkillInput.value.trim();
        if (!skillName) return;

        const existingSkills = Array.from(document.querySelectorAll('.skill-badge.selected'))
            .map(lbl => lbl.dataset.skill.toLowerCase());
        const existingCustomSkills = Array.from(document.querySelectorAll('.custom-skill-tag'))
            .map(tag => tag.dataset.skill.toLowerCase());
        if (existingSkills.includes(skillName.toLowerCase()) || existingCustomSkills.includes(skillName.toLowerCase())) {
            alert('Skill sudah ada.');
            customSkillInput.value = '';
            return;
        }

        const skillTag = document.createElement('div');
        skillTag.className = 'custom-skill-tag';
        skillTag.dataset.skill = skillName;
        skillTag.innerHTML = `${skillName} <button type="button" class="btn-close btn-close-white btn-sm ms-1"></button>`;
        skillTag.querySelector('.btn-close').addEventListener('click', function(){
            skillTag.remove();
            updateCount();
        });

        customSkillsList.appendChild(skillTag);
        customSkillInput.value = '';
        updateCount();
    });

    customSkillInput.addEventListener('keypress', function(e){
        if (e.key === 'Enter') {
            e.preventDefault();
            addCustomSkillBtn.click();
        }
    });

    // Submit form dengan skill terpilih
    document.querySelector('form').addEventListener('submit', function(e){
        document.querySelectorAll('input[name="skills[]"]').forEach(el => el.remove());

        document.querySelectorAll('.skill-badge.selected').forEach(label => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills[]';
            input.value = label.dataset.skill;
            this.appendChild(input);
        });

        document.querySelectorAll('.custom-skill-tag').forEach(tag => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills[]';
            input.value = tag.dataset.skill;
            this.appendChild(input);
        });
    });

    updateCount();
</script>
@endpush
