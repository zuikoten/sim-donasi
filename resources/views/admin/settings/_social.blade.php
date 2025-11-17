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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#socialModal"
        onclick="resetSocialForm()">
        <i class="bi bi-plus-circle me-2"></i>Add Social Media
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover" id="socialTable">
        <thead class="table-light">
            <tr>
                <th width="80">Icon</th>
                <th>Platform</th>
                <th>URL</th>
                <th width="80">Status</th>
                <th width="100">Order</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($socialMedia as $index => $social)
                <tr>
                    <td>
                        <div class="d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background: #f8f9fa; border-radius: 8px;">
                            <i class="{{ $social->icon_class }} fs-4"></i>
                        </div>
                    </td>
                    <td>
                        <strong>{{ $social->platform_name }}</strong>
                        <br>
                        <small class="text-muted">{{ $social->icon_class }}</small>
                    </td>
                    <td>
                        <a href="{{ $social->url }}" target="_blank" class="text-decoration-none">
                            {{ Str::limit($social->url, 50) }}
                            <i class="bi bi-box-arrow-up-right ms-1"></i>
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-{{ $social->is_active ? 'success' : 'secondary' }}">
                            {{ $social->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.settings.social-media.move', $social) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.social-media.move', $social) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === $socialMedia->count() - 1 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editSocial({{ $social->id }})">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.settings.social-media.destroy', $social) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this social media account?')">
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
                    <td colspan="6" class="text-center text-muted py-4">
                        No social media accounts found. Click "Add Social Media" to get started.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Social Media Modal -->
<div class="modal fade" id="socialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="socialModalTitle">Add Social Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="socialForm" action="{{ route('admin.settings.social-media.store') }}" method="POST">
                @csrf
                <input type="hidden" id="social_id" name="social_id">
                <input type="hidden" id="social_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="platform_name" class="form-label">Platform Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="platform_name" name="platform_name" required
                            placeholder="e.g., Facebook, Instagram">
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="url" name="url" required
                            placeholder="https://...">
                        <small class="text-muted">Full URL including https://</small>
                    </div>

                    <div class="mb-3">
                        <label for="icon_class" class="form-label">
                            Icon <span class="text-danger">*</span>
                        </label>

                        <select class="form-select" id="icon_select" onchange="updateIconClass()">
                            <option value="">-- Select Popular Icon --</option>
                            @foreach (\App\Models\SocialMedia::getPopularIcons() as $iconClass => $iconName)
                                <option value="{{ $iconClass }}">{{ $iconName }}</option>
                            @endforeach
                            <option value="custom">✏️ Custom (Type Manually)</option>
                        </select>

                        <input type="text" class="form-control mt-2" id="icon_class" name="icon_class" required
                            placeholder="e.g., bi bi-facebook">

                        <small class="text-muted d-block mt-1">
                            Use Bootstrap Icons classes.
                            <a href="https://icons.getbootstrap.com/" target="_blank">Browse icons <i
                                    class="bi bi-box-arrow-up-right"></i></a>
                        </small>

                        <!-- Icon Preview -->
                        <div class="mt-3 p-3 bg-light rounded text-center" id="icon_preview_container"
                            style="display: none;">
                            <div class="mb-2">
                                <strong>Preview:</strong>
                            </div>
                            <div class="d-inline-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <i id="icon_preview" class="bi bi-link-45deg" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="social_is_active" class="form-label">Status</label>
                        <select class="form-select" id="social_is_active" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
        // Reset form when opening add modal
        function resetSocialForm() {
            document.getElementById('socialForm').reset();
            document.getElementById('socialForm').action = "{{ route('admin.settings.social-media.store') }}";
            document.getElementById('social_id').value = '';
            document.getElementById('social_method').value = 'POST';
            document.getElementById('socialModalTitle').textContent = 'Add Social Media';
            document.getElementById('icon_select').value = '';
            document.getElementById('icon_class').value = '';
            document.getElementById('icon_preview_container').style.display = 'none';
        }

        // Update icon class from dropdown
        function updateIconClass() {
            const select = document.getElementById('icon_select');
            const input = document.getElementById('icon_class');

            if (select.value === 'custom') {
                input.value = '';
                input.focus();
            } else if (select.value !== '') {
                input.value = select.value;
            }

            updateIconPreview();
        }

        // Update icon preview
        function updateIconPreview() {
            const iconClass = document.getElementById('icon_class').value.trim();
            const preview = document.getElementById('icon_preview');
            const container = document.getElementById('icon_preview_container');

            if (iconClass) {
                preview.className = iconClass;
                preview.style.fontSize = '2rem';
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        // Listen to manual input changes
        document.getElementById('icon_class').addEventListener('input', function() {
            // Reset dropdown if manual input
            const select = document.getElementById('icon_select');
            if (this.value !== select.value) {
                select.value = 'custom';
            }
            updateIconPreview();
        });

        // Edit social media - fetch data via AJAX
        function editSocial(id) {
            fetch(`{{ url('admin/settings/social-media') }}/${id}`)
                .then(response => response.json())
                .then(social => {
                    // Update form action for update
                    document.getElementById('socialForm').action = `{{ url('admin/settings/social-media') }}/${id}`;
                    document.getElementById('social_id').value = social.id;
                    document.getElementById('social_method').value = 'PUT';

                    // Fill form fields
                    document.getElementById('platform_name').value = social.platform_name;
                    document.getElementById('url').value = social.url;
                    document.getElementById('icon_class').value = social.icon_class;
                    document.getElementById('social_is_active').value = social.is_active ? '1' : '0';

                    // Update modal title
                    document.getElementById('socialModalTitle').textContent = 'Edit Social Media';

                    // Check if icon is in popular list
                    const select = document.getElementById('icon_select');
                    const option = Array.from(select.options).find(opt => opt.value === social.icon_class);

                    if (option) {
                        select.value = social.icon_class;
                    } else {
                        select.value = 'custom';
                    }

                    // Update preview
                    updateIconPreview();

                    // Show modal
                    new bootstrap.Modal(document.getElementById('socialModal')).show();
                })
                .catch(error => {
                    alert('Error loading social media data');
                    console.error(error);
                });
        }

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
