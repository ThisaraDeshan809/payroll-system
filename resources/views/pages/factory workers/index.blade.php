@extends('layouts.default')

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/employees') }}">Factory Workers</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 mt-4 mb-4">
                <h2><span id="tbl_head">Attendance Details</span></h2>
                <table class="table table-hover" style="width: 100%;" id="tbl_factory_workers">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">EMP ID</th>
                            <th style="color: #fff;">Date</th>
                            <th style="color: #fff;">Name</th>
                            <th style="color: #fff;">Department</th>
                            <th style="color: #fff;">Shift</th>
                            <th style="color: #fff;">Check In</th>
                            <th style="color: #fff;">Check Out</th>
                            <th style="color: #fff;">Duration</th>
                            <th style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendanceUploadModal" tabindex="-1" aria-labelledby="attendanceUploadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Import Employees Attendance Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="excelUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excelFile">Upload Excel File</label>
                            <input type="file" class="form-control" id="csv_file" name="csv_file"
                                accept=".xls,.xlsx,.csv" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="excelUploadForm">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#excelUploadForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('attendance.upload') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            $('#attendanceUploadModal').modal('hide');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    }
                });
            });

            // Reinitialize the DataTable
            tbl_factory_workers = $('#tbl_factory_workers').DataTable({
                lengthMenu: [
                    [25, 50, 100, -1],
                    ['25 ', '50 ', '100', 'All']
                ],
                processing: true,
                ajax: {
                    url: "{{ url('employees/attendance/get-incomplete-attendances') }}",
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'emp_id',
                        name: 'emp_id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'shift',
                        name: 'shift'
                    },
                    {
                        data: 'check_in',
                        name: 'check_in'
                    },
                    {
                        data: 'check_out',
                        name: 'check_out'
                    },
                    {
                        data: 'duration',
                        name: 'duration'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                order: [
                    [0, 'desc']
                ], // Default sorting by ID in descending order
            });
        });
    </script>
@endsection
