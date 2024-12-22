@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/employees') }}">Employees</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/employees/salaryAdvances') }}">Salary Advances</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-lg m-1" id="btn_add_salary_advance" data-bs-toggle="modal"
                    data-bs-target="#addSalarayAdvanceModal">
                    <i class="fa-solid fa-user-plus"></i>
                </button>
            </div>

            <div class="col-md-12 mt-4 mb-4">
                <table class="table table-hover" style="width: 100%;" id="tbl_salary_advances">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">Name</th>
                            <th style="color: #fff;">Month</th>
                            <th style="color: #fff;">Status</th>
                            <th style="color: #fff;">Amount</th>
                            <th style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSalarayAdvanceModal" tabindex="-1" aria-labelledby="addSalarayAdvanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="addSalarayAdvanceModalLabel">Add Salary Advance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="salaryAdvanceForm">
                        @csrf
                        <h2>Basic Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_id" class="form-label">Employee : </label>
                                        <select class="form-control" name="emp_id" id="emp_id" required>
                                            <option value="">Please Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="month" class="form-label">Month : </label>
                                        <input type="month" class="form-control" id="month" name="month" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Requested Amount : </label>
                                        <input type="text" class="form-control" id="amount" name="amount" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_save_salary_advance">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSalarayAdvanceModal" tabindex="-1" aria-labelledby="editSalarayAdvanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="editSalarayAdvanceModalLabel">Edit Salary Advance</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSalaryAdvanceForm">
                        @csrf
                        <h2>Basic Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_id" class="form-label">Employee : </label>
                                        <select class="form-control" name="edit_emp_id" id="edit_emp_id" required>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="month" class="form-label">Month : </label>
                                        <input type="month" class="form-control" id="edit_month" name="edit_month" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Requested Amount : </label>
                                        <input type="text" class="form-control" id="edit_amount" name="edit_amount" required>
                                    </div>
                                </div>
                                <input type="hidden" id="salary_advance_id">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_edit_salary_advance">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="markPaidConfirmationModal" tabindex="-1" aria-labelledby="markPaidConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markPaidConfirmationModalLabel">Confirm Mark As Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to Mark As Paid this Salary Advance? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="mark_salary_advance_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmMarkAsPaidButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            tbl_salary_advances = $('#tbl_salary_advances').DataTable({
                lengthMenu: [
                    [25, 50, 100, -1],
                    ['25 ', '50 ', '100', 'All']
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('employees/salary-advances/get-salary-advances') }}",
                    data: function(d) {
                        d.range = $('#reportrange').val();
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });

            $('#salaryAdvanceForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "/employees/salary-advances/save-salary-advance",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#addSalarayAdvanceModal').modal('hide');
                            tbl_salary_advances.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        toastr.error(errors);
                    },
                });
            });

            $('#tbl_salary_advances').on('click', '.btn_mark_complete', function(e) {
                let salary_advance_id = $(this).data('id');

                $('#markPaidConfirmationModal #mark_salary_advance_id').val(salary_advance_id);
            });

            $('#markPaidConfirmationModal').on('click', '#confirmMarkAsPaidButton', function(e) {
                e.preventDefault();

                let salary_advance_id = $('#mark_salary_advance_id').val();

                $.ajax({
                    url: 'salary-advances/mark-as-paid',
                    type: 'POST',
                    data: {
                        salary_advance_id: salary_advance_id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#markPaidConfirmationModal').modal('hide');
                            tbl_salary_advances.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error fetching business data: ' + xhr.responseText);
                        toastr.error(error);
                    },
                });
            });

            $('#tbl_salary_advances').on('click', '.btn_edit_salary_advance', function(e) {
                e.preventDefault();

                let salary_advance_id = $(this).data('id');

                $.ajax({
                    url: 'salary-advances/get-salary-advance',
                    type: 'GET',
                    data: {
                        salary_advance_id: salary_advance_id,
                    },
                    success: function(response) {
                        console.log(response.data.emp_category);
                        $('#editSalarayAdvanceModal #edit_emp_id').val(response.data.emp_id);
                        $('#editSalarayAdvanceModal #edit_month').val(response.data.month);
                        $('#editSalarayAdvanceModal #edit_amount').val(response.data.amount);

                        $('#editSalarayAdvanceModal #salary_advance_id').val(salary_advance_id);
                        $('#editSalarayAdvanceModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    },
                });
            });

            $('#editSalarayAdvanceModal').on('click', '#btn_edit_salary_advance', function(e) {
                e.preventDefault();

                const formData = {
                    salary_advance_id: $('#editSalarayAdvanceModal #salary_advance_id').val(),
                    emp_id: $('#editSalarayAdvanceModal #edit_emp_id').val(),
                    month: $('#editSalarayAdvanceModal #edit_month').val(),
                    amount: $('#editSalarayAdvanceModal #edit_amount').val(),
                };

                $.ajax({
                    url: 'salary-advances/edit-salary-advance',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#editSalarayAdvanceModal').modal('hide');
                            $('#editSalarayAdvanceModal')[0].reset();
                            tbl_salary_advances.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    }
                });
            });
        });
    </script>
@endsection
