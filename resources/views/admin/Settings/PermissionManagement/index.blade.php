@extends('admin.layouts.layout')

@section('title', 'Permissions')
@section('admin')
@section('pagetitle', 'Permission')

<div class="container mt-5">
    <!-- Add Permission Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Add Permission</h4>
                </div>
                <div class="card-body mt-3">
                    <form id="permissionForm">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter permission name">
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                        <i class="fas fa-plus-circle me-2"></i>Add Permission
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions List Card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Permissions List</h4>
                </div>
                <div class="card-body mt-3">
                    <table class="table table-bordered table-striped permissionsList nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Permission Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPermissionModalLabel">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_permission_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter permission name">
                        <div class="invalid-feedback" id="edit_nameError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updatePermissionBtn">Update Permission</button>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    const permissionsUrl = "{{ route('admin.permissions.index') }}";
    const storePermissionUrl = "{{ route('admin.permissions.store') }}";
    const editPermissionUrl = "{{ route('admin.permissions.edit', ':id') }}";
    const updatePermissionUrl = "{{ route('admin.permissions.update', ':id') }}";
    const deletePermissionUrl = "{{ route('admin.permissions.destroy', ':id') }}";
</script>
