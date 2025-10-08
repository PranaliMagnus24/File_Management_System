@extends('admin.layouts.layout')

@section('title', 'Departments')
@section('admin')
@section('pagetitle', 'Department')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Add Department</h4>
                </div>
                <div class="card-body mt-3">
                    <form id="departmentForm">
                        @csrf
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter department name">
                                    <div class="invalid-feedback" id="nameError"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                        <i class="fas fa-plus-circle me-2"></i>Add Department
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Department List Card -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Department List</h4>
                </div>
                <div class="card-body mt-3">
                    <table class="table table-bordered table-striped DepartmentList nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDepartmentForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_id" name="edit_id">
                    <div class="mb-3">
                        <label for="modal_name" class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_name" name="name" placeholder="Enter department name">
                        <div class="invalid-feedback" id="modal_nameError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        <i class="fas fa-save me-2"></i>Update Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const departmentsListUrl = "{{ route('admin.departments.index') }}";
const storeDepartmentUrl = "{{ route('admin.departments.store') }}";
const editDepartmentUrl = "{{ route('admin.departments.edit', ':id') }}";
const updateDepartmentUrl = "{{ route('admin.departments.update', ':id') }}";
const deleteDepartmentUrl = "{{ route('admin.departments.destroy', ':id') }}";

//////Department Table List
$(document).ready(function() {
if ($('.DepartmentList').length) {
    // initialize DataTable
    var departmentTable = $('.DepartmentList').DataTable({
        serverSide: true,
        processing: true,
        responsive: true,
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
                    { extend: 'csv', className: 'dropdown-item', exportOptions: { columns: [0,1,2] } },
                    { extend: 'excel', className: 'dropdown-item', exportOptions: { columns: [0,1,2] } },
                    { extend: 'pdf', className: 'dropdown-item', exportOptions: { columns: [0,1,2] } },
                    { extend: 'print', className: 'dropdown-item', exportOptions: { columns: [0,1,2] } }
                ]
            }
        ],
        ajax: {
            url: departmentsListUrl
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // AJAX Store Department
    $('#departmentForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: storeDepartmentUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            beforeSend: function() {
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#departmentForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', response.message || 'Department created successfully.', 'success');

                    // Reload DataTable
                    departmentTable.ajax.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors) {
                    // Show validation errors
                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to create department.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add Department');
            }
        });
    });

    // Edit department - Modal open karein
    $(document).on('click', '.edit-department', function() {
        var id = $(this).data('id');

        // Clear previous errors
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');

        $.ajax({
            url: editDepartmentUrl.replace(':id', id),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Modal mein data populate karein
                    $('#modal_edit_id').val(response.department.id);
                    $('#modal_name').val(response.department.name);

                    // Modal show karein
                    $('#editDepartmentModal').modal('show');
                } else {
                    showAlert('Error!', response.message || 'Failed to load department.', 'error');
                }
            },
            error: function() {
                showAlert('Error!', 'Failed to load department data.', 'error');
            }
        });
    });

    // Update department via modal form
    $('#editDepartmentForm').on('submit', function(e) {
        e.preventDefault();

        var editId = $('#modal_edit_id').val();
        var updateBtn = $('#updateBtn');

        // Clear errors
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');

        var formData = new FormData(this);

        $.ajax({
            url: updateDepartmentUrl.replace(':id', editId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-HTTP-Method-Override': 'PUT'
            },
            beforeSend: function() {
                updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
            },
            success: function(response) {
                if (response.success) {
                    // Modal band karein
                    $('#editDepartmentModal').modal('hide');

                    // Reset form
                    $('#editDepartmentForm')[0].reset();

                    showAlert('Success!', response.message || 'Department updated successfully.', 'success');

                    // Reload DataTable
                    departmentTable.ajax.reload();
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        $(`#modal_${key}`).addClass('is-invalid');
                        $(`#modal_${key}Error`).text(value[0]);
                    });
                } else {
                    showAlert('Error!', 'Failed to update department.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Update Department');
            }
        });
    });

    // Delete department with SweetAlert
    $(document).on('click', '.delete-department', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to delete department "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteDepartmentUrl.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Success!', response.message || 'Department deleted successfully.', 'success');
                            departmentTable.ajax.reload();
                        } else {
                            showAlert('Error!', response.message || 'Failed to delete department.', 'error');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.message) {
                            showAlert('Error!', xhr.responseJSON.message, 'error');
                        } else {
                            showAlert('Error!', 'Failed to delete department.', 'error');
                        }
                    }
                });
            }
        });
    });

    // Modal band hone par form reset karein
    $('#editDepartmentModal').on('hidden.bs.modal', function () {
        $('#editDepartmentForm')[0].reset();
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');
    });
}

// Alert function (agar aapke paas nahi hai to ye use karein)
function showAlert(title, message, type) {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
}

});
</script>
