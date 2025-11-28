@extends('layouts.app')

@section('title', 'Become a Seller - PeerSkill')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="mb-4">Become a Seller</h2>
                        <p class="text-muted mb-4">Start your freelancing journey on PeerSkill. Fill out the form below
                            to create your seller profile.</p>

                        <form method="POST" action="{{ route('seller.register') }}">
                            @csrf

                            <!-- Business Name -->
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                    id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" maxlength="1000" required>{{ old('description') }}</textarea>
                                <div class="form-text">Describe your expertise and what services you offer (max 1000
                                    characters)</div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Major -->
                            <div class="mb-3">
                                <label for="major" class="form-label">Major/Field of Study <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('major') is-invalid @enderror"
                                    id="major" name="major" value="{{ old('major') }}" required>
                                @error('major')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- University -->
                            <div class="mb-3">
                                <label for="university" class="form-label">University <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('university') is-invalid @enderror"
                                    id="university" name="university" value="{{ old('university') }}" required>
                                @error('university')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Portfolio URL -->
                            <div class="mb-3">
                                <label for="portfolio_url" class="form-label">Portfolio URL</label>
                                <input type="url" class="form-control @error('portfolio_url') is-invalid @enderror"
                                    id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}"
                                    placeholder="https://yourportfolio.com">
                                @error('portfolio_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Skills -->
                            <div class="mb-4">
                                <label class="form-label">Skills <span class="text-danger">*</span> <small
                                        class="text-muted">(Select up to 10)</small></label>
                                <div id="skills-container">
                                    @foreach ($skills->groupBy('category') as $category => $categorySkills)
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-2">{{ $category }}</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($categorySkills as $skill)
                                                    <div class="form-check">
                                                        <input class="form-check-input skill-checkbox" type="checkbox"
                                                            name="skills[]" value="{{ $skill->name }}"
                                                            id="skill-{{ $skill->id }}"
                                                            {{ in_array($skill->name, old('skills', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="skill-{{ $skill->id }}">
                                                            {{ $skill->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Custom Skills Input -->
                                    <div class="mt-3">
                                        <h6 class="fw-bold mb-2">Add Custom Skills</h6>
                                        <div id="custom-skills-container">
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control" id="custom-skill-input"
                                                    placeholder="Enter a custom skill">
                                                <button class="btn btn-outline-primary" type="button"
                                                    id="add-custom-skill">
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                        <div id="custom-skills-list" class="d-flex flex-wrap gap-2 mt-2"></div>
                                    </div>
                                </div>
                                <div class="form-text">Select or add skills that best describe your expertise (maximum
                                    10 total)</div>
                                @error('skills')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                @error('skills.*')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <div id="skill-count" class="text-muted small mt-2">Selected: <span
                                        id="count">0</span>/10</div>
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">Create Seller Profile</button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Skill counter and limit enforcement
        const maxSkills = 10;
        const skillCheckboxes = document.querySelectorAll('.skill-checkbox');
        const customSkillInput = document.getElementById('custom-skill-input');
        const addCustomSkillBtn = document.getElementById('add-custom-skill');
        const customSkillsList = document.getElementById('custom-skills-list');
        const countDisplay = document.getElementById('count');

        function updateCount() {
            const checkedBoxes = document.querySelectorAll('.skill-checkbox:checked').length;
            const customSkills = document.querySelectorAll('.custom-skill-tag').length;
            const total = checkedBoxes + customSkills;

            countDisplay.textContent = total;

            // Disable unchecked boxes if limit reached
            if (total >= maxSkills) {
                skillCheckboxes.forEach(box => {
                    if (!box.checked) {
                        box.disabled = true;
                    }
                });
                addCustomSkillBtn.disabled = true;
                customSkillInput.disabled = true;
            } else {
                skillCheckboxes.forEach(box => {
                    box.disabled = false;
                });
                addCustomSkillBtn.disabled = false;
                customSkillInput.disabled = false;
            }
        }

        skillCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCount);
        });

        // Add custom skill
        addCustomSkillBtn.addEventListener('click', function() {
            const skillName = customSkillInput.value.trim();

            if (!skillName) {
                return;
            }

            const checkedBoxes = document.querySelectorAll('.skill-checkbox:checked').length;
            const customSkills = document.querySelectorAll('.custom-skill-tag').length;
            const total = checkedBoxes + customSkills;

            if (total >= maxSkills) {
                alert('You can only select up to 10 skills.');
                return;
            }

            // Check if skill already exists
            const existingSkills = Array.from(document.querySelectorAll('.skill-checkbox'))
                .filter(cb => cb.checked)
                .map(cb => cb.value.toLowerCase());
            const existingCustomSkills = Array.from(document.querySelectorAll('.custom-skill-tag'))
                .map(tag => tag.dataset.skill.toLowerCase());

            if (existingSkills.includes(skillName.toLowerCase()) || existingCustomSkills.includes(skillName
                    .toLowerCase())) {
                alert('This skill is already added.');
                customSkillInput.value = '';
                return;
            }

            // Create skill tag
            const skillTag = document.createElement('div');
            skillTag.className = 'badge bg-primary custom-skill-tag d-flex align-items-center';
            skillTag.dataset.skill = skillName;
            skillTag.innerHTML = `
                ${skillName}
                <input type="hidden" name="skills[]" value="${skillName}">
                <button type="button" class="btn-close btn-close-white ms-2" style="font-size: 0.7rem;"></button>
            `;

            skillTag.querySelector('.btn-close').addEventListener('click', function() {
                skillTag.remove();
                updateCount();
            });

            customSkillsList.appendChild(skillTag);
            customSkillInput.value = '';
            updateCount();
        });

        // Allow adding custom skill with Enter key
        customSkillInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustomSkillBtn.click();
            }
        });

        // Initial count
        updateCount();
    </script>
@endpush
