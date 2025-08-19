@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-">Dashboard</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>

    {{-- Start Jumlah Data Pengaduan --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Aduan</h5>
                    <h2 class="card-text">{{ $totalComplaints ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Aduan Baru</h5>
                    <h2 class="card-text">{{ $complaintBaru ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Aduan Diproses</h5>
                    <h2 class="card-text">{{ $complaintDiproses ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title">Aduan Selesai</h5>
                    <h2 class="card-text">{{ $complaintSelesai ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    {{-- End Jumlah Data Pengaduan --}}

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-amber-50">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: bold">Pengaduan Diajukan</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="flex-fill text-center">Nama</div>
                        <div class="flex-fill text-center">Judul Aduan</div>
                        <div class="flex-fill text-center">Tanggal</div>
                        <div class="flex-fill text-center">Status</div>
                    </div>
                    @foreach ($resident->complaints as $complaint)
                        {{-- @php
                            $complaint = $resident->complaints->first();
                        @endphp --}}
                        <div class="d-flex py-2 border-bottom">
                            <div class="flex-fill text-center">{{ $resident->name ?? '-' }}</div>
                            <div class="flex-fill text-center">{{ $complaint->title ?? '-' }}</div>
                            <div class="flex-fill text-center">
                                {{ $complaint->created_at ? $complaint->created_at->format('d M Y') : '-' }}</div>
                            <div class="flex-fill text-center">
                                @php
                                    $badgeClass = match ($complaint->status_label) {
                                        'Baru' => 'bg-info',
                                        'Sedang Diproses' => 'bg-warning',
                                        'Selesai' => 'bg-success',
                                        default => 'Tidak Diketahui',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}" style="color: white">{{ $complaint->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: bold">Permintaan Akun Terbaru</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="flex-fill text-center">Email</div>
                        <div class="flex-fill text-center">Tanggal Masuk</div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
