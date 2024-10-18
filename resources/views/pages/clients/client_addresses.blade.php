@extends('layouts.user_type.auth')

@section('content')

<!-- Custom CSS untuk menghilangkan panah pada input number -->
<style>
    /* Hapus panah pada input number di Chrome, Safari, Edge, dan Opera */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hapus panah pada input number di Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">All Addresses for {{ $client->company }}</h5>
                    </div>
                    <div>
                        <a href="{{ route('clients.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">Back to Clients</a> <!-- Tombol Kembali -->
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createAddressModal">+&nbsp; New Address</a> <!-- Tombol Tambah Address -->
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penerima</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat Lengkap</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama CP</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nomor Telp</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View Spec</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($addresses as $address)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $address->penerima }}</td>
                                <td class="text-center">{{ $address->alamat_lengkap }}</td>
                                <td class="text-center">{{ $address->nama_cp }}</td>
                                <td class="text-center">{{ $address->nomor_telp }}</td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm bg-gradient-info" data-bs-toggle="modal" data-bs-target="#viewSpecModal-{{ $address->id }}">
                                        View Spec
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editAddressModal-{{ $address->id }}">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#deleteAddressModal-{{ $address->id }}">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Address Modal -->
                            <div class="modal fade" id="editAddressModal-{{ $address->id }}" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAddressModalLabel">Edit Address</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('clients.addresses.update', [$client->id, $address->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="penerima">Penerima</label>
                                                    <input type="text" class="form-control" name="penerima" value="{{ $address->penerima }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat_lengkap">Alamat Lengkap</label>
                                                    <input type="text" class="form-control" name="alamat_lengkap" value="{{ $address->alamat_lengkap }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_cp">Nama CP</label>
                                                    <input type="text" class="form-control" name="nama_cp" value="{{ $address->nama_cp }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nomor_telp">Nomor Telp</label>
                                                    <input type="text" class="form-control" name="nomor_telp" value="{{ $address->nomor_telp }}" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Address Modal -->
                            <div class="modal fade" id="deleteAddressModal-{{ $address->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteAddressModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAddressModalLabel">Delete Address</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this address?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('clients.addresses.destroy', [$client->id, $address->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal View Spec -->
                            <div class="modal fade" id="viewSpecModal-{{ $address->id }}" tabindex="-1" role="dialog" aria-labelledby="viewSpecModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-gradient-primary">
                                            <h5 class="modal-title text-white" id="viewSpecModalLabel">Client Specification for {{ $address->alamat_lengkap }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($address->specs->isNotEmpty())
                                                <div class="row">
                                                    @foreach($address->specs as $spec)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="card spec-card h-100">
                                                                <div class="card-body">
                                                                    <h5 class="card-title mb-4">Specification #{{ $loop->iteration }}</h5>
                                                                    <div class="spec-item">
                                                                        <i class="fas fa-cube me-2"></i>
                                                                        <span class="spec-label">Jenis Batubara:</span>
                                                                        <span class="spec-value">{{ $spec->jenis_batubara }}</span>
                                                                    </div>
                                                                    <div class="spec-item">
                                                                        <i class="fas fa-star me-2"></i>
                                                                        <span class="spec-label">Grade:</span>
                                                                        <span class="spec-value">{{ $spec->grade }}</span>
                                                                    </div>
                                                                    <div class="spec-item">
                                                                        <i class="fas fa-ruler-horizontal me-2"></i>
                                                                        <span class="spec-label">Size:</span>
                                                                        <span class="spec-value">{{ $spec->size }}</span>
                                                                    </div>
                                                                    <div class="spec-item">
                                                                        <i class="fas fa-fire me-2"></i>
                                                                        <span class="spec-label">Kalori:</span>
                                                                        <span class="spec-value">{{ $spec->kalori }}</span>
                                                                    </div>
                                                                    <div class="spec-item">
                                                                        <i class="fas fa-info-circle me-2"></i>
                                                                        <span class="spec-label">Status:</span>
                                                                        <span class="spec-value">{{ $spec->status }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-center">Client Specification belum ada.</p>
                                            @endif
                                        </div>         
                                        <div class="modal-footer">
                                            <a href="{{ route('client.specs.index', $address->id) }}" class="btn btn-primary">Manage Specifications</a>
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


<!-- Create Address Modal -->
<div class="modal fade" id="createAddressModal" tabindex="-1" role="dialog" aria-labelledby="createAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAddressModalLabel">Create New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('clients.addresses.store', $client->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="penerima">Penerima</label>
                        <input type="text" class="form-control" name="penerima" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat_lengkap">Alamat Lengkap</label>
                        <input type="text" class="form-control" name="alamat_lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_cp">Nama CP</label>
                        <input type="text" class="form-control" name="nama_cp" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telp">Nomor Telp</label>
                        <input type="text" class="form-control" name="nomor_telp" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Address</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all phone inputs in the modals
        const phoneInputs = document.querySelectorAll('input[name="nomor_telp"]');

        // Add an event listener to each phone input to prevent non-numeric input
        phoneInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    });
</script>


<style>
    .modal-lg {
        max-width: 80%;
    }

    .spec-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .spec-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .spec-item {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .spec-label {
        font-weight: 600;
        margin-right: 10px;
        color: #555;
    }

    .spec-value {
        color: #333;
    }

    .card-title {
        color: #4a5568;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 10px;
    }

    .fas {
        color: #3498db;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: none;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }
</style>