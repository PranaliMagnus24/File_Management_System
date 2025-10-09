@extends('admin.layouts.layout')

@section('title', 'Racks')
@section('admin')
@section('pagetitle', 'Racks Management')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add Rack</h4>
                </div>
                <div class="card-body mt-3">
                    <form action="{{ route('admin.users.store') }}" method="POST" id="rackForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Racks Name</label>
                                <input type="text" class="form-control" id="rack_name" name="name"
                                    placeholder="Enter rack name" value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    placeholder="Enter location" value="{{ old('location') }}">
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-primary w-10" id="submitBtn">
                                <i class="fas fa-plus-circle me-2"></i>Add Rack
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
                    <h4 class="mb-0">Racks List</h4>
                </div>
                <div class="card-body mt-3">
                    <table class="table table-bordered table-striped racksList nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rack Name</th>
                                <th>Location</th>
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
<!-- Edit Rack Modal -->
<div class="modal fade" id="editRackModal" tabindex="-1" aria-labelledby="editRackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRackModalLabel">Edit Rack</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRackForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="modal_edit_id" name="edit_id">
                    <div class="mb-3">
                        <label for="modal_name" class="form-label">Rack Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_name" name="name" placeholder="Enter rack name">
                        <div class="invalid-feedback" id="modal_nameError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="modal_location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_location" name="location" placeholder="Enter location">
                        <div class="invalid-feedback" id="modal_locationError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        <i class="fas fa-save me-2"></i>Update Rack
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const racksListUrl = "{{ route('admin.racks.index') }}";
const storeRacktUrl = "{{ route('admin.racks.store') }}";
const editRackUrl = "{{ route('admin.racks.edit', ':id') }}";
const updateRackUrl = "{{ route('admin.racks.update', ':id') }}";
const deleteRackUrl = "{{ route('admin.racks.destroy', ':id') }}";

//////Department Table List
$(document).ready(function() {
if ($('.racksList').length) {
    // initialize DataTable
    var departmentTable = $('.racksList').DataTable({
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
            url: racksListUrl
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'location', name: 'location' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // AJAX Store Department
    $('#rackForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $.ajax({
            url: storeRacktUrl,
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
                    $('#rackForm')[0].reset();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    // Show success message
                    showAlert('Success!', response.message || 'Rack created successfully.', 'success');

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
                    showAlert('Error!', 'Failed to create rack.', 'error');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Add Rack');
            }
        });
    });

    // Edit rack - Modal open karein
    $(document).on('click', '.edit-rack', function() {
        var id = $(this).data('id');

        // Clear previous errors
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');
        $('#modal_location').removeClass('is-invalid');
        $('#modal_locationError').text('');


        $.ajax({
            url: editRackUrl.replace(':id', id),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    // Modal mein data populate karein
                    $('#modal_edit_id').val(response.rack.id);
                    $('#modal_name').val(response.rack.name);
                    $('#modal_location').val(response.rack.location);

                    // Modal show karein
                    $('#editRackModal').modal('show');
                } else {
                    showAlert('Error!', response.message || 'Failed to load rack.', 'error');
                }
            },
            error: function() {
                showAlert('Error!', 'Failed to load rack data.', 'error');
            }
        });
    });

    // Update department via modal form
    $('#editRackForm').on('submit', function(e) {
        e.preventDefault();

        var editId = $('#modal_edit_id').val();
        var updateBtn = $('#updateBtn');

        // Clear errors
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');
        $('#modal_location').removeClass('is-invalid');
        $('#modal_locationError').text('');

        var formData = new FormData(this);

        $.ajax({
            url: updateRackUrl.replace(':id', editId),
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
                    $('#editRackModal').modal('hide');

                    // Reset form
                    $('#editRackForm')[0].reset();

                    showAlert('Success!', response.message || 'Rack updated successfully.', 'success');

                    // Reload DataTable
                    rackTable.ajax.reload();
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
                    showAlert('Error!', 'Failed to update rack.', 'error');
                }
            },
            complete: function() {
                updateBtn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Update Rack');
            }
        });
    });

    // Delete rack with SweetAlert
    $(document).on('click', '.delete-rack', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Are you sure?',
            text: `You want to delete rack "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteRackUrl.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('Success!', response.message || 'Rack deleted successfully.', 'success');
                            rackTable.ajax.reload();
                        } else {
                            showAlert('Error!', response.message || 'Failed to delete rack.', 'error');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.message) {
                            showAlert('Error!', xhr.responseJSON.message, 'error');
                        } else {
                            showAlert('Error!', 'Failed to delete rack.', 'error');
                        }
                    }
                });
            }
        });
    });

    // Modal band hone par form reset karein
    $('#editRackModal').on('hidden.bs.modal', function () {
        $('#editRackForm')[0].reset();
        $('#modal_name').removeClass('is-invalid');
        $('#modal_nameError').text('');
        $('#modal_location').removeClass('is-invalid');
        $('#modal_locationError').text('');
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
