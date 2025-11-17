{{-- Display success message --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Display validation errors --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

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
            @forelse($testimonials as $index => $testimonial)
                <tr>
                    <td>
                        <img src="{{ $testimonial->photo ? asset('storage/' . $testimonial->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $testimonial->name }}" class="rounded-circle"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td>{{ $testimonial->name }}</td>
                    <td>{{ $testimonial->role ?? '-' }}</td>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $testimonial->rating)
                                <span class="text-warning">⭐</span>
                            @else
                                <span class="text-muted">☆</span>
                            @endif
                        @endfor
                    </td>
                    <td>
                        <span class="badge bg-{{ $testimonial->is_active ? 'success' : 'secondary' }}">
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.settings.testimonials.move', $testimonial) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.testimonials.move', $testimonial) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === $testimonials->count() - 1 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewTestimonial({{ $testimonial->id }})"
                            title="View Comment">
                            <i class="bi bi-eye"></i>
                        </button>

                        <button class="btn btn-sm btn-warning" onclick="editTestimonial({{ $testimonial->id }})">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.settings.testimonials.destroy', $testimonial) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        No testimonials found. Click "Add Testimonial" to get started.
                    </td>
                </tr>
            @endforelse
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
            <form id="testimonialForm" action="{{ route('admin.settings.testimonials.store') }}" method="POST"
                enctype="multipart/form-data">
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
                            <label for="testimonial_name" class="form-label">Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="testimonial_name" name="name"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="testimonial_role" class="form-label">Role</label>
                            <select class="form-select" id="testimonial_role" name="role">
                                <option value="Donatur" selected>Donatur</option>
                                <option value="Wakif">Wakif</option>
                                <option value="Penerima Manfaat">Penerima Manfaat</option>
                                <option value="Pembuat Program">Pembuat Program</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="testimonial_rating" class="form-label">Rating <span
                                    class="text-danger">*</span></label>
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
                            <label for="testimonial_comment" class="form-label">Comment/Testimonial <span
                                    class="text-danger">*</span></label>
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

<!-- View Comment Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Testimonial Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="#" alt="Photo" class="rounded-circle mb-2" id="view_photo"
                        style="width: 80px; height: 80px; object-fit: cover;">
                    <h6 class="mb-0" id="view_name"></h6>
                    <small class="text-muted" id="view_role"></small>
                    <div class="mt-2" id="view_rating"></div>
                </div>
                <div class="border-top pt-3">
                    <p class="mb-0" id="view_comment"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Reset form when opening add modal
        function resetTestimonialForm() {
            document.getElementById('testimonialForm').reset();
            document.getElementById('testimonialForm').action = "{{ route('admin.settings.testimonials.store') }}";
            document.getElementById('testimonial_id').value = '';
            document.getElementById('testimonial_method').value = 'POST';
            document.getElementById('testimonialModalTitle').textContent = 'Add Testimonial';
            document.getElementById('testimonial_photo_preview').classList.add('d-none');
        }

        // Edit testimonial - fetch data via AJAX
        function editTestimonial(id) {
            fetch(`{{ url('admin/settings/testimonials') }}/${id}`)
                .then(response => response.json())
                .then(testimonial => {
                    // Update form action for update
                    document.getElementById('testimonialForm').action =
                        `{{ url('admin/settings/testimonials') }}/${id}`;
                    document.getElementById('testimonial_id').value = testimonial.id;
                    document.getElementById('testimonial_method').value = 'PUT';

                    // Fill form fields
                    document.getElementById('testimonial_name').value = testimonial.name;
                    document.getElementById('testimonial_role').value = testimonial.role || 'Donatur';
                    document.getElementById('testimonial_rating').value = testimonial.rating;
                    document.getElementById('testimonial_comment').value = testimonial.comment;
                    document.getElementById('testimonial_is_active').value = testimonial.is_active ? '1' : '0';

                    // Update modal title
                    document.getElementById('testimonialModalTitle').textContent = 'Edit Testimonial';

                    // Show existing photo preview
                    if (testimonial.photo) {
                        const preview = document.getElementById('testimonial_photo_preview');
                        preview.src = '/storage/' + testimonial.photo;
                        preview.classList.remove('d-none');
                    }

                    // Show modal
                    new bootstrap.Modal(document.getElementById('testimonialModal')).show();
                })
                .catch(error => {
                    alert('Error loading testimonial data');
                    console.error(error);
                });
        }

        // View testimonial comment
        function viewTestimonial(id) {
            fetch(`{{ url('admin/settings/testimonials') }}/${id}`)
                .then(response => response.json())
                .then(testimonial => {
                    // Set photo
                    const photoUrl = testimonial.photo ? '/storage/' + testimonial.photo : '/images/default-avatar.png';
                    document.getElementById('view_photo').src = photoUrl;

                    // Set name and role
                    document.getElementById('view_name').textContent = testimonial.name;
                    document.getElementById('view_role').textContent = testimonial.role || '-';

                    // Set rating stars
                    let stars = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= testimonial.rating) {
                            stars += '<span class="text-warning">⭐</span>';
                        } else {
                            stars += '<span class="text-muted">☆</span>';
                        }
                    }
                    document.getElementById('view_rating').innerHTML = stars;

                    // Set comment
                    document.getElementById('view_comment').textContent = testimonial.comment;

                    // Show modal
                    new bootstrap.Modal(document.getElementById('viewCommentModal')).show();
                })
                .catch(error => {
                    alert('Error loading testimonial data');
                    console.error(error);
                });
        }

        // Photo preview when file is selected
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
            } else {
                preview.classList.add('d-none');
            }
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endpush
