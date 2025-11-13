<div class="mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testimonialModal"
        onclick="resetTestimonialForm()">
        <i class="bi bi-plus-circle me-2"></i>Add Testimonial
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover" id="testimonialTable">
        <thead class="table-light">
            <tr>
                <th width="80">Photo</th>
                <th>Name</th>
                <th>Role</th>
                <th width="120">Rating</th>
                <th width="80">Status</th>
                <th width="100">Order</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Will be populated by JavaScript -->
        </tbody>
    </table>
</div>

<!-- Testimonial Modal -->
<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testimonialModalTitle">Add Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="testimonialForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="testimonial_id" name="testimonial_id">
                <input type="hidden" id="testimonial_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="testimonial_photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="testimonial_photo" name="photo"
                                accept="image/*">
                            <small class="text-muted">150x150 px, Max 1MB</small>
                            <div class="mt-2">
                                <img src="#" alt="Preview" class="image-preview d-none"
                                    id="testimonial_photo_preview" style="max-width: 150px; border-radius: 50%;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="testimonial_name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="testimonial_name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="testimonial_role" class="form-label">Role</label>
                            <select class="form-select" id="testimonial_role" name="role" required>
                                <option value="Donatur" selected>Donatur</option>
                                <option value="Wakif">Wakif</option>
                                <option value="Penerima Manfaat">Penerima Manfaat</option>
                                <option value="Pembuat Program">Pembuat Program</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="testimonial_rating" class="form-label">Rating *</label>
                            <select class="form-select" id="testimonial_rating" name="rating" required>
                                <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                <option value="3">⭐⭐⭐ (3 Stars)</option>
                                <option value="2">⭐⭐ (2 Stars)</option>
                                <option value="1">⭐ (1 Star)</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="testimonial_is_active" class="form-label">Status</label>
                            <select class="form-select" id="testimonial_is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="testimonial_comment" class="form-label">Comment/Testimonial *</label>
                            <textarea class="form-control" id="testimonial_comment" name="comment" rows="4" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let testimonials = [];

        // Load testimonials on tab shown
        document.getElementById('testimonial-tab').addEventListener('shown.bs.tab', function() {
            loadTestimonials();
        });

        // Load testimonials
        function loadTestimonials() {
            fetch('{{ route('admin.settings.testimonials.index') }}')
                .then(response => response.json())
                .then(data => {
                    testimonials = data;
                    renderTestimonialsTable();
                });
        }

        // Render table
        function renderTestimonialsTable() {
            const tbody = document.querySelector('#testimonialTable tbody');
            tbody.innerHTML = '';

            testimonials.forEach((testimonial, index) => {
                const stars = '⭐'.repeat(testimonial.rating);
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>
                <img src="${testimonial.photo ? '/storage/' + testimonial.photo : '/images/default-avatar.png'}" 
                     alt="${testimonial.name}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            <td>${testimonial.name}</td>
            <td>${testimonial.role || '-'}</td>
            <td>${stars}</td>
            <td>
                <span class="badge bg-${testimonial.is_active ? 'success' : 'secondary'}">
                    ${testimonial.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-secondary" onclick="moveTestimonial(${testimonial.id}, 'up')" ${index === 0 ? 'disabled' : ''}>
                    <i class="bi bi-arrow-up"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="moveTestimonial(${testimonial.id}, 'down')" ${index === testimonials.length - 1 ? 'disabled' : ''}>
                    <i class="bi bi-arrow-down"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editTestimonial(${testimonial.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteTestimonial(${testimonial.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
                tbody.appendChild(tr);
            });
        }

        // Reset form
        function resetTestimonialForm() {
            document.getElementById('testimonialForm').reset();
            document.getElementById('testimonial_id').value = '';
            document.getElementById('testimonial_method').value = 'POST';
            document.getElementById('testimonialModalTitle').textContent = 'Add Testimonial';
            document.getElementById('testimonial_photo_preview').classList.add('d-none');
        }

        // Edit testimonial
        function editTestimonial(id) {
            fetch(`{{ url('admin/settings/testimonials') }}/${id}`)
                .then(response => response.json())
                .then(testimonial => {
                    document.getElementById('testimonial_id').value = testimonial.id;
                    document.getElementById('testimonial_method').value = 'POST';
                    document.getElementById('testimonial_name').value = testimonial.name;
                    document.getElementById('testimonial_role').value = testimonial.role || '';
                    document.getElementById('testimonial_rating').value = testimonial.rating;
                    document.getElementById('testimonial_comment').value = testimonial.comment;
                    document.getElementById('testimonial_is_active').value = testimonial.is_active ? '1' : '0';
                    document.getElementById('testimonialModalTitle').textContent = 'Edit Testimonial';

                    if (testimonial.photo) {
                        document.getElementById('testimonial_photo_preview').src = '/storage/' + testimonial.photo;
                        document.getElementById('testimonial_photo_preview').classList.remove('d-none');
                    }

                    new bootstrap.Modal(document.getElementById('testimonialModal')).show();
                });
        }

        // Submit form
        document.getElementById('testimonialForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const testimonialId = document.getElementById('testimonial_id').value;
            const url = testimonialId ? `{{ url('admin/settings/testimonials') }}/${testimonialId}` :
                '{{ route('admin.settings.testimonials.store') }}';

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('testimonialModal')).hide();
                        loadTestimonials();
                        showTestimonialAlert('success', data.message);
                    }
                })
                .catch(error => {
                    showTestimonialAlert('danger', 'An error occurred');
                });
        });

        // Delete testimonial
        function deleteTestimonial(id) {
            if (confirm('Are you sure you want to delete this testimonial?')) {
                fetch(`{{ url('admin/settings/testimonials') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadTestimonials();
                            showTestimonialAlert('success', data.message);
                        }
                    });
            }
        }

        // Move testimonial
        function moveTestimonial(id, direction) {
            fetch(`{{ url('admin/settings/testimonials') }}/${id}/move`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        direction: direction
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadTestimonials();
                    }
                });
        }

        // Photo preview
        document.getElementById('testimonial_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('testimonial_photo_preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Show alert
        function showTestimonialAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            const container = document.querySelector('.container-fluid');
            const card = container.querySelector('.card');
            container.insertBefore(alertDiv, card);

            setTimeout(() => alertDiv.remove(), 3000);
        }
    </script>
@endpush
