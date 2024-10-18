@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Edit Stok</h5>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('stocks.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stocks.update', $stock->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto_item" class="form-label">Foto Item</label>
                                    @if($stock->foto_item)
                                        <img src="{{ Storage::url($stock->foto_item) }}" width="100" alt="Foto Item" class="mb-3">
                                    @endif
                                    <input type="file" class="form-control" id="foto_item" name="foto_item">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_batubara" class="form-label">Jenis Batubara</label>
                                    <input type="text" class="form-control" id="jenis_batubara" name="jenis_batubara" value="{{ $stock->jenis_batubara }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="grade" name="grade" value="{{ $stock->grade }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="size" class="form-label">Size</label>
                                    <input type="text" class="form-control" id="size" name="size" value="{{ $stock->size }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kalori" class="form-label">Kalori</label>
                                    <input type="text" class="form-control" id="kalori" name="kalori" value="{{ $stock->kalori }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah_stok" class="form-label">Jumlah Stok</label>
                                    <input type="number" class="form-control" id="jumlah_stok" name="jumlah_stok" value="{{ $stock->jumlah_stok }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lokasi_simpan" class="form-label">Lokasi Simpan</label>
                                    <input type="text" class="form-control" id="lokasi_simpan" name="lokasi_simpan" value="{{ $stock->lokasi_simpan }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="harga_per_ton" class="form-label">Harga per Ton</label>
                                    <input type="text" class="form-control" id="harga_per_ton" name="harga_per_ton" value="{{ $stock->harga_per_ton }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ $stock->catatan }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_alias" class="form-label">Nama Alias</label>
                                    <input type="text" class="form-control" id="nama_alias" name="nama_alias" value="{{ $stock->nama_alias }}">
                                </div>
                            </div>
                        </div>

                        <!-- Tambahan Kolom Detail Stok -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="detail_stock" class="form-label">Detail Stok</label>
                                    <textarea class="form-control" id="detail_stock" name="detail_stock" rows="3">{{ $stock->detail_stock }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Akhir Tambahan Kolom Detail Stok -->

                        <!-- Menampilkan Stok Sebelum Update -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="stock_before_update" class="form-label">Stok Sebelum Update</label>
                                    <input type="number" class="form-control" id="stock_before_update" name="stock_before_update" value="{{ $stock->stock_before_update }}" readonly>
                                </div>
                            </div>
                        </div>
                        <!-- Akhir Tampilan Stok Sebelum Update -->

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
