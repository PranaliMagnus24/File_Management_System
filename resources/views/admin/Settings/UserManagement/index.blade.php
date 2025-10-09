@extends('admin.layouts.layout')

@section('title', 'Users')
@section('admin')
@section('pagetitle', 'User')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add User</h4>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter user name" value="{{ old('name') }}">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">User Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter user email" value="{{ old('email') }}">
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password">
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="roles" class="form-label">Roles</label>
                                <select name="roles[]" id="roles" class="form-control" multiple>
                                    <option value="" disabled selected>-- Select --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="rolesError"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter phone number" value="{{ old('phone') }}">
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="department_id" class="form-label">Department</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="" selected>-- Select --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="department_idError"></div>
                            </div>

                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary w-10" id="submitBtn">
                                <i class="fas fa-plus-circle me-2"></i>Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Users List</h4>
                </div>
                <div class="card-body mt-3">
                    <table class="table table-bordered table-striped userList nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
        <th>Department</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name"
                                placeholder="Enter user name">
                            <div class="invalid-feedback" id="edit_nameError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label">User Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email"
                                placeholder="Enter user email">
                            <div class="invalid-feedback" id="edit_emailError"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password"
                                placeholder="Enter new password (leave blank to keep current)">
                            <small class="text-muted">Leave blank to keep current password</small>
                            <div class="invalid-feedback" id="edit_passwordError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_roles" class="form-label">Roles</label>
                            <select name="roles[]" id="edit_roles" class="form-control" multiple>
                                <option value="" disabled>-- Select --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="edit_rolesError"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
    <div class="col-md-6">
        <label for="edit_phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="edit_phone" name="phone"
            placeholder="Enter phone number">
        <div class="invalid-feedback" id="edit_phoneError"></div>
    </div>
    <div class="col-md-6">
        <label for="edit_department_id" class="form-label">Department</label>
        <select name="department_id" id="edit_department_id" class="form-control">
            <option value="" disabled>-- Select --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="edit_department_idError"></div>
    </div>
</div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateUserBtn">Update User</button>
            </div>
        </div>
    </div>
</div>


@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const userUrl = "{{ route('admin.users.index') }}";
    const storeUserUrl = "{{ route('admin.users.store') }}";
    const editUserUrl = "{{ route('admin.users.edit', ':id') }}";
    const updateUserUrl = "{{ route('admin.users.update', ':id') }}";
    const deleteUserUrl = "{{ route('admin.users.delete', ':id') }}";
</script>
