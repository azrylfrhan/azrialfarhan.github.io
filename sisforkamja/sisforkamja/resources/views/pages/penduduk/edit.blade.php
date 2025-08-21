@extends('layout.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ubah Penduduk</h1>
    </div>
    <div class="card shadow border-left-primary p-4 mb-4">
        <form action="/penduduk/{{ $penduduks->id }}" method="POST">
            @csrf
            @method('PUT')
        <!-- 2 column grid layout with text inputs for the first and last names -->
        <div class="row mb-4">
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <input type="text" inputmode="numeric" name="nik" id="nik" class="form-control @error('nik')
                    is-invalid @enderror" value="{{ old('nik', $penduduks->nik) }}"/>
                    @error('nik')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                <label class="form-label" for="nik">NIK</label>
            </div>
            </div>
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <input type="text" name="nama" id="nama" class="form-control @error('nama')
                    is-invalid @enderror" value="{{ old('nama', $penduduks->nama) }}" />
                    @error('nama')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                <label class="form-label" for="nama">Nama</label>
            </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir')
                    is-invalid @enderror" value="{{ old('tempat_lahir', $penduduks->tempat_lahir) }}" />
                    @error('tempat_lahir')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
            </div>
            </div>
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir')
                    is-invalid @enderror" value="{{ old('tanggal_lahir', $penduduks->tanggal_lahir) }}" />
                    @error('tanggal_lahir')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
            </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control @error('jenis_kelamin')
                    is-invalid @enderror" value="{{ old('jenis_kelamin', $penduduks->jenis_kelamin) }}" >
                    @error('jenis_kelamin')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror">
                    <option value="pria">LAKI-LAKI</option>
                    <option value="wanita">PEREMPUAN</option>
                </select>
                <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
            </div>
            </div>
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                <select name="agama" id="agama" class="form-control @error('agama')
                    is-invalid @enderror" value="{{ old('agama', $penduduks->agama) }}" >
                    @error('agama')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    <option value="ISLAM">ISLAM</option>
                    <option value="KRISTEN">KRISTEN</option>
                    <option value="KATOLIK">KATOLIK</option>
                    <option value="BUDHA">BUDHA</option>
                    <option value="HINDU">HINDU</option>
                    <option value="LAINNYA">LAINNYA</option>
                </select>
                <label class="form-label" for="agama">Agama</label>
                </div>
            </div>
        </div>
        <!-- Text input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control @error('alamat')
                is-invalid @enderror"> {{ old('alamat', $penduduks->alamat) }} </textarea>
            <label class="form-label" for="alamat">Alamat</label>
        </div>
        <div class="row mb-4">
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <select name="status_perkawinan" id="status_perkawinan" class="form-control @error('status_perkawinan')
                    is-invalid @enderror" value="{{ old('status_perkawinan', $penduduks->status_perkawinan) }}" >
                    @error('status_perkawinan')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    <option value="single">BELUM KAWIN</option>
                    <option value="menikah">MENIKAH</option>
                    <option value="cerai">CERAI</option>
                    <option value="janda">JANDA</option>
                </select>
                <label class="form-label" for="status_perkawinan">Status Perkawinan</label>
            </div>
            </div>
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                <select name="pekerjaan" id="pekerjaan" class="form-control @error('pekerjaan')
                    is-invalid @enderror" value="{{ old('pekerjaan', $penduduks->pekerjaan) }}" >
                    @error('pekerjaan')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    <option value="TNI/POLRI">TNI/POLRI</option>
                    <option value="PNS">PNS</option>
                    <option value="SWASTA">SWASTA</option>
                    <option value="MHS/PELAJAR">MHS/PELAJAR</option>
                    <option value="BURUH/TANI">BURUH/TANI</option>
                    <option value="PEDAGANG">PEDAGANG</option>
                    <option value="LAINNYA">LAINNYA</option>
                </select>
                <label class="form-label" for="pekerjaan">Pekerjaan</label>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
            <div data-mdb-input-init class="form-outline">
                <input type="text" id="no_telepon" name="no_telepon" class="form-control" value="{{ old('no_telepon', $penduduks->no_telepon) }}"/>
                <label class="form-label" for="no_telepon">Nomor Telepon</label>
            </div>
            </div>
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                <select name="lingkungan" id="lingkungan" class="form-control @error('lingkungan')
                    is-invalid @enderror" value="{{ old('lingkungan', $penduduks->lingkungan) }}">
                    @error('lingkungan')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    <option value="lingkungan1">1</option>
                    <option value="lingkungan2">2</option>
                    <option value="lingkungan3">3</option>
                </select>
                <label class="form-label" for="lingkungan">Lingkungan</label>
                </div>
            </div>
        </div>
            <div data-mdb-input-init class="form-outline">
                <select name="status" id="status" class="form-control @error('status')
                    is-invalid @enderror" value="{{ old('status', $penduduks->status) }}">
                    @error('status')
                        <span class="invalid-feedback">
                        {{ $message }}
                        </span>
                    @enderror
                    <option value="aktif">AKTIF</option>
                    <option value="pindah">PINDAH</option>
                    <option value="meninggal">MENINGGAL</option>
                </select>
                <label class="form-label" for="status">Status Kependudukan</label>
            </div>
        <!-- Submit button -->
        <div class="row mb-4">
            <div class="col">
            <a href="/penduduk" class="btn btn-outline-secondary btn-block mb-4">Kembali</a>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-warning btn-block mb-4">Simpan perubahan</button>
            </div>
        </div>
        
    </form>
        
    </div>
    
@endsection