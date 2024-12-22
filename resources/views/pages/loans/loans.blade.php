@extends('layouts.default')

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/employees') }}">Employees</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/employees/loans') }}">loans</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <button type="button" class="btn btn-primary btn-lg m-1" id="btn_add_loan" data-bs-toggle="modal"
                    data-bs-target="#addLoanModal">
                    <i class="fa-solid fa-user-plus"></i>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-4 mb-4">
                <h2><span id="tbl_head">Employees Loans</span></h2>
                <table class="table table-hover" style="width: 100%;" id="tbl_loans">
                    <thead>
                        <tr style="background-color: #4F75B2;">
                            <th style="color: #fff;">Id</th>
                            <th style="color: #fff;">EMP ID</th>
                            <th style="color: #fff;">Name</th>
                            <th style="color: #fff;">Amount</th>
                            <th style="color: #fff;">Monthly Amount</th>
                            <th style="color: #fff;">Installments</th>
                            <th style="color: #fff;">Installed</th>
                            <th style="color: #fff;">Guaranter 1</th>
                            <th style="color: #fff;">Guaranter 2</th>
                            <th style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="addLoanModalLabel">Add New Loan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loanForm">
                        @csrf
                        <h2>New Loan Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_id" class="form-label">Employee : </label>
                                        <select class="form-control" name="emp_id" id="emp_id">
                                            <option value="">Please Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="loan_amount" class="form-label">Loan Amount(LKR) : </label>
                                        <input type="text" class="form-control" id="loan_amount" name="loan_amount"
                                            required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="loan_installments" class="form-label">Installments : </label>
                                        <input type="text" class="form-control" id="loan_installments"
                                            name="loan_installments" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="monthly_loan_amount" class="form-label">Monthly Payment Amount(LKR) :
                                        </label>
                                        <input type="text" class="form-control" id="monthly_loan_amount"
                                            name="monthly_loan_amount" required>
                                    </div>
                                </div>
                                <div class="col-6">

                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="guaranter_1" class="form-label">Loan Guaranter 1 : </label>
                                        <select class="form-control" name="guaranter_1" id="guaranter_1">
                                            <option value="">Please Select Loan Guaranter 1 </option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="guaranter_2" class="form-label">Loan Guaranter 2 : </label>
                                        <select class="form-control" name="guaranter_2" id="guaranter_2">
                                            <option value="">Please Select Loan Guaranter 2</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_save_loan">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loanForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('loan.save') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            $('#addLoanModal').modal('hide');
                            tbl_loans.ajax.reload();
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
            tbl_loans = $('#tbl_loans').DataTable({
                lengthMenu: [
                    [25, 50, 100, -1],
                    ['25 ', '50 ', '100', 'All']
                ],
                processing: true,
                ajax: {
                    url: "{{ url('employees/loans/get-loans-table') }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'monthly_amount',
                        name: 'monthly_amount'
                    },
                    {
                        data: 'premiums',
                        name: 'premiums'
                    },
                    {
                        data: 'installed_count',
                        name: 'installed_count'
                    },
                    {
                        data: 'guaranter_1',
                        name: 'guaranter_1'
                    },
                    {
                        data: 'guaranter_2',
                        name: 'guaranter_2'
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
