@extends('layouts.app')

@section('title', 'Manajemen Penerima Manfaat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Penerima Manfaat</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('beneficiaries.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Penerima
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Dropdown untuk memilih jumlah data per halaman -->
        <div class="col-md-3">
            <label for="perPage" class="form-label">Tampilkan</label>
            <select id="perPage" class="form-select form-select-sm">
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                <option value="999999" {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <!-- Akhir Dropdown -->
        @if($beneficiaries->count() > 0)
            <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                <table class="table table-striped table-hover table-sticky-header">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beneficiaries as $key => $beneficiary)
                        <tr>
                            <td>{{ ($beneficiaries->currentPage() - 1) * $beneficiaries->perPage() + $key + 1 }}</td>
                            <td>{{ $beneficiary->nama }}</td>
                            <td>{{ Str::limit($beneficiary->alamat, 50) }}</td>
                            <td>{{ $beneficiary->nomor_telepon ?: '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('beneficiaries.show', $beneficiary->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('beneficiaries.edit', $beneficiary->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('beneficiaries.destroy', $beneficiary->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penerima manfaat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <p>Belum ada data penerima manfaat.</p>
                <a href="{{ route('beneficiaries.create') }}" class="btn btn-primary">Tambah Penerima Pertama</a>
            </div>
        @endif
    </div>
    <!-- Start Pagination -->
    <div class="card-footer d-flex justify-content-between align-items-center">
        <span class="text-muted">
            Menampilkan {{ $beneficiaries->firstItem() }} hingga {{ $beneficiaries->lastItem() }} dari {{ $beneficiaries->total() }} data.
        </span>
        {{ $beneficiaries->appends(request()->query())->links() }}

    </div>
    <!-- End Pagination -->
</div>
@endsection


@push('scripts')
<script>
   // Reload Tabel saat Paginasi berubah
    document.getElementById('perPage').addEventListener('change', function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('perPage', this.value); // ubah perPage
        window.location.href = currentUrl.toString(); // reload halaman dengan semua filter tetap
    });
</script>
@endpush