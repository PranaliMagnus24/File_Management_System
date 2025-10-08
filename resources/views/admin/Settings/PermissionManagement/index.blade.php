@extends('admin.layouts.layout')

@section('title', 'Permissions')
@section('admin')
@section('pagetitle', 'Permission')

<div class="container mt-5">
    <!-- Add Permission Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Add New Permission</h5>
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

    <!-- Permissions Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Permissions List</h5>
            <span class="badge bg-primary" id="permissionCount">0 Permissions</span>
        </div>
        <div class="card-body mt-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="permissionsTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="60" class="text-center text-dark">#</th>
                            <th class="text-dark">Permission Name</th>
                            <th width="150" class="text-center text-dark">Created At</th>
                            <th width="120" class="text-center text-dark">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="permissionsTableBody">
                        <tr id="noDataRow">
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No permissions found. Add your first permission above.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editPermissionForm">
                    @csrf
                    <input type="hidden" id="edit_permission_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name">
                        <div class="invalid-feedback" id="editNameError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updatePermissionBtn">Update Permission</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Load permissions on page load
    loadPermissions();

    // Add new permission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault();
        addPermission();
    });

    // Update permission
    $('#updatePermissionBtn').on('click', function() {
        updatePermission();
    });

    function loadPermissions() {
        $.ajax({
            url: '{{ route("admin.permissions.index") }}',
            type: 'GET',
            success: function(response) {
                $('#permissionsTableBody').html('');

                if (response.permissions && response.permissions.length > 0) {
                    response.permissions.forEach(function(permission, index) {
                        const row = `
                            <tr id="permissionRow_${permission.id}">
                                <td class="text-center">${index + 1}</td>
                                <td>${permission.name}</td>
                                <td class="text-center">${new Date(permission.created_at).toLocaleDateString()}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${permission.id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${permission.id}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        $('#permissionsTableBody').append(row);
                    });
                    $('#permissionCount').text(response.permissions.length + ' Permissions');
                } else {
                    $('#permissionsTableBody').html(`
                        <tr id="noDataRow">
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No permissions found. Add your first permission above.</p>
                            </td>
                        </tr>
                    `);
                    $('#permissionCount').text('0 Permissions');
                }
            },
            error: function(xhr) {
                console.error('Error loading permissions:', xhr);
                showAlert('Error!', 'Failed to load permissions.', 'error');
            }
        });
    }

    function addPermission() {
        const formData = new FormData($('#permissionForm')[0]);
        const submitBtn = $('#submitBtn');

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');

        $.ajax({
            url: '{{ route("admin.permissions.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#permissionForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'Permission added successfully.', 'success');

                    // Reload permissions
                    loadPermissions();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show new errors
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to add permission.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add Permission');
            }
        });
    }

    // Edit permission - Load data for editing
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');

        // Show loading state
        $('#editPermissionModal').modal('show');
        $('#edit_name').val('Loading...');
        $('#updatePermissionBtn').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.permissions.edit", "") }}/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#edit_permission_id').val(response.permission.id);
                    $('#edit_name').val(response.permission.name);
                    $('#editNameError').text('');
                    $('#edit_name').removeClass('is-invalid');
                    $('#updatePermissionBtn').prop('disabled', false);
                } else {
                    $('#editPermissionModal').modal('hide');
                    showAlert('Error!', 'Failed to load permission data.', 'error');
                }
            },
            error: function(xhr) {
                $('#editPermissionModal').modal('hide');
                showAlert('Error!', 'Failed to load permission data.', 'error');
            }
        });
    });

    function updatePermission() {
        const formData = new FormData($('#editPermissionForm')[0]);
        const updateBtn = $('#updatePermissionBtn');

        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');

        $.ajax({
            url: '{{ route("admin.permissions.update") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editPermissionModal').modal('hide');
                    showAlert('Success!', 'Permission updated successfully.', 'success');
                    loadPermissions();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show new errors
                    $.each(errors, function(key, value) {
                        $(`#edit_${key}`).addClass('is-invalid');
                        $(`#edit${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to update permission.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('Update Permission');
            }
        });
    }

    // Delete permission
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const permissionName = $(this).closest('tr').find('td:eq(1)').text();

        if (confirm(`Are you sure you want to delete "${permissionName}"?`)) {
            $.ajax({
                url: '{{ route("admin.permissions.destroy") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('Success!', 'Permission deleted successfully.', 'success');
                        loadPermissions();
                    } else {
                        showAlert('Error!', 'Failed to delete permission.', 'error');
                    }
                },
                error: function(xhr) {
                    showAlert('Error!', 'Failed to delete permission.', 'error');
                }
            });
        }
    });

    function showAlert(title, message, icon) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            alert(`${title} ${message}`);
        }
    }
});
</script>

<style>
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection
