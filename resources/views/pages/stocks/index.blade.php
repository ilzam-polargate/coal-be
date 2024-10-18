@extends('layouts.user_type.auth')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Daftar Stok</h5>
                    </div>
                    <a href="{{ route('stocks.create') }}" class="btn bg-gradient-primary btn-sm mb-0">+ Tambah Stok</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Batubara</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Grade</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Size</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kalori</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Stok</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr>
                                <td class="ps-4">
                                    <p class="text-xs font-weight-bold mb-0">{{ $loop->iteration }}</p>
                                </td>
                                <td class="text-center">
                                    @if($stock->foto_item)
                                        <img src="{{ Storage::url($stock->foto_item) }}" width="100" alt="Foto Item">
                                    @else
                                        <span class="text-xs font-weight-bold">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->jenis_batubara }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->grade }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->size }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->kalori }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $stock->jumlah_stok }}</p>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('stocks.edit', $stock->id) }}" class="mx-3">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    <span type="button" class="text-secondary btn-delete" data-id="{{ $stock->id }}">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Penghapusan -->
<div class="modal fade" id="deleteStockModal" tabindex="-1" role="dialog" aria-labelledby="deleteStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteStockModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus stok ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let stockIdToDelete = null;
        let formToSubmit = null;

        // Inisialisasi modal konfirmasi delete
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteStockModal'));

        // Event listener untuk tombol delete
        document.querySelectorAll('.btn-delete').forEach(function(button) {
            button.addEventListener('click', function() {
                stockIdToDelete = this.getAttribute('data-id');
                formToSubmit = document.createElement('form');
                formToSubmit.method = 'POST';
                formToSubmit.action = `/stocks/${stockIdToDelete}`;
                formToSubmit.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                deleteModal.show();  // Tampilkan modal
            });
        });

        // Event listener untuk tombol konfirmasi di modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (formToSubmit) {
                document.body.appendChild(formToSubmit);  // Masukkan form dinamis ke DOM
                formToSubmit.submit();  // Submit form untuk menghapus
            }
        });
    });
</script>
@endpush


