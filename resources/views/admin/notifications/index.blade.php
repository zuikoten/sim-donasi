@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Notifikasi Saya</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- Tombol MARK All READ -->
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-check2-all"></i> Tandai Semua Telah Dibaca
                </button>
            </form>
            <!-- Tombol DELETE All READ -->
            @if (auth()->user()->notifications()->where('status_baca', true)->exists())
                <form action="{{ route('notifications.deleteAllRead') }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi yang sudah dibaca?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash3"></i> Hapus Semua yang Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse(auth()->user()->notifications()->orderBy('created_at', 'desc')->get() as $notification)
                <div
                    class="list-group-item p-3 mb-2 @if (!$notification->status_baca) border-start border-4 border-primary @endif">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 {{ !$notification->status_baca ? 'fw-bold' : '' }}">
                            {{ $notification->judul }}
                        </h6>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-2">{{ $notification->isi }}</p>
                    <div class="d-flex justify-content-end">
                        @if (!$notification->status_baca)
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-check2"></i> Tandai Dibaca
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell display-1 text-muted"></i>
                    <p class="text-muted mt-3">Anda tidak memiliki notifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
