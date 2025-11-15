<div class="mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bankModal"
        onclick="resetBankForm()">
        <i class="bi bi-plus-circle me-2"></i>Add Bank Account
    </button>
</div>

<div class="table-responsive">
    <table class="table table-hover" id="bankTable">
        <thead class="table-light">
            <tr>
                <th width="80">Logo</th>
                <th width="80">QRIS</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Account Holder</th>
                <th width="80">Status</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Will be populated by JavaScript -->
        </tbody>
    </table>
</div>

<!-- Bank Account Modal -->
<div class="modal fade" id="bankModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankModalTitle">Add Bank Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bankForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="bank_id" name="bank_id">
                <input type="hidden" id="bank_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bank_logo" class="form-label">Bank Logo</label>
                        <input type="file" class="form-control" id="bank_logo" name="bank_logo" accept="image/*">
                        <small class="text-muted">120x120 px, Max 512KB</small>
                        <div class="mt-2">
                            <img src="#" alt="Preview" class="image-preview d-none" id="bank_logo_preview"
                                style="max-width: 120px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="qris_image" class="form-label">QRIS Image</label>
                        <input type="file" class="form-control" id="qris_image" name="qris_image" accept="image/*">
                        <small class="text-muted">400x400 px, Max 512KB</small>

                        <div class="mt-2">
                            <img src="#" alt="Preview" id="qris_image_preview" class="image-preview d-none"
                                style="max-width: 150px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name *</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" required
                            placeholder="e.g., BCA, Mandiri, BNI">
                    </div>

                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number *</label>
                        <input type="text" class="form-control" id="account_number" name="account_number" required
                            placeholder="1234567890">
                    </div>

                    <div class="mb-3">
                        <label for="account_holder" class="form-label">Account Holder *</label>
                        <input type="text" class="form-control" id="account_holder" name="account_holder" required
                            placeholder="PT. Company Name">
                    </div>

                    <div class="mb-3">
                        <label for="bank_is_active" class="form-label">Status</label>
                        <select class="form-select" id="bank_is_active" name="is_active">
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
        let banks = [];

        // Load banks on tab shown
        document.getElementById('bank-tab').addEventListener('shown.bs.tab', function() {
            loadBanks();
        });

        // Load banks
        function loadBanks() {
            fetch('{{ route('admin.settings.bank-accounts.index') }}')
                .then(response => response.json())
                .then(data => {
                    banks = data;
                    renderBanksTable();
                });
        }

        // Render table
        function renderBanksTable() {
            const tbody = document.querySelector('#bankTable tbody');
            tbody.innerHTML = '';

            if (banks.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No bank accounts added yet</td></tr>';
                return;
            }

            banks.forEach(bank => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
            <td>
                <img src="${bank.bank_logo ? '/storage/' + bank.bank_logo : '/images/default-bank.png'}" 
                     alt="${bank.bank_name}" class="rounded" style="width: 50px; height: 50px; object-fit: contain;">
            </td>
            <td>
                <img src="${bank.qris_image ? '/storage/' + bank.qris_image : '/images/default-qris.png'}"
                class="rounded" style="width: 50px; height: 50px; object-fit: contain;">
            </td>

            <td><strong>${bank.bank_name}</strong></td>
            <td>${bank.account_number}</td>
            <td>${bank.account_holder}</td>
            <td>
                <span class="badge bg-${bank.is_active ? 'success' : 'secondary'}">
                    ${bank.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" onclick="editBank(${bank.id})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteBank(${bank.id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
                tbody.appendChild(tr);
            });
        }

        // Reset form
        function resetBankForm() {
            document.getElementById('bankForm').reset();
            document.getElementById('bank_id').value = '';
            document.getElementById('bank_method').value = 'POST';
            document.getElementById('bankModalTitle').textContent = 'Add Bank Account';
            document.getElementById('bank_logo_preview').classList.add('d-none');
            document.getElementById('qris_image_preview').classList.add('d-none');

        }

        // Edit bank
        function editBank(id) {
            fetch(`{{ url('admin/settings/bank-accounts') }}/${id}`)
                .then(response => response.json())
                .then(bank => {
                    document.getElementById('bank_id').value = bank.id;
                    document.getElementById('bank_method').value = 'POST';
                    document.getElementById('bank_name').value = bank.bank_name;
                    document.getElementById('account_number').value = bank.account_number;
                    document.getElementById('account_holder').value = bank.account_holder;
                    document.getElementById('bank_is_active').value = bank.is_active ? '1' : '0';
                    document.getElementById('bankModalTitle').textContent = 'Edit Bank Account';

                    // Bank Logo preview
                    if (bank.bank_logo) {
                        document.getElementById('bank_logo_preview').src = '/storage/' + bank.bank_logo;
                        document.getElementById('bank_logo_preview').classList.remove('d-none');
                    }

                    // QRIS preview
                    if (bank.qris_image) {
                        document.getElementById('qris_image_preview').src = '/storage/' + bank.qris_image;
                        document.getElementById('qris_image_preview').classList.remove('d-none');
                    }

                    new bootstrap.Modal(document.getElementById('bankModal')).show();
                });
        }

        // Submit form
        document.getElementById('bankForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const bankId = document.getElementById('bank_id').value;
            const url = bankId ? `{{ url('admin/settings/bank-accounts') }}/${bankId}` :
                '{{ route('admin.settings.bank-accounts.store') }}';

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
                        bootstrap.Modal.getInstance(document.getElementById('bankModal')).hide();
                        loadBanks();
                        showBankAlert('success', data.message);
                    }
                })
                .catch(error => {
                    showBankAlert('danger', 'An error occurred');
                });
        });

        // Delete bank
        function deleteBank(id) {
            if (confirm('Are you sure you want to delete this bank account?')) {
                fetch(`{{ url('admin/settings/bank-accounts') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadBanks();
                            showBankAlert('success', data.message);
                        }
                    });
            }
        }

        // Bank Logo preview
        document.getElementById('bank_logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('bank_logo_preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // QRIS preview
        document.getElementById('qris_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('qris_image_preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.src = event.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Show alert
        function showBankAlert(type, message) {
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
