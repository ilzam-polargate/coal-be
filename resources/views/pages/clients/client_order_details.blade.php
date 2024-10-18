@extends('layouts.user_type.auth')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Manage Order Details for Order #{{ $order->no_po }}</h5>
                    </div>
                    <div>
                        <!-- Tombol kembali ke halaman orders -->
                        <a href="{{ route('client_orders.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">Back to Orders</a>
                        <!-- Tombol untuk menambah order detail baru -->
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#addOrderDetailModal">+&nbsp; Add Order Detail</a>
                    </div>
                </div>                
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">No PO</th>
                                <th class="text-center">Alamat Lengkap</th>
                                <th class="text-center">Jenis Batubara</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Kalori</th>
                                <th class="text-center">Jumlah Order</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $detail)
                            <tr>
                                <!-- Ambil spesifikasi pertama -->
                                @php
                                    $spec = $order->clientAddress->specs->first();
                                @endphp
                        
                                <td class="text-center">{{ $detail->no_po }}</td>
                                <td class="text-center">{{ $order->clientAddress->alamat_lengkap }}</td>
                                <td class="text-center">{{ $spec ? $spec->jenis_batubara : '-' }}</td>
                                <td class="text-center">{{ $spec ? $spec->grade : '-' }}</td>
                                <td class="text-center">{{ $spec ? $spec->size : '-' }}</td>
                                <td class="text-center">{{ $spec ? $spec->kalori : '-' }}</td>
                        
                                <td class="text-center">{{ $detail->jumlah_order }}</td>
                                <td class="text-center">
                                    <select class="form-control select-status" data-detail-id="{{ $detail->id }}">
                                        <option value="order requested" {{ $detail->status == 'order requested' ? 'selected' : '' }}>Order Requested</option>
                                        <option value="on process" {{ $detail->status == 'on process' ? 'selected' : '' }}>On Process</option>
                                        <option value="order delivered" {{ $detail->status == 'order delivered' ? 'selected' : '' }}>Order Delivered</option>
                                        <option value="in delivery" {{ $detail->status == 'in delivery' ? 'selected' : '' }}>In Delivery</option>
                                        <option value="arrive at location" {{ $detail->status == 'arrive at location' ? 'selected' : '' }}>Arrive at Location</option>
                                        <option value="rejected" {{ $detail->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="returned" {{ $detail->status == 'returned' ? 'selected' : '' }}>Returned</option>
                                        <option value="send to another location" {{ $detail->status == 'send to another location' ? 'selected' : '' }}>Send to Another Location</option>
                                        <option value="send to another client" {{ $detail->status == 'send to another client' ? 'selected' : '' }}>Send to Another Client</option>
                                        <option value="order completed" {{ $detail->status == 'order completed' ? 'selected' : '' }}>Order Completed</option>
                                    </select>                                    
                                </td>
                                
                                <td class="text-center">
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editOrderDetailModal-{{ $detail->id }}">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#deleteOrderDetailModal-{{ $detail->id }}">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Order Detail Modal -->
                            <div class="modal fade" id="editOrderDetailModal-{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="editOrderDetailModalLabel-{{ $detail->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Order Detail</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('client_order_details.update', [$order->id, $detail->id]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <!-- No PO (Disabled Input) -->
                                                <div class="form-group">
                                                    <label for="no_po">No PO</label>
                                                    <input type="text" class="form-control" name="no_po" value="{{ $detail->no_po }}" disabled>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="jumlah_order">Jumlah Order</label>
                                                    <input type="number" class="form-control" name="jumlah_order" value="{{ $detail->jumlah_order }}" required>
                                                </div>

                                                <!-- Image Upload -->
                                                <div class="form-group">
                                                    <label for="image">Upload Image</label>
                                                    <input type="file" class="form-control" name="image" accept="image/*">
                                                    @if($detail->image)
                                                        <img src="{{ Storage::url($detail->image) }}" alt="Order Image" class="img-fluid mt-2">
                                                    @endif
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Modal Konfirmasi Penghapusan -->
                            <div class="modal fade" id="deleteOrderDetailModal-{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteOrderDetailModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteOrderDetailModalLabel">Konfirmasi Penghapusan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus detail order ini?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('client_order_details.destroy', [$order->id, $detail->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Order Detail -->
<div class="modal fade" id="addOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="addOrderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderDetailModalLabel">Add New Order Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client_order_details.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- No PO (Hidden Input) -->
                    <!-- <input type="hidden" name="no_po"> -->

                    <div class="form-group">
                        <label for="jumlah_order">Jumlah Order</label>
                        <input type="number" class="form-control" name="jumlah_order" required>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="image">Upload Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Order Detail</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select-status').on('change', function () {
        var status = $(this).val();
        var detailId = $(this).data('detail-id');

        $.ajax({
            url: '{{ route("client_order_details.updateStatus") }}', // Pastikan rute ini sesuai
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: detailId, // Kirimkan id detail order
                status: status // Kirimkan status baru
            },
            success: function (response) {
                if(response.success) {
                    toastr.success(response.message || 'Status berhasil diperbarui.');
                } else {
                    toastr.error(response.message || 'Gagal memperbarui status.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error response:', xhr.responseText);
                var errorMessage = 'Terjadi kesalahan saat memperbarui status.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join(' ');
                }
                toastr.error(errorMessage);
                console.error('Detailed error:', xhr.responseJSON);
            }
        });
    });

    });
</script>
@endsection
