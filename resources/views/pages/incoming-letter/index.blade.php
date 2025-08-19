@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pengajuan Surat</h1>
        @if (auth()->user()->role_id == 2)
            <a href="/letter/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm p-2"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Buat Surat</a>
        @endif
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session()->get('success') }}",
                icon: "success"
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                title: "Terjadi Kesalahan!",
                text: "{{ session()->get('error') }}",
                icon: "error"
            });
        </script>
    @endif

    {{-- Table --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hovered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    @if (auth()->user()->role_id == 1)
                                        <th>Nama Pemohon</th>
                                    @endif
                                    <th>Jenis Surat</th>
                                    <th>Waktu Pengajuan</th>
                                    <th>Status</th>
                                    <th>Unduh Surat</th>
                                    @if (auth()->user()->role_id == 1)
                                        <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            {{-- @if ($letter?->isEmpty())
                                <tbody>
                                    <tr>
                                        <td colspan="11">
                                            <p class="pt-3 text-center">Tidak ada data</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @else --}}
                                <tbody>
                                    @forelse ($letter as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + $letter->firstItem() - 1 }}</td>
                                            @if (auth()->user()->role_id == 1)
                                                <td>{{ $item->resident->name ?? '-' }}</td>
                                            @endif
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($item->status_label) {
                                                        'Terkirim' => 'badge-info',
                                                        'Sedang Diproses' => 'badge-warning',
                                                        'Selesai' => 'badge-success',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge 
                                            @if ($item->status == 'pending') badge-info
                                            @elseif ($item->status == 'processing') badge-warning
                                            @else badge-success @endif">
                                                    {{ $item->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->file_path)
                                                    <a href="/letter/{{ $item->id }}/download"
                                                        class="btn btn-outline-info">Unduh</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @if (auth()->user()->role_id == 1)
                                                <td>
                                                    <a href="/letter/{{ $item->id }}/edit" class="btn btn-warning">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            {{-- @endif --}}
                        </table>
                        {{ $letter->links() }}
                    </div>
                </div>
                {{-- @if ($letter->hasPages())
                    <div class="card-footer">
                        {{ $letter->links('pagination::bootstrap-5') }}
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
@endsection
