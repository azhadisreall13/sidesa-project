@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Pengaduan</h1>
        @if (auth()->user()->role_id == 2)
            <a href="/complaint/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm p-2"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Tambah Aduan</a>
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
                                        <th>Nama Penduduk</th>
                                    @endif
                                    <th>Judul</th>
                                    <th>Isi Aduan</th>
                                    <th>Bukti Foto</th>
                                    <th>Status</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            {{-- @if (count($complaint) < 1)
                                <tbody>
                                    <tr>
                                        <td colspan="11">
                                            <p class="pt-3 text-center">Tidak ada data</p>
                                        </td>
                                    </tr>
                                </tbody>
                            @else --}}
                            <tbody>
                                @forelse ($complaint as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + $complaint->firstItem() - 1 }}</td>
                                        @if (auth()->user()->role_id == 1)
                                            <td>{{ $item->resident->name }}</td>
                                        @endif
                                        <td>{{ $item->title }}</td>
                                        <td>{!! wordwrap($item->description, 50, "<br>\n") !!}</td>
                                        <td>
                                            @php
                                                $filePath = 'storage/' . $item->images;
                                            @endphp
                                            @if (isset($item->images))
                                                <a href="{{ $filePath }}">
                                                    <img src="{{ $filePath }}" alt="Bukti Foto"
                                                        style="max-width: 300px">
                                                </a>
                                            @else
                                                Tidak ada
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match ($item->status_label) {
                                                    'Baru' => 'badge-info',
                                                    'Sedang Diproses' => 'badge-warning',
                                                    'Selesai' => 'badge-success',
                                                    default => 'badge-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}"
                                                style="color: white">{{ $item->status_label }}</span>
                                        </td>
                                        <td>{{ $item->report_date_label }}</td>
                                        <td>
                                            @if (auth()->user()->role_id == 2 && isset(auth()->user()->resident) && $item->status == 'new')
                                                <div class="d-flex align-items-center" style="gap: 10px;">
                                                    <a href="{{ url('/complaint/' . $item->id . '/edit') }}"
                                                        class="d-inline-block btn btn-sm btn-warning">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button" class="btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#confirmationDelete-{{ $item->id }}">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </div>
                                            @elseif (auth()->user()->role_id == 1)
                                                <div>
                                                    <form id="formChangeStatus-{{ $item->id }}"
                                                        action="/complaint/update-status/{{ $item->id }}"
                                                        method="post"
                                                        oninput="document.getElementById('formChangeStatus-{{ $item->id }}').submit()">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="form-group">
                                                            <select name="status" id="status" class="form-control"
                                                                style="min-width: 170px">
                                                                @foreach ([
                                                                    (object)
                                                            [
                                                                        'label' => 'Baru',
                                                                        'value' => 'new',
                                                                    ],
                                                                    (object) [
                                                                        'label' => 'Sedang Diproses',
                                                                        'value' => 'processing',
                                                                    ],
                                                                    (object) [
                                                                        'label' => 'Selesai',
                                                                        'value' => 'completed',
                                                                    ],
                                                                ] as $status)
                                                                    <option value="{{ $status->value }}"
                                                                        @selected($item->status == $status->value)>
                                                                        {{ $status->label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @include('pages.complaint.confirmation-delete')
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            {{-- @endif --}}
                        </table>
                        {{ $complaint->links() }}
                    </div>
                </div>
                {{-- @if ($complaint->lastPage() > 1)
                    <div class="card-footer">
                        {{ $complaint->links('pagination::bootstrap-5') }}
                    </div>
                @endif --}}
            </div>
        </div>
    </div>
@endsection
