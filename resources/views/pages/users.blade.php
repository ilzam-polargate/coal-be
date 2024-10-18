@extends('layouts.user_type.auth')

@section('content')

<!-- Custom CSS untuk menghilangkan panah pada input number -->
<style>
    /* Menghilangkan panah di input type number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<!-- Page Content -->
<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Users</h5>
                        </div>
                        <a href="#" class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#createUserModal">+&nbsp; New User</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Username</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Creation Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $loop->iteration }}</p></td>
                                    <td class="text-center"><p class="text-xs font-weight-bold mb-0">{{ $user->username }}</p></td>
                                    <td class="text-center"><p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p></td>
                                    <td class="text-center"><p class="text-xs font-weight-bold mb-0">{{ $user->position }}</p></td>
                                    <td class="text-center"><p class="text-xs font-weight-bold mb-0">{{ ucfirst($user->user_status) }}</p></td>
                                    <td class="text-center"><span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y') }}</span></td>
                                    <td class="text-center">
                                        <a href="#" class="mx-3" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                            <i class="fas fa-user-edit text-secondary"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <span type="button" class="text-secondary btn-delete m-0 p-0" data-id="{{ $user->id }}">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editUserForm-{{ $user->id }}" action="{{ route('users.update', $user->id) }}" method="POST" onsubmit="return validateEditPassword('{{ $user->id }}')">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="username">Username</label>
                                                        <input type="text" class="form-control" name="username" value="{{ $user->username }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hp">Phone</label>
                                                        <input type="number" class="form-control" name="hp" value="{{ $user->hp }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password">New Password (optional)</label>
                                                        <input type="password" class="form-control" name="password" id="editPassword-{{ $user->id }}" placeholder="Leave blank to keep current password">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="password_confirmation">Confirm New Password</label>
                                                        <input type="password" class="form-control" name="password_confirmation" id="editPasswordConfirmation-{{ $user->id }}" placeholder="Re-enter the new password (optional)">
                                                        <span id="editPasswordError-{{ $user->id }}" class="text-danger"></span> <!-- Error message -->
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="position">Position</label>
                                                        <select class="form-control" name="position" id="editPositionSelect-{{ $user->id }}" required>
                                                            <option value="Director" {{ $user->position == 'Director' ? 'selected' : '' }}>Director</option>
                                                            <option value="Admin Stock" {{ $user->position == 'Admin Stock' ? 'selected' : '' }}>Admin Stock</option>
                                                            <option value="Marketing" {{ $user->position == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                            <option value="Finance" {{ $user->position == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                            <option value="Investor" {{ $user->position == 'Investor' ? 'selected' : '' }}>Investor</option>
                                                            <option value="Operational" {{ $user->position == 'Operational' ? 'selected' : '' }}>Operational</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group editSubPositionGroup" data-id="{{ $user->id }}" style="{{ $user->position == 'Director' ? '' : 'display:none' }}">
                                                        <label for="sub_position">Sub Position</label>
                                                        <select class="form-control" name="access_level">
                                                            <option value="superadmin" {{ $user->access_level == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                                            <option value="view only" {{ $user->access_level == 'view only' ? 'selected' : '' }}>View Only</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="user_status">Status</label>
                                                        <select name="user_status" class="form-control">
                                                            <option value="active" {{ $user->user_status == 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $user->user_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
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
</div>

<!-- Create Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createUserForm" action="{{ route('users.store') }}" method="POST" onsubmit="return validateCreatePassword()">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="hp">Phone</label>
                        <input type="number" class="form-control" name="hp" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="createPassword" placeholder="Enter a strong password (min. 8 characters)" minlength="8" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="createPasswordConfirmation" placeholder="Re-enter the password" minlength="8" required>
                        <span id="createPasswordError" class="text-danger"></span> <!-- Error message -->
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        <select class="form-control" id="createPositionSelect" name="position" required>
                            <option value="" disabled selected>Select Position</option>
                            <option value="Director">Director</option>
                            <option value="Admin Stock">Admin Stock</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Finance">Finance</option>
                            <option value="Investor">Investor</option>
                            <option value="Operational">Operational</option>
                        </select>
                    </div>
                    <div class="form-group" id="createSubPositionGroup" style="display: none;">
                        <label for="sub_position">Sub Position</label>
                        <select class="form-control" name="access_level" id="createSubPositionSelect">
                            <option value="superadmin">Superadmin</option>
                            <option value="view only">View Only</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Penghapusan -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Script Logic -->
<script>

function validateCreatePassword() {
    var password = document.getElementById('createPassword').value;
    var confirmPassword = document.getElementById('createPasswordConfirmation').value;
    var errorMessage = document.getElementById('createPasswordError');

    if (password !== confirmPassword) {
        errorMessage.textContent = 'Passwords do not match';
        return false; // Prevent form submission
    }
    errorMessage.textContent = ''; // Clear the error message
    return true;
}

// Validation for Edit User Modal
function validateEditPassword(userId) {
    var password = document.getElementById('editPassword-' + userId).value;
    var confirmPassword = document.getElementById('editPasswordConfirmation-' + userId).value;
    var errorMessage = document.getElementById('editPasswordError-' + userId);

    if (password !== confirmPassword) {
        errorMessage.textContent = 'Passwords do not match';
        return false; // Prevent form submission
    }
    errorMessage.textContent = ''; // Clear the error message
    return true;
}
    document.addEventListener('DOMContentLoaded', function () {
    let userIdToDelete;
    let formToSubmit;

    // Event listener for delete button
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            userIdToDelete = this.getAttribute('data-id');
            formToSubmit = this.closest('form');
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            deleteModal.show();
        });
    });

    // Event listener for confirm delete button in modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        formToSubmit.submit();
    });

    // Show/hide sub-position when "Director" is selected in the Position dropdown
    function toggleSubPosition(selectElement, subPositionGroup) {
        if (selectElement.value.toLowerCase() === 'director') {
            subPositionGroup.style.display = 'block';
        } else {
            subPositionGroup.style.display = 'none';
        }

        selectElement.addEventListener('change', function () {
            if (this.value.toLowerCase() === 'director') {
                subPositionGroup.style.display = 'block';
            } else {
                subPositionGroup.style.display = 'none';
            }
        });
    }

    // Loop through all users to apply toggleSubPosition for edit modals
    document.querySelectorAll('.positionSelect').forEach(function(selectElement) {
        const userId = selectElement.getAttribute('data-id');
        const subPositionGroup = document.querySelector(`.editSubPositionGroup[data-id="${userId}"]`);
        toggleSubPosition(selectElement, subPositionGroup);

        // Initialize the visibility of subPositionGroup based on current value
        if (selectElement.value.toLowerCase() === 'director') {
            subPositionGroup.style.display = 'block';
        } else {
            subPositionGroup.style.display = 'none';
        }
    });

    // Call the toggle function for the create form
    toggleSubPosition(document.getElementById('createPositionSelect'), document.getElementById('createSubPositionGroup'));
});

</script>

<style>
    .form-group input {
    border-radius: 5px;
    padding: 10px;
    font-size: 14px;
}

.form-group input::placeholder {
    color: #6c757d;
    font-style: italic;
}

.form-group input:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

</style>

@endsection
