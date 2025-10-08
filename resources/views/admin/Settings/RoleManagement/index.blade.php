@extends('admin.layouts.layout')

@section('title', 'Roles')
@section('admin')
@section('pagetitle', 'Role')

<div class="container mt-5">
    <!-- Add Role Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-person-badge me-2"></i>Add Role</h4>
                </div>
                <div class="card-body mt-3">
                    <form id="roleForm">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter role name">
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                        <i class="fas fa-plus-circle me-2"></i>Add Role
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles List Card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Roles List</h4>
                </div>
                <div class="card-body mt-3">
                    <table class="table table-bordered table-striped rolesList nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_role_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter role name">
                        <div class="invalid-feedback" id="edit_nameError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateRoleBtn">Update Role</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    const rolesUrl = "{{ route('admin.roles.index') }}";
    const storeRoleUrl = "{{ route('admin.roles.store') }}";
    const editRoleUrl = "{{ route('admin.roles.edit', ':id') }}";
    const updateRoleUrl = "{{ route('admin.roles.update', ':id') }}";
    const deleteRoleUrl = "{{ route('admin.roles.destroy', ':id') }}";
</script>
