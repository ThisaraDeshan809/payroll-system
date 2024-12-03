@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Settings</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/locations') }}">Locations</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#addLocationModal"
                    class="btn btn-primary btn-lg m-1" id="btn_add_location"><i class="fa-solid fa-plus"></i> Add New
                    Location</button>
            </div>

            <div class="col-md-12 mt-4 mb-4">
                <table class="table table-hover" style="width: 100%;" id="tbl_locations">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">Location Name</th>
                            <th style="color: #fff;">Business</th>
                            <th style="color: #fff;">Address</th>
                            <th style="color: #fff;">Telephone No.</th>
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
                    <h5 class="modal-title" id="addLocationModalLabel">Add New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="locationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="location_name" class="form-label">Location Name</label>
                            <input type="text" class="form-control" id="location_name" name="location_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business</label>
                            <select class="form-control" name="business_id" id="business_id">
                                <option value="">Please Select Business</option>
                                @foreach ($businesses as $business)
                                    <option value="{{$business->id}}">{{$business->business_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_save_location">Save</button>
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
                    <h5 class="modal-title" id="addLocationModalLabel">Edit Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLocationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="location_name" class="form-label">Location Name</label>
                            <input type="text" class="form-control" id="edit_location_name" name="edit_location_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="business_name" class="form-label">Business</label>
                            <select class="form-control" name="edit_business_id" id="edit_business_id">
                                <option value="">Please Select Business</option>
                                @foreach ($businesses as $business)
                                    <option value="{{$business->id}}">{{$business->business_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="edit_address" name="address" required>
                        </div>
                        <div class="mb-3">
                            <label for="telephone" class="form-label">Telephone</label>
                            <input type="tel" class="form-control" id="edit_telephone" name="telephone" required>
                        </div>
                        <input type="hidden" id="location_id">
                        <button type="submit" class="btn btn-primary mb-3" id="btn_edit_location">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this location? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_location_id">
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
            tbl_locations = $('#tbl_locations').DataTable({
                lengthMenu: [
                    [25, 50, 100, -1],
                    ['25 ', '50 ', '100', 'All']
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/locations/get-locations') }}",
                    data: function(d) {
                        d.range = $('#reportrange').val();
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'location_name',
                        name: 'location_name'
                    },
                    {
                        data: 'business',
                        name: 'business'
                    },
                    {
                        data: 'address',
                        name: 'address'
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

            $('#btn_save_location').click(function(e) {
                e.preventDefault();

                const formData = {
                    location_name: $('#location_name').val(),
                    business_id: $('#business_id').val(),
                    address: $('#address').val(),
                    email: $('#email').val(),
                    telephone: $('#telephone').val(),
                };

                $.ajax({
                    url: '/locations/save-location',
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
                            tbl_locations.ajax.reload();
                        } else {
                            toastr.success(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            $('#tbl_locations').on('click', '.btn_edit_location', function(e) {
                e.preventDefault();

                let locationId = $(this).data('id');

                $.ajax({
                    url: '/locations/get-location',
                    type: 'GET',
                    data: {
                        locationId: locationId,
                    },
                    success: function(response) {
                        $('#editLocationModal #edit_location_name').val(response.location_name);
                        $('#editLocationModal #edit_address').val(response.address);
                        $('#editLocationModal #edit_telephone').val(response.telephone);
                        $('#editLocationModal #location_id').val(locationId);

                        $('#editLocationModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching business data: ' + xhr.responseText);
                    },
                });
            });

            $('#editLocationModal').on('click', '#btn_edit_location', function(e) {
                e.preventDefault();

                const formData = {
                    location_id: $('#editLocationModal #location_id').val(),
                    location_name: $('#editLocationModal #edit_location_name').val(),
                    business_id: $('#editLocationModal #edit_business_id').val(),
                    address: $('#editLocationModal #edit_address').val(),
                    telephone: $('#editLocationModal #edit_telephone').val(),
                };

                $.ajax({
                    url: '/locations/edit-location',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#editLocationModal').modal('hide');
                            $('#editLocationForm')[0].reset();
                            tbl_locations.ajax.reload();
                        } else {
                            toastr.success(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            $('#tbl_locations').on('click','.btn_delete_location',function(e) {
                let location_id = $(this).data('id');

                $('#deleteConfirmationModal #delete_location_id').val(location_id);
            });

            $('#deleteConfirmationModal').on('click', '#confirmDeleteButton', function(e) {
                e.preventDefault();

                let location_id = $('#delete_location_id').val();

                $.ajax({
                    url: '/locations/delete-location',
                    type: 'POST',
                    data: {
                        location_id: location_id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success === true) {
                            toastr.success(response.message);
                            $('#deleteConfirmationModal').modal('hide');
                            tbl_locations.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching business data: ' + xhr.responseText);
                    },
                });
            });
        });
    </script>
@endsection
