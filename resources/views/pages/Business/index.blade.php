@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Settings</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/business') }}">Business</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#addLocationModal"
                    class="btn btn-primary btn-lg m-1" id="btn_add_business"><i class="fa-solid fa-plus"></i> Add New
                    Business</button>
            </div>

            <div class="col-md-12 mt-4 mb-4">
                <table class="table table-hover" style="width: 100%;" id="tbl_businesses">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">Business Name</th>
                            <th style="color: #fff;">Address</th>
                            <th style="color: #fff;">Email</th>
                            <th style="color: #fff;">Telephone</th>
                            <th style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">Add New Business</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="locationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="business_name" name="business_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_save_business">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">Edit Business</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBusinessForm">
                        @csrf
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business Name</label>
                            <input type="text" class="form-control" id="edit_business_name" name="business_name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit_address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="edit_telephone" name="telephone" required>
                        </div>
                        <input type="hidden" id="business_id">
                        <button type="submit" class="btn btn-primary mb-3" id="btn_edit_business">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_business_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            tbl_businesses = $('#tbl_businesses').DataTable({
                lengthMenu: [
                    [25, 50, 100, -1],
                    ['25 ', '50 ', '100', 'All']
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/business/get-businesses') }}",
                    data: function(d) {
                        d.range = $('#reportrange').val();
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'business_name',
                        name: 'business_name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'telephone',
                        name: 'telephone'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            $('#tbl_businesses').on('click', '.btn_edit_business', function(e) {
                e.preventDefault();

                let businessId = $(this).data('id');

                $.ajax({
                    url: '/business/get-business',
                    type: 'GET',
                    data: {
                        businessId: businessId,
                    },
                    success: function(response) {
                        $('#editLocationModal #edit_business_name').val(response.business_name);
                        $('#editLocationModal #edit_address').val(response.address);
                        $('#editLocationModal #edit_email').val(response.email);
                        $('#editLocationModal #edit_telephone').val(response.telephone);
                        $('#editLocationModal #business_id').val(businessId);

                        $('#editLocationModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching business data: ' + xhr.responseText);
                    },
                });
            });

            $('#editLocationModal').on('click', '#btn_edit_business', function(e) {
                e.preventDefault();

                const formData = {
                    businessId: $('#editLocationModal #business_id').val(),
                    business_name: $('#editLocationModal #edit_business_name').val(),
                    address: $('#editLocationModal #edit_address').val(),
                    email: $('#editLocationModal #edit_email').val(),
                    telephone: $('#editLocationModal #edit_telephone').val(),
                };

                $.ajax({
                    url: '/business/edit-business',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#editLocationModal').modal('hide');
                            $('#editBusinessForm')[0].reset();
                            tbl_businesses.ajax.reload();
                        } else {
                            toastr.success(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            $('#tbl_businesses').on('click','.btn_delete_business',function(e) {
                let businessId = $(this).data('id');

                $('#deleteConfirmationModal #delete_business_id').val(businessId);
            });

            $('#deleteConfirmationModal').on('click', '#confirmDeleteButton', function(e) {
                e.preventDefault();

                let businessId = $('#delete_business_id').val();

                $.ajax({
                    url: '/business/delete-business',
                    type: 'POST',
                    data: {
                        businessId: businessId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success === true) {
                            toastr.success(response.message);
                            $('#deleteConfirmationModal').modal('hide');
                            tbl_businesses.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching business data: ' + xhr.responseText);
                    },
                });
            });

            $('#btn_save_business').click(function(e) {
                e.preventDefault();

                const formData = {
                    business_name: $('#business_name').val(),
                    address: $('#address').val(),
                    email: $('#email').val(),
                    telephone: $('#telephone').val(),
                };

                $.ajax({
                    url: '/business/save-business',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#addLocationModal').modal('hide');
                            $('#locationForm')[0].reset();
                            tbl_businesses.ajax.reload();
                        } else {
                            toastr.success(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
