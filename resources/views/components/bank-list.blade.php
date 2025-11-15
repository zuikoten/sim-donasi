{{-- resources/views/components/bank-list.blade.php --}}
<div class="list-group">
    @foreach ($bankAccounts as $bank)
        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal"
            data-bs-target="#bankModal{{ $bank->id }}">
            <div class="d-flex align-items-center">
                <img src="{{ $bank->bank_logo_url }}" width="35" class="me-3">
                <div>
                    <strong>{{ $bank->bank_name }}</strong><br>
                    <small>{{ $bank->account_number }} — {{ $bank->account_holder }}</small>
                </div>
            </div>
        </a>

        <!-- Modal for QRIS -->
        <div class="modal fade" id="bankModal{{ $bank->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">{{ $bank->bank_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <p><strong>{{ $bank->account_number }}</strong><br>
                            a.n {{ $bank->account_holder }}</p>

                        @if ($bank->qris_image)
                            <img src="{{ Storage::url($bank->qris_image) }}" class="img-fluid rounded shadow"
                                style="max-width: 300px;">
                            <p class="small text-muted mt-2">Scan QRIS untuk donasi</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
