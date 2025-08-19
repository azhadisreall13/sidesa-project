@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Surat</h1>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <form action="/letter/update/{{ $letter->id }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Jenis Surat</label>
                            <input type="text" class="form-control" value="{{ $letter->type }}" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nama Pemohon</label>
                            <input type="text" class="form-control" value="{{ $letter->resident->name }}" disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Unggah File (PDF)</label>
                            <input type="file" name="file" class="form-control">
                            @if ($letter->file_path)
                                <small>File saat ini: <a href="/letter/{{ $letter->id }}/download" target="_blank">Lihat</a></small>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                @foreach ($statusOption as $value => $label)
                                    <option value="{{ $value }}" @selected($letter->status == $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="d-flex justify-content-end" style="gap: 10px">
                            <a href="/letter" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
