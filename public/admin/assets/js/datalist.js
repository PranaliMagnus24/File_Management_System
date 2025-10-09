$(document).ready(function() {
    ////Roles Table List
// Make sure showAlert function is available (same as users)
function showAlert(title, message, icon = 'success') {
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
        alert(`${title}: ${message}`);
    }
}

if ($('.rolesList').length) {
    // Initialize DataTable
    var roleTable = $('.rolesList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        language: {
            lengthMenu: '<select class="form-select">'+
                            '<option value="10">10</option>'+
                            '<option value="25">25</option>'+
                            '<option value="50">50</option>'+
                            '<option value="100">100</option>'+
                        '</select>'
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i>',
                className: 'btn btn-light dropdown-toggle',
                buttons: [
                    {
                        extend: 'csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    }
                ]
            }
        ],
        ajax: {
            url: rolesUrl
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // AJAX Store Role
    $('#roleForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: storeRoleUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#roleForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'Role created successfully.', 'success');

                    // Reload DataTable
                    roleTable.ajax.reload();
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
                    showAlert('Error!', 'Failed to create role.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add Role');
            }
        });
    });

    // Edit Role Modal
    $(document).on('click', '.edit-role', function() {
        var roleId = $(this).data('id');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Fetch role data
        $.ajax({
            url: editRoleUrl.replace(':id', roleId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Populate form fields
                    $('#edit_role_id').val(response.data.id);
                    $('#edit_name').val(response.data.name);

                    // Show modal
                    $('#editRoleModal').modal('show');
                }
            },
            error: function(xhr) {
                showAlert('Error!', 'Failed to load role data.', 'error');
            }
        });
    });

    // Update Role
    $('#updateRoleBtn').click(function() {
        var roleId = $('#edit_role_id').val();
        var formData = new FormData($('#editRoleForm')[0]);
        var updateBtn = $('#updateRoleBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: updateRoleUrl.replace(':id', roleId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                updateBtn.prop('disabled', true).html('Updating...');
            },
            success: function(response) {
                if (response.success) {
                    // Hide modal
                    $('#editRoleModal').modal('hide');

                    // Reset form
                    $('#editRoleForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'Role updated successfully.', 'success');

                    // Reload DataTable
                    roleTable.ajax.reload();
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
                        $(`#edit_${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to update role.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('Update Role');
            }
        });
    });

    // Delete Role Confirmation
    $(document).on('click', '.delete-role', function() {
        var roleId = $(this).data('id');
        var roleName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to delete role "${roleName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteRole(roleId);
            }
        });
    });

    function deleteRole(roleId) {
        $.ajax({
            url: deleteRoleUrl.replace(':id', roleId),
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('Success!', 'Role deleted successfully.', 'success');

                    // Reload DataTable
                    roleTable.ajax.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    showAlert('Error!', xhr.responseJSON.error, 'error');
                } else {
                    showAlert('Error!', 'Failed to delete role.', 'error');
                }
            }
        });
    }

    // Reset forms when modals are closed
    $('#editRoleModal').on('hidden.bs.modal', function() {
        $('#editRoleForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
}
    ///User Table List
   // Add this showAlert function at the top
function showAlert(title, message, icon = 'success') {
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
        // Fallback to basic alert if SweetAlert is not available
        alert(`${title}: ${message}`);
    }
}

if ($('.userList').length) {
    // Initialize DataTable
    var userTable = $('.userList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        language: {
            lengthMenu: '<select class="form-select">'+
                            '<option value="10">10</option>'+
                            '<option value="25">25</option>'+
                            '<option value="50">50</option>'+
                            '<option value="100">100</option>'+
                        '</select>'
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i>',
                className: 'btn btn-light dropdown-toggle',
                buttons: [
                    {
                        extend: 'csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ]
            }
        ],
        ajax: {
            url: userUrl
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
        { data: 'department', name: 'department' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // AJAX Store User
    $('#userForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.error-text').text('');

        $.ajax({
            url: storeUserUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#userForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'User created successfully.', 'success');

                    // Reload DataTable
                    userTable.ajax.reload();
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
                    showAlert('Error!', 'Failed to create user.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add User');
            }
        });
    });

    // Edit User Modal
    $(document).on('click', '.edit-user', function() {
        var userId = $(this).data('id');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.error-text').text('');

        // Fetch user data
        $.ajax({
            url: editUserUrl.replace(':id', userId),
            type: 'GET',
           success: function(response) {
    // Populate form fields
    $('#edit_user_id').val(response.user.id);
    $('#edit_name').val(response.user.name);
    $('#edit_email').val(response.user.email);
    $('#edit_phone').val(response.user.phone ?? '');
    $('#edit_department_id').val(response.user.department_id ?? '');

    // Set selected roles
    $('#edit_roles').val(response.userRoles);

    // Show modal
    $('#editUserModal').modal('show');
},

            error: function(xhr) {
                showAlert('Error!', 'Failed to load user data.', 'error');
            }
        });
    });

    // Update User
    $('#updateUserBtn').click(function() {
        var userId = $('#edit_user_id').val();
        var formData = new FormData($('#editUserForm')[0]);
        var updateBtn = $('#updateUserBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.error-text').text('');

        $.ajax({
            url: updateUserUrl.replace(':id', userId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                updateBtn.prop('disabled', true).html('Updating...');
            },
            success: function(response) {
                if (response.success) {
                    // Hide modal
                    $('#editUserModal').modal('hide');

                    // Reset form
                    $('#editUserForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'User updated successfully.', 'success');

                    // Reload DataTable
                    userTable.ajax.reload();
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
    var inputId = key;
    // for mapping to edit input ids: e.g. department_id => edit_department_id
    if ($('#edit_' + key).length) {
        $(`#edit_${key}`).addClass('is-invalid');
        $(`#edit_${key}Error`).text(value[0]);
    } else if ($(`#${key}`).length) {
        $(`#${key}`).addClass('is-invalid');
        $(`#${key}Error`).text(value[0]);
    }
});

                } else {
                    showAlert('Error!', 'Failed to update user.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('Update User');
            }
        });
    });

    // Delete User Confirmation
    var deleteUserId;
    var deleteUserName;

    $(document).on('click', '.delete-user', function() {
        deleteUserId = $(this).data('id');
        deleteUserName = $(this).data('name');

        // Use SweetAlert for confirmation instead of Bootstrap modal
        Swal.fire({
            title: 'Are you sure?',
            text: `You want to delete user "${deleteUserName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteUser();
            }
        });
    });

    function deleteUser() {
        $.ajax({
            url: deleteUserUrl.replace(':id', deleteUserId),
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                // Show loading state if needed
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('Success!', 'User deleted successfully.', 'success');

                    // Reload DataTable
                    userTable.ajax.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    showAlert('Error!', xhr.responseJSON.error, 'error');
                } else {
                    showAlert('Error!', 'Failed to delete user.', 'error');
                }
            }
        });
    }

    // Reset forms when modals are closed
    $('#editUserModal').on('hidden.bs.modal', function() {
        $('#editUserForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
}

////Permissioms Table List
// Make sure showAlert function is available
function showAlert(title, message, icon = 'success') {
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
        alert(`${title}: ${message}`);
    }
}

if ($('.permissionsList').length) {
    // Initialize DataTable
    var permissionTable = $('.permissionsList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 d-flex justify-content-end align-items-center gap-2'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        language: {
            lengthMenu: '<select class="form-select">'+
                            '<option value="10">10</option>'+
                            '<option value="25">25</option>'+
                            '<option value="50">50</option>'+
                            '<option value="100">100</option>'+
                        '</select>'
        },
        buttons: [
            {
                extend: 'collection',
                text: '<i class="bi bi-download"></i>',
                className: 'btn btn-light dropdown-toggle',
                buttons: [
                    {
                        extend: 'csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    },
                    {
                        extend: 'print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [0, 1, 2] }
                    }
                ]
            }
        ],
        ajax: {
            url: permissionsUrl
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // AJAX Store Permission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: storePermissionUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#permissionForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'Permission created successfully.', 'success');

                    // Reload DataTable
                    permissionTable.ajax.reload();
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
                    showAlert('Error!', 'Failed to create permission.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add Permission');
            }
        });
    });

    // Edit Permission Modal
    $(document).on('click', '.edit-permission', function() {
        var permissionId = $(this).data('id');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Fetch permission data
        $.ajax({
            url: editPermissionUrl.replace(':id', permissionId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Populate form fields
                    $('#edit_permission_id').val(response.permission.id);
                    $('#edit_name').val(response.permission.name);

                    // Show modal
                    $('#editPermissionModal').modal('show');
                }
            },
            error: function(xhr) {
                showAlert('Error!', 'Failed to load permission data.', 'error');
            }
        });
    });

    // Update Permission
    $('#updatePermissionBtn').click(function() {
        var permissionId = $('#edit_permission_id').val();
        var formData = new FormData($('#editPermissionForm')[0]);
        var updateBtn = $('#updatePermissionBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: updatePermissionUrl.replace(':id', permissionId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                updateBtn.prop('disabled', true).html('Updating...');
            },
            success: function(response) {
                if (response.success) {
                    // Hide modal
                    $('#editPermissionModal').modal('hide');

                    // Reset form
                    $('#editPermissionForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', 'Permission updated successfully.', 'success');

                    // Reload DataTable
                    permissionTable.ajax.reload();
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
                        $(`#edit_${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to update permission.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('Update Permission');
            }
        });
    });

    // Delete Permission Confirmation
    $(document).on('click', '.delete-permission', function() {
        var permissionId = $(this).data('id');
        var permissionName = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to delete permission "${permissionName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                deletePermission(permissionId);
            }
        });
    });

    function deletePermission(permissionId) {
        $.ajax({
            url: deletePermissionUrl.replace(':id', permissionId),
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('Success!', 'Permission deleted successfully.', 'success');

                    // Reload DataTable
                    permissionTable.ajax.reload();
                } else {
                    showAlert('Error!', response.message, 'error');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    showAlert('Error!', xhr.responseJSON.message, 'error');
                } else {
                    showAlert('Error!', 'Failed to delete permission.', 'error');
                }
            }
        });
    }

    // Reset forms when modals are closed
    $('#editPermissionModal').on('hidden.bs.modal', function() {
        $('#editPermissionForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    });
}

});
