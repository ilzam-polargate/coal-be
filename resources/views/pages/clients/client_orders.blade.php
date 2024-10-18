@extends('layouts.user_type.auth')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Client Orders</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal"
                            data-bs-target="#createOrderModal">+&nbsp; New Order</a>
                    </div>
                </div>
                <div id="errorMessages"></div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">No PO</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Total Order</th>
                                    <th class="text-center">Order Detail</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Payment</th>
                                    {{-- <th class="text-center">Status</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $order->no_po }}</td>
                                        <td class="text-center">{{ $order->stock->nama_alias }}</td>
                                        <td class="text-center">
                                            {{ $order->details_sum_jumlah_order ?? 0 }} / {{ $order->total_order }}
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm bg-gradient-info" data-bs-toggle="modal"
                                                data-bs-target="#viewOrderDetailModal-{{ $order->id }}">
                                                Order Detail
                                            </a>
                                        </td>
                                        <td class="text-center">{{ $order->order_date }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm bg-gradient-info" data-bs-toggle="modal"
                                                data-bs-target="#viewPaymentModal-{{ $order->id }}">
                                                View Payment
                                            </a>
                                        </td>
                                        {{-- <td class="text-center">
                                            <select class="form-control select-with-indicator status-select"
                                                data-order-id="{{ $order->id }}">
                                                <option value="order requested"
                                                    {{ $order->status_order == 'order requested' ? 'selected' : '' }}>Order
                                                    Requested</option>
                                                <option value="on process"
                                                    {{ $order->status_order == 'on process' ? 'selected' : '' }}>On Process
                                                </option>
                                                <option value="order delivered"
                                                    {{ $order->status_order == 'order delivered' ? 'selected' : '' }}>Order
                                                    Delivered</option>
                                                <option value="in delivery"
                                                    {{ $order->status_order == 'in delivery' ? 'selected' : '' }}>In
                                                    Delivery</option>
                                                <option value="arrive at location"
                                                    {{ $order->status_order == 'arrive at location' ? 'selected' : '' }}>
                                                    Arrive at Location</option>
                                                <option value="rejected"
                                                    {{ $order->status_order == 'rejected' ? 'selected' : '' }}>Rejected
                                                </option>
                                                <option value="returned"
                                                    {{ $order->status_order == 'returned' ? 'selected' : '' }}>Returned
                                                </option>
                                                <option value="send to another location"
                                                    {{ $order->status_order == 'send to another location' ? 'selected' : '' }}>
                                                    Send to Another Location</option>
                                                <option value="send to another client"
                                                    {{ $order->status_order == 'send to another client' ? 'selected' : '' }}>
                                                    Send to Another Client</option>
                                                <option value="order completed"
                                                    {{ $order->status_order == 'order completed' ? 'selected' : '' }}>Order
                                                    Completed</option>
                                            </select>
                                        </td> --}}
                                        <td class="text-center">
                                            <a href="#" class="mx-3" data-bs-toggle="modal"
                                                data-bs-target="#editOrderModal-{{ $order->id }}">
                                                <i class="fas fa-edit text-secondary"></i>
                                            </a>
                                            <a href="#" class="mx-3" data-bs-toggle="modal"
                                                data-bs-target="#deleteOrderModal-{{ $order->id }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
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

    <!-- Tempatkan modal di luar tbody -->
    @foreach ($orders as $order)
        <!-- Modal View Payment -->
        <div class="modal fade" id="viewPaymentModal-{{ $order->id }}" tabindex="-1" role="dialog"
            aria-labelledby="viewPaymentModalLabel-{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary">
                        <h5 class="modal-title text-white" id="viewPaymentModalLabel-{{ $order->id }}">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Client Payment for {{ $order->no_po }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($order->payments->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">Termin</th>
                                            <th class="text-center">Jumlah Bayar</th>
                                            <th class="text-center">Tgl Jatuh Tempo</th>
                                            <th class="text-center">Tgl Bayar</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->payments as $payment)
                                            <tr>
                                                <td class="text-center">{{ $payment->termin_ke }}</td>
                                                <td class="text-center">Rp
                                                    {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($payment->tgl_jatuh_tempo)->format('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($payment->tgl_bayar)
                                                        {{ \Carbon\Carbon::parse($payment->tgl_bayar)->format('d M Y') }}
                                                    @else
                                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match ($payment->payment_status) {
                                                            'Paid' => 'bg-success',
                                                            'Unpaid' => 'bg-danger',
                                                            'Partial' => 'bg-warning text-dark',
                                                            default => 'bg-secondary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusClass }}">{{ $payment->payment_status }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Payment belum diatur.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('client_payments.index', $order->id) }}" class="btn btn-primary">
                            <i class="fas fa-cog me-2"></i>Manage Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal View Order Detail -->
        <div class="modal fade" id="viewOrderDetailModal-{{ $order->id }}" tabindex="-1" role="dialog"
            aria-labelledby="viewOrderDetailModalLabel-{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-gradient-primary">
                        <h5 class="modal-title text-white" id="viewOrderDetailModalLabel-{{ $order->id }}">
                            <i class="fas fa-list me-2"></i>Order Details for {{ $order->no_po }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($order->details->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">No PO</th> <!-- Tambahkan kolom No PO -->
                                            <th class="text-center">Alamat Lengkap</th>
                                            <th class="text-center">Jenis Batu Bara</th>
                                            <th class="text-center">Grade</th>
                                            <th class="text-center">Size</th>
                                            <th class="text-center">Kalori</th>
                                            <th class="text-center">Jumlah Order</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->details as $detail)
                                            @php
                                                $spec = $order->clientAddress->specs->first();
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $detail->no_po }}</td> <!-- Tampilkan no_po -->
                                                <td class="text-center">{{ $order->clientAddress->alamat_lengkap }}</td>
                                                <td class="text-center">{{ $spec ? $spec->jenis_batubara : '-' }}</td>
                                                <td class="text-center">{{ $spec ? $spec->grade : '-' }}</td>
                                                <td class="text-center">{{ $spec ? $spec->size : '-' }}</td>
                                                <td class="text-center">{{ $spec ? $spec->kalori : '-' }}</td>
                                                <td class="text-center">{{ $detail->jumlah_order }}</td>
                                                <td class="text-center">{{ $detail->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Order details belum diatur.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('client_order_details.index', $order->id) }}" class="btn btn-primary">
                            <i class="fas fa-cog me-2"></i>Manage Order Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Order Modal -->
        <div class="modal fade" id="editOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('client_orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- No PO -->
                            <div class="form-group">
                                <label for="no_po">No PO</label>
                                <input type="text" class="form-control" name="no_po" value="{{ $order->no_po }}"
                                    required>
                            </div>

                            <!-- Stock -->
                            <div class="form-group">
                                <label for="stock_id">Stock</label>
                                <select class="form-control select-with-indicator" id="edit-stock-{{ $order->id }}"
                                    name="stock_id" required>
                                    <option value="" disabled selected>Pilih Stock</option>
                                    @foreach ($stocks as $stock)
                                        <option value="{{ $stock->id }}" data-jumlah-stok="{{ $stock->jumlah_stok }}"
                                            {{ $stock->id == $order->stock_id ? 'selected' : '' }}>
                                            {{ $stock->nama_alias }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Menampilkan Stok Tersedia -->
                            <div class="form-group">
                                <label for="stok_tersedia">Stok Tersedia</label>
                                <input type="text" class="form-control" id="stok_tersedia"
                                    value="{{ $order->stock->jumlah_stok }}" readonly>
                            </div>

                            <!-- Client Address -->
                            <div class="form-group">
                                <label for="client_address_id">Client Address</label>
                                <select class="form-control select-with-indicator"
                                    id="edit-client-address-{{ $order->id }}" name="client_address_id" required>
                                    <option value="" disabled>Pilih Client Address</option>
                                    @foreach ($addresses as $address)
                                        <option value="{{ $address->id }}"
                                            {{ $address->id == $order->client_address_id ? 'selected' : '' }}>
                                            {{ $address->alamat_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Client Spec -->
                            <div class="form-group">
                                <label for="client_spec_id">Client Spec Reference</label>
                                <select class="form-control select-with-indicator"
                                    id="edit-client-spec-{{ $order->id }}" name="client_spec_id" required>
                                    <option value="" disabled selected>Pilih Client Address terlebih dahulu</option>
                                    @if ($order->client_spec_id)
                                        <option value="{{ $order->client_spec_id }}" selected>
                                            {{ $order->clientAddress->specs->firstWhere('id', $order->client_spec_id)->jenis_batubara }}
                                        </option>
                                    @endif
                                </select>
                            </div>


                            <!-- Jumlah Order -->
                            <div class="form-group">
                                <label for="total_order">Jumlah Order</label>
                                <input type="number" class="form-control" id="total_order" name="total_order"
                                    value="{{ old('total_order', $order->total_order) }}" required>
                                @error('total_order')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <!-- Keterangan -->
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" name="keterangan">{{ $order->keterangan }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Delete Order Modal -->
        <div class="modal fade" id="deleteOrderModal-{{ $order->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteOrderModalLabel">Delete Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this order?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('client_orders.destroy', $order->id) }}" method="POST">
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

    <!-- Create Order Modal -->
    <div class="modal fade" id="createOrderModal" tabindex="-1" role="dialog" aria-labelledby="createOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createOrderModalLabel">Create New Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('client_orders.store') }}" method="POST">
                        @csrf
                        <!-- No PO -->
                        <div class="form-group">
                            <label for="no_po">No PO</label>
                            <input type="text" class="form-control" id="no_po" name="no_po" required>
                        </div>

                        <!-- Stock -->
                        <div class="form-group">
                            <label for="stock_id">Stock</label>
                            <select class="form-control select-with-indicator" id="stock_id" name="stock_id" required>
                                <option value="" disabled selected>Pilih Stock</option>
                                @foreach ($stocks as $stock)
                                    <option value="{{ $stock->id }}" data-jumlah-stok="{{ $stock->jumlah_stok }}">
                                        {{ $stock->nama_alias }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Menampilkan Stok Tersedia -->
                        <div class="form-group">
                            <label for="stok_tersedia">Stok Tersedia</label>
                            <input type="text" class="form-control" id="stok_tersedia" readonly>
                        </div>

                        <!-- Client Address -->
                        <div class="form-group">
                            <label for="client_address_id">Client Address</label>
                            <select class="form-control select-with-indicator" id="client_address_id"
                                name="client_address_id" required>
                                <option value="" disabled selected>Pilih Client Address</option>
                                @foreach ($addresses as $address)
                                    <option value="{{ $address->id }}">{{ $address->alamat_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Client Spec -->
                        <div class="form-group">
                            <label for="client_spec_id">Client Spec Reference</label>
                            <select class="form-control select-with-indicator" id="client_spec_id" name="client_spec_id"
                                disabled>
                                <option value="" disabled selected>Pilih Client Address terlebih dahulu</option>
                            </select>
                        </div>

                        <!-- Jumlah Order -->
                        <div class="form-group">
                            <label for="total_order">Jumlah Order</label>
                            <input type="number" class="form-control" id="total_order" name="total_order" required>
                            @error('total_order')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Keterangan -->
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" name="keterangan"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Tambahkan jQuery dan AJAX untuk mengupdate status -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk menampilkan error di luar modal
            function showErrorMessages(errors) {
                var errorContainer = $('#errorMessages');
                errorContainer.empty(); // Kosongkan pesan error sebelumnya
                if (errors) {
                    var errorList = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(key, message) {
                        errorList += '<li>' + message + '</li>';
                    });
                    errorList += '</ul></div>';
                    errorContainer.append(errorList); // Tambahkan pesan error baru
                }
            }
    
            // Fungsi untuk memperbarui stok tersedia berdasarkan stock yang dipilih
            function updateStokTersedia(selectElement) {
                const selectedOption = $(selectElement).find('option:selected');
                const jumlahStok = selectedOption.data('jumlah-stok');
                const modalId = $(selectElement).closest('.modal').attr('id');
                
                // Set value stok tersedia di modal sesuai dengan stock yang dipilih
                $(`#${modalId} #stok_tersedia`).val(jumlahStok || '');
            }
    
            // Fungsi untuk menutup modal sepenuhnya dan menghapus background modal
            function closeModal(modal) {
                modal.modal('hide'); // Tutup modal
                $('body').removeClass('modal-open'); // Hapus kelas modal-open dari body
                $('.modal-backdrop').remove(); // Hapus elemen backdrop dari DOM
            }
    
            // Handle form submission dengan validasi dan tutup modal
            function handleFormSubmission(form, isEdit = false) {
                $(form).on('submit', function(e) {
                    e.preventDefault();
    
                    const formData = new FormData(this); // Ambil data dari form
                    const url = $(this).attr('action'); // Ambil URL dari action form
                    const modal = $(this).closest('.modal'); // Cari modal terkait
    
                    // Reset error messages sebelum validasi
                    $('#errorMessages').empty();
                    $(form).find('.error-message').remove();
    
                    $.ajax({
                        url: url,
                        method: isEdit ? 'POST' : 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                closeModal(modal); // Tutup modal sepenuhnya jika berhasil
                                location.reload(); // Refresh halaman setelah sukses
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) { // Validasi gagal di server (HTTP 422)
                                const errors = xhr.responseJSON.errors;
                                closeModal(modal); // Tutup modal sepenuhnya saat ada error
                                showErrorMessages(errors); // Tampilkan pesan error di luar modal
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        }
                    });
                });
            }
    
            // Event listener untuk perubahan stock di modal Create dan Edit
            $('#createOrderModal #stock_id').on('change', function() {
                updateStokTersedia(this);
            });
    
            $('[id^=editOrderModal-] [name="stock_id"]').on('change', function() {
                updateStokTersedia(this);
            });
    
            // Fungsi untuk mengatur client specs berdasarkan client address
            function setClientSpecs(clientAddressId, targetSpecSelect) {
                if (clientAddressId) {
                    $.ajax({
                        url: '/client-specs/' + clientAddressId, // Mengambil spesifikasi berdasarkan alamat
                        type: 'GET',
                        success: function(data) {
                            var clientSpecSelect = $(targetSpecSelect);
                            clientSpecSelect.empty(); // Reset dropdown spesifikasi
                            clientSpecSelect.append('<option value="" disabled selected>Pilih Client Spec</option>');
    
                            // Loop data yang didapatkan dan tambahkan ke select
                            $.each(data, function(index, spec) {
                                clientSpecSelect.append('<option value="' + spec.id + '">' +
                                    spec.jenis_batubara + ' (Grade: ' + spec.grade +
                                    ' | Size: ' + spec.size + ' | Kalori: ' + spec.kalori + ')</option>');
                            });
    
                            clientSpecSelect.prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching client specs:", error);
                            alert("Failed to load client specs. Please try again.");
                        }
                    });
                } else {
                    $(targetSpecSelect).empty().append(
                        '<option value="" disabled selected>Pilih Client Address terlebih dahulu</option>'
                    ).prop('disabled', true);
                }
            }
    
            // Event listener untuk perubahan Client Address di modal Create dan Edit
            $('#createOrderModal #client_address_id').on('change', function() {
                setClientSpecs($(this).val(), '#createOrderModal #client_spec_id');
            });
    
            $('[id^=editOrderModal-]').each(function() {
                var modalId = '#' + $(this).attr('id');
                $(modalId + ' [name="client_address_id"]').on('change', function() {
                    setClientSpecs($(this).val(), modalId + ' [name="client_spec_id"]');
                });
            });
    
            // Fungsi untuk validasi jumlah order sebelum submit
            function validateOrder(form) {
                const totalOrder = parseInt($(form).find('[name="total_order"]').val());
                const stokTersedia = parseInt($(form).find('#stok_tersedia').val());
    
                if (totalOrder > stokTersedia) {
                    $(form).find('[name="total_order"]')
                        .siblings('.error-message').remove()
                        .after('<div class="error-message text-danger mt-1">Jumlah order tidak boleh melebihi stok tersedia.</div>');
                    return false;
                }
                return true;
            }
    
            // Inisialisasi form Create dan Edit dengan validasi
            handleFormSubmission('#createOrderModal form', false);
            $('[id^=editOrderModal-]').each(function() {
                handleFormSubmission($(this).find('form'), true);
            });
    
            // Inisialisasi Select2 untuk dropdown stock dan client address
            $('#stock_id, #client_address_id').select2({
                placeholder: "Pilih...",
                allowClear: true
            });
    
            // Inisialisasi Select2 untuk dropdown di modal Edit
            $('[id^=editOrderModal-]').each(function() {
                var modalId = '#' + $(this).attr('id');
                $(modalId + ' select').select2({
                    placeholder: "Pilih...",
                    allowClear: true
                });
            });
    
            // Reset form dan Select2 saat modal ditutup
            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset(); // Reset form input
                $(this).find('select').val('').trigger('change'); // Reset select2
            });
        });
    </script>

    <style>
        /* Indikator select dengan panah bawah */
        .select-with-indicator {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"><path fill="gray" d="M7 10l5 5 5-5z"/></svg>');
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 10px 10px;
            padding-right: 25px;
            /* Space for indicator */
        }
    </style>
@endsection
