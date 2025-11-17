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
            @forelse($bankAccounts as $bank)
                <tr>
                    <td>
                        @if ($bank->bank_logo)
                            <img src="{{ asset('storage/' . $bank->bank_logo) }}" alt="{{ $bank->bank_name }}"
                                class="rounded" style="width: 50px; height: 50px; object-fit: contain; cursor: pointer;"
                                onclick="viewImage('{{ asset('storage/' . $bank->bank_logo) }}', '{{ $bank->bank_name }} Logo')">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-bank text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($bank->qris_image)
                            <img src="{{ asset('storage/' . $bank->qris_image) }}" alt="QRIS {{ $bank->bank_name }}"
                                class="rounded" style="width: 50px; height: 50px; object-fit: contain; cursor: pointer;"
                                onclick="viewImage('{{ asset('storage/' . $bank->qris_image) }}', 'QRIS {{ $bank->bank_name }}')">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-qr-code text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $bank->bank_name }}</strong></td>
                    <td>
                        <span class="font-monospace">{{ $bank->account_number }}</span>
                        <button class="btn btn-sm btn-link p-0 ms-1"
                            onclick="copyToClipboard('{{ $bank->account_number }}')" title="Copy account number">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </td>
                    <td>{{ $bank->account_holder }}</td>
                    <td>
                        <span class="badge bg-{{ $bank->is_active ? 'success' : 'secondary' }}">
                            {{ $bank->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editBank({{ $bank->id }})">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <form action="{{ route('admin.settings.bank-accounts.destroy', $bank) }}" method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this bank account?')">
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
                        No bank accounts found. Click "Add Bank Account" to get started.
                    </td>
                </tr>
            @endforelse
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
            <form id="bankForm" action="{{ route('admin.settings.bank-accounts.store') }}" method="POST"
                enctype="multipart/form-data">
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
                        <input type="file" class="form-control" id="qris_image" name="qris_image"
                            accept="image/*">
                        <small class="text-muted">400x400 px, Max 1MB</small>
                        <div class="mt-2">
                            <img src="#" alt="Preview" id="qris_image_preview" class="image-preview d-none"
                                style="max-width: 150px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bank_name" class="form-label">Bank Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" required
                            placeholder="e.g., BCA, Mandiri, BNI">
                    </div>

                    <div class="mb-3">
                        <label for="account_number" class="form-label">Account Number <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_number" name="account_number"
                            required placeholder="1234567890">
                    </div>

                    <div class="mb-3">
                        <label for="account_holder" class="form-label">Account Holder <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="account_holder" name="account_holder"
                            required placeholder="PT. Company Name">
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

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageViewerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewerTitle">Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="#" alt="Full Image" id="fullImagePreview" class="img-fluid"
                    style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Reset form when opening add modal
        function resetBankForm() {
            document.getElementById('bankForm').reset();
            document.getElementById('bankForm').action = "{{ route('admin.settings.bank-accounts.store') }}";
            document.getElementById('bank_id').value = '';
            document.getElementById('bank_method').value = 'POST';
            document.getElementById('bankModalTitle').textContent = 'Add Bank Account';
            document.getElementById('bank_logo_preview').classList.add('d-none');
            document.getElementById('qris_image_preview').classList.add('d-none');
        }

        // Edit bank - fetch data via AJAX
        function editBank(id) {
            fetch(`{{ url('admin/settings/bank-accounts') }}/${id}`)
                .then(response => response.json())
                .then(bank => {
                    // Update form action for update
                    document.getElementById('bankForm').action = `{{ url('admin/settings/bank-accounts') }}/${id}`;
                    document.getElementById('bank_id').value = bank.id;
                    document.getElementById('bank_method').value = 'PUT';

                    // Fill form fields
                    document.getElementById('bank_name').value = bank.bank_name;
                    document.getElementById('account_number').value = bank.account_number;
                    document.getElementById('account_holder').value = bank.account_holder;
                    document.getElementById('bank_is_active').value = bank.is_active ? '1' : '0';

                    // Update modal title
                    document.getElementById('bankModalTitle').textContent = 'Edit Bank Account';

                    // Show existing bank logo preview
                    if (bank.bank_logo) {
                        const logoPreview = document.getElementById('bank_logo_preview');
                        logoPreview.src = '/storage/' + bank.bank_logo;
                        logoPreview.classList.remove('d-none');
                    }

                    // Show existing QRIS preview
                    if (bank.qris_image) {
                        const qrisPreview = document.getElementById('qris_image_preview');
                        qrisPreview.src = '/storage/' + bank.qris_image;
                        qrisPreview.classList.remove('d-none');
                    }

                    // Show modal
                    new bootstrap.Modal(document.getElementById('bankModal')).show();
                })
                .catch(error => {
                    alert('Error loading bank account data');
                    console.error(error);
                });
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
            } else {
                preview.classList.add('d-none');
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
            } else {
                preview.classList.add('d-none');
            }
        });

        // View full image
        function viewImage(imageSrc, title) {
            document.getElementById('fullImagePreview').src = imageSrc;
            document.getElementById('imageViewerTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imageViewerModal')).show();
        }

        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show temporary tooltip or alert
                const tooltip = document.createElement('div');
                tooltip.className =
                    'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                tooltip.style.zIndex = '9999';
                tooltip.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>Account number copied to clipboard!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(tooltip);

                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(tooltip);
                    bsAlert.close();
                }, 2000);
            }).catch(function(error) {
                console.error('Could not copy text: ', error);
                alert('Failed to copy account number');
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
