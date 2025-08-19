@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Penduduk</h1>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <form action="/resident" method="post">
                    @csrf
                    @method('POST')
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nik" >NIK (Nomor Induk Keluarga)</label>
                            <input type="number" inputmode="numeric" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}">
                            @error('nik')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="name" >Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" >Jenis Kelamin</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="" {{ old('gender') == '' ? 'selected' : '' }} disabled>--Pilih jenis kelamin</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="birth_date" >Tanggal Lahir</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date') }}">
                            @error('birth_date')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="birth_place" >Tempat Lahir</label>
                            <input type="text" name="birth_place" id="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place') }}">
                            @error('birth_place')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" >Alamat</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                            @error('address')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="religion" >Agama</label>
                            <input type="text" name="religion" id="religion" class="form-control @error('religion') is-invalid @enderror" value="{{ old('religion') }}">
                            @error('religion')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="marital_status" >Status Perkawinan</label>
                            <select name="marital_status" id="marital_status" class="form-control @error('marital_status') is-invalid @enderror">
                                <option value="" {{ old('marital_status') == '' ? 'selected' : '' }} disabled>--Pilih status perkawinan</option>
                                <option value="Belum Menikah" {{ old('marital_status') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Menikah" {{ old('marital_status') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai" {{ old('marital_status') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                                <option value="Duda/Janda" {{ old('marital_status') == 'Duda/Janda' ? 'selected' : '' }}>Janda/Duda</option>
                            </select>
                            @error('marital_status')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="occupation" >Pekerjaan</label>
                            <input type="text" name="occupation" id="occupation" class="form-control @error('occupation') is-invalid @enderror" value="{{ old('occupation') }}">
                            @error('occupation')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" >Nomor Telepon</label>
                            <input type="number" inputmode="numeric" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" >Status Penduduk</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status') }}">
                                <option value="Hidup">Hidup</option>
                                <option value="Pindah">Pindah</option>
                                <option value="Meninggal Dunia">Meninggal</option>
                            </select>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="d-flex justify-content-end" style="gap: 10px">
                            <a href="/resident" class="btn btn-outline-secondary">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection