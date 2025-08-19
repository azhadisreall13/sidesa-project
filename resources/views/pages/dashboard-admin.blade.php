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

    {{-- Start Jumlah Penduduk dan Total Akun Terdaftar --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card bg-amber-50">
                <div class="card-body shadow">
                    <h5 class="card-title text-center" style="font-weight: bold">Jumlah Penduduk</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="d-flex">Total Penduduk: {{ $totalResident ?? 0 }}</div>
                    </div>
                    <div class="d-flex border-bottom pb-2">
                        <div class="d-flex">Laki-laki: {{ $maleCount ?? 0 }}</div>
                    </div>
                    <div class="d-flex border-bottom pb-2">
                        <div class="d-flex">Perempuan: {{ $femaleCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Start Total Akun Terdaftar --}}
        <div class="col-md-4">
            <div class="card bg-amber-50">
                <div class="card-body shadow">
                    <h5 class="card-title text-center" style="font-weight: bold">Total Akun Terdaftar</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="d-flex">{{ $totalAccount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Total AKun Terdaftar --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body shadow">
                    <h5 class="card-title" style="font-weight: bold">Permintaan Akun Terbaru</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="flex-fill text-center">Email</div>
                        <div class="flex-fill text-center">Tanggal Masuk</div>
                    </div>
                    @forelse ($latestRequestAccount as $request)
                        <div class="d-flex border-bottom py-2">
                            <div class="flex-fill text-center">{{ $request->email }}</div>
                            <div class="flex-fill text-center">{{ $request->created_at }}</div>
                        </div>
                    @empty
                        <div class="text-center pt-2">Tidak Ada Permintaan Akun Baru</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    {{-- End Jumlah Penduduk dan Total Akun Terdaftar --}}

    {{-- Pengaduan Terbaru --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-amber-50">
                <div class="card-body shadow">
                    <h5 class="card-title" style="font-weight: bold">Pengaduan Terbaru</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="flex-fill text-center">Nama</div>
                        <div class="flex-fill text-center">Judul Aduan</div>
                        <div class="flex-fill text-center">Tanggal</div>
                        <div class="flex-fill text-center">Status</div>
                    </div>
                    @foreach ($resident as $item)
                        @php
                            $complaint = $item->complaints->first();
                        @endphp
                        <div class="d-flex py-2 border-bottom">
                            <div class="flex-fill text-center">{{ $item->name ?? '-' }}</div>
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
                                <span class="badge {{ $badgeClass }}"
                                    style="color: white">{{ $complaint->status_label }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Start Surat Masuk Terbaru --}}
        <div class="col-md-6">
            <div class="card bg-amber-50">
                <div class="card-body shadow">
                    <h5 class="card-title text-center" style="font-weight: bold">Surat Masuk Terbaru</h5>
                    <div class="d-flex border-bottom pb-2">
                        <div class="flex-fill text-center">Nama</div>
                        <div class="flex-fill text-center">Jenis</div>
                        <div class="flex-fill text-center">Tanggal Pengajuan</div>
                        <div class="flex-fill text-center">Status</div>
                    </div>
                    @foreach ($resident as $item)
                        @php
                            $letter = $item->incomingLetters->first();
                        @endphp
                        <div class="d-flex bottom-border py2">
                            <div class="flex-fill text-center">{{ $item->name ?? '-' }}</div>
                            <div class="flex-fill text-center">{{ $letter->type ?? '-' }}</div>
                            <div class="flex-fill text-center">
                                {{ $letter?->created_at?->format('d M Y') ?? '-' }}</div>
                            <div class="flex-fill text-center">
                                @if ($letter)
                                    @php
                                        $badgeClass = match ($letter->status_label) {
                                            'Terkirim' => 'bg-info',
                                            'Sedang Diproses' => 'bg-warning',
                                            'Selesai' => 'bg-success',
                                            default => 'Tidak Diketahui',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}"
                                        style="color: white">{{ $letter->status_label ?? '-' }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary" style="color: white"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- End Total AKun Terdaftar --}}
    </div>
    {{-- End Pengaduan Terbaru --}}
@endsection
