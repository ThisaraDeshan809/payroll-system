@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/employees') }}">Employees</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-primary btn-lg m-1" id="btn_add_employee"><i
                        class="fa-solid fa-user-plus"></i></button>
                <button type="button" class="btn btn-secondary btn-lg m-1" id="btn_upload_employees" data-bs-toggle="modal"
                    data-bs-target="#uploadModal"><i class="fa-solid fa-file-excel"></i> Upload Employees Excel</button>
            </div>

            <div class="col-md-12 mt-4 mb-4">
                <table class="table table-hover" style="width: 100%;" id="tbl_employees">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">EPF No</th>
                            <th style="color: #fff;">Name</th>
                            <th style="color: #fff;">Basic Salary</th>
                            <th style="color: #fff;">OT</th>
                            <th style="color: #fff;">Category</th>
                            <th style="color: #fff;">Designation</th>
                            <th style="color: #fff;">EPF</th>
                            <th style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Import Employees from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="excelUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excelFile">Upload Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xls,.xlsx"
                                required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="excelUploadForm">Upload</button>
                </div>
            </div>
        </div>
    @endsection

    @section('javascript')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                tbl_employees = $('#tbl_employees').DataTable({
                    lengthMenu: [
                        [25, 50, 100, -1],
                        ['25 ', '50 ', '100', 'All']
                    ],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('employees/get-employees') }}",
                        data: function(d) {
                            d.range = $('#reportrange').val();
                        },
                    },
                    columns: [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'epf_no',
                            name: 'epf_no'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'basic_salary',
                            name: 'basic_salary'
                        },
                        {
                            data: 'OT',
                            name: 'OT'
                        },
                        {
                            data: 'emp_category',
                            name: 'emp_category'
                        },
                        {
                            data: 'designation',
                            name: 'designation'
                        },
                        {
                            data: 'emp_epf',
                            name: 'emp_epf'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ]
                });

                $('#excelUploadForm').on('submit', function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);

                    $.ajax({
                        url: "{{ route('employee.upload') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success === true) {
                                $('#uploadModal').modal('hide');
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    @endsection
