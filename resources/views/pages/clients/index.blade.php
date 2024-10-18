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
                        <h5 class="mb-0">All Clients</h5>
                    </div>
                    <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createClientModal">+&nbsp; New Client</a>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Purchasing Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th> <!-- Kolom Address -->
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $client->company }}</td>
                                <td class="text-center">{{ $client->nama_purchasing }}</td>
                                <td class="text-center">{{ $client->email }}</td>
                                <td class="text-center">{{ $client->nomor_telep }}</td>
                                <td class="text-center">
                                    <!-- Tambahkan link untuk membuka halaman client addresses -->
                                    <a href="{{ route('clients.addresses', $client->id) }}" class="btn btn-sm bg-gradient-info">
                                        View Address
                                    </a>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol untuk Edit Client -->
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editClientModal-{{ $client->id }}">
                                        <i class="fas fa-user-edit text-secondary"></i>
                                    </a>
                                
                                    <!-- Tombol untuk Request Delete -->
                                    @if (!$client->deletion_requested)
                                        <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#deleteClientModal-{{ $client->id }}">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    @endif
                                
                                    <!-- Tombol untuk Approve Delete (Jika Request Delete Sudah Diajukan) -->
                                    @if ($client->deletion_requested && !$client->deletion_approved)
                                        <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $client->id }}">
                                            <i class="fas fa-check text-success"></i>
                                        </a>
                                    @elseif($client->deletion_approved)
                                        <span class="badge bg-danger">Deleted</span>
                                    @endif
                                </td>                                
                                
                            </tr>

                            <!-- Edit Client Modal -->
                            <div class="modal fade" id="editClientModal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="editClientModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('clients.update', $client->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="company">Company</label>
                                                    <input type="text" class="form-control" name="company" value="{{ $client->company }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_purchasing">Purchasing Name</label>
                                                    <input type="text" class="form-control" name="nama_purchasing" value="{{ $client->nama_purchasing }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat">Address</label>
                                                    <input type="text" class="form-control" name="alamat" value="{{ $client->alamat }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="email" value="{{ $client->email }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nomor_telep">Phone</label>
                                                    <input type="number" class="form-control" name="nomor_telep" value="{{ $client->nomor_telep }}" required min="0" step="1">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Client Modal -->
                            <div class="modal fade" id="deleteClientModal-{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteClientModalLabel">Delete Client</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this client?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Delete Modal -->
                            <div class="modal fade" id="confirmDeleteModal-{{ $client->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Confirm Client Deletion
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning" role="alert">
                                                <i class="fas fa-info-circle me-2"></i> Are you sure you want to delete this client? This action cannot be undone.
                                            </div>
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <h6 class="card-subtitle mb-3 text-muted"><i class="fas fa-user-tie me-2"></i>Client Information</h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong><i class="fas fa-building me-2"></i>Company:</strong> {{ $client->company }}</p>
                                                            <p><strong><i class="fas fa-user me-2"></i>Purchasing Name:</strong> {{ $client->nama_purchasing }}</p>
                                                            <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> {{ $client->email }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong><i class="fas fa-phone me-2"></i>Phone:</strong> {{ $client->nomor_telep }}</p>
                                                            <p><strong><i class="fas fa-map-marker-alt me-2"></i>Address:</strong> {{ $client->alamat }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="deletion-reason">
                                                <h6 class="text-danger mb-2"><i class="fas fa-question-circle me-2"></i>Reason for Deletion</h6>
                                                <p class="mb-0">{{ $client->deletion_reason }}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('clients.approveDelete', $client->id) }}" method="POST">
                                                @csrf

                                                <!-- Button for Cancel Request -->
                                                <button type="submit" name="reject" value="reject" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times-circle me-2"></i>Cancel Request
                                                </button>

                                                <!-- Button for Confirm Delete -->
                                                <button type="submit" name="approve" value="approve" class="btn btn-danger btn-delete">
                                                    <i class="fas fa-trash-alt me-2"></i>Confirm Deletion
                                                </button>
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

<!-- Create Client Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createClientModalLabel">Create New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" name="company" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_purchasing">Purchasing Name</label>
                        <input type="text" class="form-control" name="nama_purchasing" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Address</label>
                        <input type="text" class="form-control" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telep">Phone</label>
                        <input type="number" class="form-control" name="nomor_telep" required min="0" step="1">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Client</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all phone inputs in the modals
        const phoneInputs = document.querySelectorAll('input[name="nomor_telep"]');

        // Add an event listener to each phone input to prevent non-numeric input
        phoneInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    });
</script>

<style>
    /* .modal-header {
        background: linear-gradient(45deg, #FF416C, #FF4B2B);
        color: white;
    } */
    .card {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }
    .card:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    .deletion-reason {
        background-color: #f8d7da;
        border-left: 5px solid #dc3545;
        padding: 15px;
        margin-top: 20px;
    }
    .modal-footer {
        background-color: #f8f9fa;
    }
    .btn-delete {
        background: linear-gradient(45deg, #FF416C, #FF4B2B);
        border: none;
    }
    .btn-delete:hover {
        background: linear-gradient(45deg, #FF4B2B, #FF416C);
    }
</style>
