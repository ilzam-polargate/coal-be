@extends('layouts.user_type.auth')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">All Client Specs for {{ $clientAddress->alamat_lengkap }}</h5>
                    </div>
                    <div>
                        <a href="{{ route('clients.addresses', $clientAddress->client_id) }}" class="btn bg-gradient-secondary btn-sm mb-0">Back to Addresses</a>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createSpecModal">+&nbsp; New Spec</a>
                    </div>
                    
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Jenis Batubara</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Kalori</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientSpecs as $spec)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $spec->jenis_batubara }}</td>
                                <td class="text-center">{{ $spec->grade }}</td>
                                <td class="text-center">{{ $spec->size }}</td>
                                <td class="text-center">{{ $spec->kalori }}</td>
                                <td class="text-center">{{ $spec->status }}</td>
                                <td class="text-center">
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editSpecModal-{{ $spec->id }}">
                                        <i class="fas fa-edit text-secondary"></i>
                                    </a>
                                    <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#deleteSpecModal-{{ $spec->id }}">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Spec Modal -->
                            <div class="modal fade" id="editSpecModal-{{ $spec->id }}" tabindex="-1" role="dialog" aria-labelledby="editSpecModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSpecModalLabel">Edit Spec</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('client.specs.update', $spec->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="jenis_batubara">Jenis Batubara</label>
                                                    <input type="text" class="form-control" name="jenis_batubara" value="{{ $spec->jenis_batubara }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="grade">Grade</label>
                                                    <input type="text" class="form-control" name="grade" value="{{ $spec->grade }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="size">Size</label>
                                                    <input type="text" class="form-control" name="size" value="{{ $spec->size }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="kalori">Kalori</label>
                                                    <input type="text" class="form-control" name="kalori" value="{{ $spec->kalori }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <input type="text" class="form-control" name="status" value="{{ $spec->status }}" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Spec Modal -->
                            <div class="modal fade" id="deleteSpecModal-{{ $spec->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteSpecModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteSpecModalLabel">Delete Spec</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this spec?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('client.specs.destroy', $spec->id) }}" method="POST">
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

<!-- Create Spec Modal -->
<div class="modal fade" id="createSpecModal" tabindex="-1" role="dialog" aria-labelledby="createSpecModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSpecModalLabel">Create New Spec</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('client.specs.store', $clientAddress->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="jenis_batubara">Jenis Batubara</label>
                        <input type="text" class="form-control" name="jenis_batubara" required>
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade</label>
                        <input type="text" class="form-control" name="grade" required>
                    </div>
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input type="text" class="form-control" name="size" required>
                    </div>
                    <div class="form-group">
                        <label for="kalori">Kalori</label>
                        <input type="text" class="form-control" name="kalori" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" name="status" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Spec</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
