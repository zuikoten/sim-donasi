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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamModal"
        onclick="resetTeamForm()">
        <i class="bi bi-plus-circle me-2"></i>Add Team Member
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover" id="teamTable">
        <thead class="table-light">
            <tr>
                <th width="80">Photo</th>
                <th>Name</th>
                <th>Position</th>
                <th width="80">Status</th>
                <th width="100">Order</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teams as $index => $team)
                <tr>
                    <td>
                        <img src="{{ $team->photo ? asset('storage/' . $team->photo) : asset('images/default-avatar.png') }}"
                            alt="{{ $team->name }}" class="rounded"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->position }}</td>
                    <td>
                        <span class="badge bg-{{ $team->is_active ? 'success' : 'secondary' }}">
                            {{ $team->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('admin.settings.teams.move', $team) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-up"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.teams.move', $team) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                {{ $index === $teams->count() - 1 ? 'disabled' : '' }}>
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editTeam({{ $team->id }})">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.settings.teams.destroy', $team) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this team member?')">
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
                        No team members found. Click "Add Team Member" to get started.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamModalTitle">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="teamForm" action="{{ route('admin.settings.teams.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="team_id" name="team_id">
                <input type="hidden" id="team_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="team_photo" class="form-label">Photo <span class="text-danger"
                                    id="photo_required">*</span></label>
                            <input type="file" class="form-control" id="team_photo" name="photo" accept="image/*">
                            <small class="text-muted">400x400 px, Max 2MB</small>
                            <div class="mt-2">
                                <img src="#" alt="Preview" class="image-preview d-none"
                                    id="team_photo_preview" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="team_name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_position" class="form-label">Position <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="team_position" name="position" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_is_active" class="form-label">Status</label>
                            <select class="form-select" id="team_is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="team_description" class="form-label">Description</label>
                            <textarea class="form-control" id="team_description" name="description" rows="3"></textarea>
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
        // Reset form when opening add modal
        function resetTeamForm() {
            document.getElementById('teamForm').reset();
            document.getElementById('teamForm').action = "{{ route('admin.settings.teams.store') }}";
            document.getElementById('team_id').value = '';
            document.getElementById('team_method').value = 'POST';
            document.getElementById('teamModalTitle').textContent = 'Add Team Member';
            document.getElementById('team_photo_preview').classList.add('d-none');
            document.getElementById('team_photo').required = true;
            document.getElementById('photo_required').style.display = 'inline';
        }

        // Edit team - fetch data via AJAX
        function editTeam(id) {
            fetch(`{{ url('admin/settings/teams') }}/${id}`)
                .then(response => response.json())
                .then(team => {
                    // Update form action for update
                    document.getElementById('teamForm').action = `{{ url('admin/settings/teams') }}/${id}`;
                    document.getElementById('team_id').value = team.id;
                    document.getElementById('team_method').value = 'PUT';

                    // Fill form fields
                    document.getElementById('team_name').value = team.name;
                    document.getElementById('team_position').value = team.position;
                    document.getElementById('team_description').value = team.description || '';
                    document.getElementById('team_is_active').value = team.is_active ? '1' : '0';

                    // Update modal title
                    document.getElementById('teamModalTitle').textContent = 'Edit Team Member';

                    // Photo is not required when editing
                    document.getElementById('team_photo').required = false;
                    document.getElementById('photo_required').style.display = 'none';

                    // Show existing photo preview
                    if (team.photo) {
                        const preview = document.getElementById('team_photo_preview');
                        preview.src = '/storage/' + team.photo;
                        preview.classList.remove('d-none');
                    }

                    // Show modal
                    new bootstrap.Modal(document.getElementById('teamModal')).show();
                })
                .catch(error => {
                    alert('Error loading team data');
                    console.error(error);
                });
        }

        // Photo preview when file is selected
        document.getElementById('team_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('team_photo_preview');

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
