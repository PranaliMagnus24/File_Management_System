@extends('admin.layouts.layout')

@section('title', 'Roles')
@section('admin')
@section('pagetitle', 'Role')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add Role</h4>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('admin.roles.store') }}" method="POST" id="roleForm">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter role name" value="{{ old('name') }}">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Roles List</h4>
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
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

<div id="editRoleModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editRoleForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editRoleId" />
            <div class="mb-3">
                <label for="editRoleName" class="form-label">Role Name</label>
                <input type="text" class="form-control" id="editRoleName" name="name" placeholder="Enter role name" required>
                <div class="invalid-feedback" id="editNameError"></div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="saveRoleBtn" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

@endsection
<script>
    const rolesUrl = "{{ route('admin.roles.index') }}";
</script>
