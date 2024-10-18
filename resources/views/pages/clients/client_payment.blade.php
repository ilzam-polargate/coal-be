@extends('layouts.user_type.auth')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">Payments for Order {{ $order->no_po }}</h5>
                    </div>
                    <div>
                        <a href="{{ route('client_orders.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">Back to Orders</a>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createPaymentModal">+&nbsp; New Payment</a>
                    </div>
                </div>                
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">Termin Ke</th>
                                <th class="text-center">Jumlah Bayar</th>
                                <th class="text-center">Tgl Jatuh Tempo</th>
                                <th class="text-center">Tgl Bayar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td class="text-center">{{ $payment->termin_ke }}</td>
                                <td class="text-center">{{ $payment->jumlah_bayar }}</td>
                                <td class="text-center">{{ $payment->tgl_jatuh_tempo }}</td>
                                <td class="text-center">{{ $payment->tgl_bayar ?? 'Belum Bayar' }}</td>
                                <!-- Status Dropdown dalam Tabel -->
                                <td class="text-center">
                                    <select class="form-select payment-status" data-payment-id="{{ $payment->id }}">
                                        <option value="unpaid" {{ $payment->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $payment->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </td>

                                <td class="text-center">
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editPaymentModal-{{ $payment->id }}">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#deletePaymentModal-{{ $payment->id }}">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Payment Modal -->
                            <div class="modal fade" id="editPaymentModal-{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('client_payments.update', [$order->id, $payment->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <!-- Termin Ke hanya bisa dilihat, tidak bisa diubah -->
                                                <div class="form-group">
                                                    <label for="termin_ke">Termin Ke</label>
                                                    <input type="number" class="form-control" name="termin_ke" value="{{ $payment->termin_ke }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="jumlah_bayar">Jumlah Bayar</label>
                                                    <input type="number" class="form-control" name="jumlah_bayar" value="{{ $payment->jumlah_bayar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tgl_jatuh_tempo">Tgl Jatuh Tempo</label>
                                                    <input type="date" class="form-control" name="tgl_jatuh_tempo" value="{{ $payment->tgl_jatuh_tempo }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="payment_status">Status</label>
                                                    <select class="form-control" name="payment_status" required>
                                                        <option value="unpaid" {{ $payment->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                        <option value="paid" {{ $payment->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="keterangan">Keterangan</label>
                                                    <textarea class="form-control" name="keterangan">{{ $payment->keterangan }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Payment Modal -->
                            <div class="modal fade" id="deletePaymentModal-{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this payment?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('client_payments.destroy', [$order->id, $payment->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
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

<!-- Create Payment Modal -->
<div class="modal fade" id="createPaymentModal" tabindex="-1" role="dialog" aria-labelledby="createPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPaymentModalLabel">Create New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client_payments.store', $order->id) }}" method="POST">
                    @csrf
                    <!-- Termin Ke sudah otomatis di controller, jadi tidak perlu input -->
                    <div class="form-group">
                        <label for="client_order_detail_id">Select Order Detail</label>
                        <select class="form-control" name="client_order_detail_id" required>
                            @foreach($availableOrderDetails as $detail)
                                <option value="{{ $detail->id }}">
                                    Order Detail #{{ $detail->id }} - {{ $detail->no_po }} - Jumlah: {{ $detail->jumlah_order }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_bayar">Jumlah Bayar</label>
                        <input type="number" class="form-control" name="jumlah_bayar" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_jatuh_tempo">Tgl Jatuh Tempo</label>
                        <input type="date" class="form-control" name="tgl_jatuh_tempo" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Ketika dropdown status berubah
        $('.payment-status').change(function() {
            var paymentId = $(this).data('payment-id');
            var paymentStatus = $(this).val();

            $.ajax({
                url: '/client_payments/' + paymentId + '/update-status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF token untuk proteksi Laravel
                    payment_status: paymentStatus
                },
                success: function(response) {
                    // Ganti alert dengan toastr success
                    toastr.success(response.message, 'Success');
                    
                    // Opsional: reload halaman atau tabel jika diperlukan untuk melihat update otomatis
                    // location.reload(); // Uncomment jika ingin halaman reload setelah sukses
                },
                error: function(xhr) {
                    // Ganti alert dengan toastr error
                    toastr.error('An error occurred while updating the status.', 'Error');
                }
            });
        });
    });
</script>

@endsection
