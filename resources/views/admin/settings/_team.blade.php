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
            <!-- Will be populated by JavaScript -->
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
            <form id="teamForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="team_id" name="team_id">
                <input type="hidden" id="team_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="team_photo" class="form-label">Photo *</label>
                            <input type="file" class="form-control" id="team_photo" name="photo" accept="image/*">
                            <small class="text-muted">400x400 px, Max 2MB</small>
                            <div class="mt-2">
                                <img src="#" alt="Preview" class="image-preview d-none" id="team_photo_preview">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="team_name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="team_position" class="form-label">Position *</label>
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
        let teams = [];

        // Load teams on tab shown
        document.getElementById('team-tab').addEventListener('shown.bs.tab', function() {
            loadTeams();
        });

        // Load teams
        function loadTeams() {
            fetch('{{ route('admin.settings.teams.index') }}')
                .then(response => response.json())
                .then(data => {
                    teams = data;
                    renderTeamsTable();
                });
        }

        // Render table
        function renderTeamsTable() {
            const tbody = document.querySelector('#teamTable tbody');
            tbody.innerHTML = '';

            teams.forEach((team, index) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>
                <img src="${team.photo ? '/storage/' + team.photo : '/images/default-avatar.png'}" 
                     alt="${team.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            <td>${team.name}</td>
            <td>${team.position}</td>
            <td>
                <span class="badge bg-${team.is_active ? 'success' : 'secondary'}">
                    ${team.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-secondary" onclick="moveTeam(${team.id}, 'up')" ${index === 0 ? 'disabled' : ''}>
                    <i class="bi bi-arrow-up"></i>
                </button>
                <button class="btn btn-sm btn-outline-secondary" onclick="moveTeam(${team.id}, 'down')" ${index === teams.length - 1 ? 'disabled' : ''}>
                    <i class="bi bi-arrow-down"></i>
                </button>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editTeam(${team.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteTeam(${team.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
                tbody.appendChild(tr);
            });
        }

        // Reset form
        function resetTeamForm() {
            document.getElementById('teamForm').reset();
            document.getElementById('team_id').value = '';
            document.getElementById('team_method').value = 'POST';
            document.getElementById('teamModalTitle').textContent = 'Add Team Member';
            document.getElementById('team_photo_preview').classList.add('d-none');
            document.getElementById('team_photo').required = true;
        }

        // Edit team
        function editTeam(id) {
            fetch(`{{ url('admin/settings/teams') }}/${id}`)
                .then(response => response.json())
                .then(team => {
                    document.getElementById('team_id').value = team.id;
                    document.getElementById('team_method').value = 'POST';
                    document.getElementById('team_name').value = team.name;
                    document.getElementById('team_position').value = team.position;
                    document.getElementById('team_description').value = team.description || '';
                    document.getElementById('team_is_active').value = team.is_active ? '1' : '0';
                    document.getElementById('teamModalTitle').textContent = 'Edit Team Member';
                    document.getElementById('team_photo').required = false;

                    if (team.photo) {
                        document.getElementById('team_photo_preview').src = '/storage/' + team.photo;
                        document.getElementById('team_photo_preview').classList.remove('d-none');
                    }

                    new bootstrap.Modal(document.getElementById('teamModal')).show();
                });
        }

        // Submit form
        document.getElementById('teamForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const teamId = document.getElementById('team_id').value;
            const url = teamId ? `{{ url('admin/settings/teams') }}/${teamId}` :
                '{{ route('admin.settings.teams.store') }}';

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
                        bootstrap.Modal.getInstance(document.getElementById('teamModal')).hide();
                        loadTeams();
                        showAlert('success', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'An error occurred');
                });
        });

        // Delete team
        function deleteTeam(id) {
            if (confirm('Are you sure you want to delete this team member?')) {
                fetch(`{{ url('admin/settings/teams') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadTeams();
                            showAlert('success', data.message);
                        }
                    });
            }
        }

        // Move team
        function moveTeam(id, direction) {
            fetch(`{{ url('admin/settings/teams') }}/${id}/move`, {
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
                        loadTeams();
                    }
                });
        }

        // Photo preview
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
            }
        });

        // Show alert
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.card'));

            setTimeout(() => alertDiv.remove(), 3000);
        }
    </script>
@endpush
