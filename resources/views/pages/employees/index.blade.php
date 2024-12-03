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
                <button type="button" class="btn btn-primary btn-lg m-1" id="btn_add_employee" data-bs-toggle="modal"
                    data-bs-target="#addEmployeeModal">
                    <i class="fa-solid fa-user-plus"></i>
                </button>
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
    </div>

    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="employeeForm">
                        @csrf
                        <h2>Basic Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="epf_no" class="form-label">Employee EPF No.</label>
                                        <input type="text" class="form-control" id="epf_no" name="epf_no" required>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="emp_name" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="emp_name" name="emp_name" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="emp_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="emp_address" name="emp_address" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_location" class="form-label">Employee Location</label>
                                        <select class="form-control" name="emp_location" id="emp_location">
                                            <option value="">Please Select Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_business" class="form-label">Employee Business</label>
                                        <select class="form-control" name="emp_business" id="emp_business">
                                            <option value="">Please Select Business</option>
                                            @foreach ($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->business_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Telephone</label>
                                        <input type="tel" class="form-control" id="telephone" name="telephone"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Payment Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="basic_salary" class="form-label">Employee Basic Salary</label>
                                        <input type="text" class="form-control" id="basic_salary"
                                            name="basic_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="allowance_1" class="form-label">Employee Allowance 1</label>
                                        <input type="text" class="form-control" id="allowance_1" name="allowance_1">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="daily_salary" class="form-label">Employee Daily Salary</label>
                                        <input type="text" class="form-control" id="daily_salary"
                                            name="daily_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_epf" class="form-label">Employee EPF</label>
                                        <input type="text" class="form-control" id="emp_epf" name="emp_epf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Other Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="OT" class="form-label">Employee OT</label>
                                        <select class="form-control" name="OT" id="OT">
                                            <option value="">Please Select</option>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_category" class="form-label">Employee Category</label>
                                        <select class="form-control" name="emp_category" id="emp_category">
                                            <option value="">Please Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_designation" class="form-label">Employee Designation</label>
                                        <select class="form-control" name="emp_designation" id="emp_designation">
                                            <option value="">Please Select</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->name }}">{{ $designation->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Bank Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_bank_name" class="form-label">Employee Bank Name</label>
                                        <input type="text" class="form-control" id="emp_bank_name"
                                            name="emp_bank_name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_bank_branch" class="form-label">Employee Bank Branch</label>
                                        <input type="text" class="form-control" id="emp_bank_branch"
                                            name="emp_bank_branch">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_bank_acc" class="form-label">Employee Bank Account No.</label>
                                        <input type="text" class="form-control" id="emp_bank_acc"
                                            name="emp_bank_acc">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="emp_bank_acc_name" class="form-label">Employee Bank Account
                                            Name</label>
                                        <input type="text" class="form-control" id="emp_bank_acc_name"
                                            name="emp_bank_acc_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3" id="btn_save_business">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm">
                        @csrf
                        <h2>Basic Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_epf_no" class="form-label">Employee EPF No.</label>
                                        <input type="text" class="form-control" id="edit_epf_no" name="edit_epf_no"
                                            required>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="edit_emp_name" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="edit_emp_name"
                                            name="edit_emp_name" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="edit_emp_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="edit_emp_address" name="edit_emp_address" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_location" class="form-label">Employee Location</label>
                                        <select class="form-control" name="edit_emp_location" id="edit_emp_location">
                                            <option value="">Please Select Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->location_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_business" class="form-label">Employee Business</label>
                                        <select class="form-control" name="edit_emp_business" id="edit_emp_business">
                                            <option value="">Please Select Business</option>
                                            @foreach ($businesses as $business)
                                                <option value="{{ $business->id }}">{{ $business->business_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_telephone" class="form-label">Telephone</label>
                                        <input type="tel" class="form-control" id="edit_telephone"
                                            name="edit_telephone" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Payment Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_basic_salary" class="form-label">Employee Basic Salary</label>
                                        <input type="text" class="form-control" id="edit_basic_salary"
                                            name="edit_basic_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_allowance_1" class="form-label">Employee Allowance 1</label>
                                        <input type="text" class="form-control" id="edit_allowance_1"
                                            name="edit_allowance_1">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_daily_salary" class="form-label">Employee Daily Salary</label>
                                        <input type="text" class="form-control" id="edit_daily_salary"
                                            name="edit_daily_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_epf" class="form-label">Employee EPF</label>
                                        <input type="text" class="form-control" id="edit_emp_epf"
                                            name="edit_emp_epf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Other Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_OT" class="form-label">Employee OT</label>
                                        <select class="form-control" name="edit_OT" id="edit_OT">
                                            <option value="">Please Select</option>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_category" class="form-label">Employee Category</label>
                                        <select class="form-control" name="edit_emp_category" id="edit_emp_category">
                                            <option value="">Please Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_designation" class="form-label">Employee Designation</label>
                                        <select class="form-control" name="edit_emp_designation"
                                            id="edit_emp_designation">
                                            <option value="">Please Select</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->name }}">{{ $designation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Bank Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_bank_name" class="form-label">Employee Bank Name</label>
                                        <input type="text" class="form-control" id="edit_emp_bank_name"
                                            name="edit_emp_bank_name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_bank_branch" class="form-label">Employee Bank Branch</label>
                                        <input type="text" class="form-control" id="edit_emp_bank_branch"
                                            name="edit_emp_bank_branch">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_bank_acc" class="form-label">Employee Bank Account
                                            No.</label>
                                        <input type="text" class="form-control" id="edit_emp_bank_acc"
                                            name="edit_emp_bank_acc">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="edit_emp_bank_acc_name" class="form-label">Employee Bank Account
                                            Name</label>
                                        <input type="text" class="form-control" id="edit_emp_bank_acc_name"
                                            name="edit_emp_bank_acc_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="emp_id">
                        <button type="submit" class="btn btn-primary mb-3" id="btn_edit_employee">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="viewEmployeeModalLabel">View Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="viewEmployeeForm">
                        @csrf
                        <h2>Basic Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_epf_no" class="form-label">Employee EPF No.</label>
                                        <input type="text" class="form-control" id="view_epf_no" name="view_epf_no"
                                        readonly>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="view_emp_name" class="form-label">Employee Name</label>
                                        <input type="text" class="form-control" id="view_emp_name"
                                            name="view_emp_name" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="view_emp_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="view_emp_address" name="view_emp_address" rows="5" readonly></textarea>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_location" class="form-label">Employee Location</label>
                                        <input type="text" class="form-control" id="view_emp_location"
                                        name="view_emp_location" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_business" class="form-label">Employee Business</label>
                                        <input type="text" class="form-control" id="view_emp_business"
                                        name="view_emp_business" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_telephone" class="form-label">Telephone</label>
                                        <input type="tel" class="form-control" id="view_telephone"
                                            name="view_telephone" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Payment Informations</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_basic_salary" class="form-label">Employee Basic Salary</label>
                                        <input type="text" class="form-control" id="view_basic_salary"
                                            name="view_basic_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_allowance_1" class="form-label">Employee Allowance 1</label>
                                        <input type="text" class="form-control" id="view_allowance_1"
                                            name="view_allowance_1">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_daily_salary" class="form-label">Employee Daily Salary</label>
                                        <input type="text" class="form-control" id="view_daily_salary"
                                            name="view_daily_salary">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_epf" class="form-label">Employee EPF</label>
                                        <input type="text" class="form-control" id="view_emp_epf"
                                            name="view_emp_epf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Other Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_OT" class="form-label">Employee OT</label>
                                        <input type="text" class="form-control" id="view_OT"
                                        name="view_OT" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_category" class="form-label">Employee Category</label>
                                        <input type="text" class="form-control" id="view_emp_category"
                                        name="view_emp_category" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_designation" class="form-label">Employee Designation</label>
                                        <input type="text" class="form-control" id="view_emp_designation"
                                        name="view_emp_designation" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Bank Details</h2>
                        <div class="card p-2">
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_bank_name" class="form-label">Employee Bank Name</label>
                                        <input type="text" class="form-control" id="view_emp_bank_name"
                                            name="view_emp_bank_name" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_bank_branch" class="form-label">Employee Bank Branch</label>
                                        <input type="text" class="form-control" id="view_emp_bank_branch"
                                            name="view_emp_bank_branch" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_bank_acc" class="form-label">Employee Bank Account
                                            No.</label>
                                        <input type="text" class="form-control" id="view_emp_bank_acc"
                                            name="view_emp_bank_acc" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="view_emp_bank_acc_name" class="form-label">Employee Bank Account
                                            Name</label>
                                        <input type="text" class="form-control" id="view_emp_bank_acc_name"
                                            name="view_emp_bank_acc_name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    <p>Are you sure you want to delete this Employee? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="delete_emp_id">
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
                columns: [{
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
                ],
                order: [
                    [0, 'desc']
                ],
            });

            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "/employees/save-employee",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#addEmployeeModal').modal('hide');
                            tbl_employees.ajax.reload();
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

            $('#tbl_employees').on('click', '.btn_view_emp', function() {

                let empId = $(this).data('id');

                $.ajax({
                    url: '/employees/view-employee',
                    type: 'GET',
                    data: {
                        empId: empId,
                    },
                    success: function(response) {
                        console.log(response.data);
                        $('#view_epf_no').val(response.data.epf_no || '');
                        $('#view_emp_name').val(response.data.name || '');
                        $('#view_emp_address').val(response.data.address || '');
                        $('#view_telephone').val(response.data.telephone || '');
                        $('#view_basic_salary').val(response.data.basic_salary || '');
                        $('#view_allowance_1').val(response.data.allowance_1 || '');
                        $('#view_daily_salary').val(response.data.daily_salary || '');
                        $('#view_emp_epf').val(response.data.emp_epf || '');
                        $('#view_emp_bank_name').val(response.data.bank_name || '');
                        $('#view_emp_bank_branch').val(response.data.bank_branch || '');
                        $('#view_emp_bank_acc').val(response.data.bank_account_no || '');
                        $('#view_emp_bank_acc_name').val(response.data.bank_acc_name || '');

                        $('#view_emp_location').val(response.data.location.location_name || '');
                        $('#view_emp_business').val(response.data.business.business_name || '');

                        let ot = response.data.OT;
                        let ot_name = 'No';
                        if(ot === 1) {
                            ot_name = 'Yes';
                        }

                        $('#view_OT').val(ot_name || '');
                        $('#view_emp_category').val(response.data.emp_category || '');
                        $('#view_emp_designation').val(response.data.designation || '');

                        $('#viewEmployeeModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    },
                });
            });

            $('#tbl_employees').on('click', '.btn_edit_emp', function(e) {
                e.preventDefault();

                let empId = $(this).data('id');

                $.ajax({
                    url: '/employees/get-employee',
                    type: 'GET',
                    data: {
                        empId: empId,
                    },
                    success: function(response) {
                        console.log(response.data.emp_category);
                        $('#editEmployeeModal #edit_epf_no').val(response.data.epf_no);
                        $('#editEmployeeModal #edit_emp_name').val(response.data.name);
                        $('#editEmployeeModal #edit_emp_address').val(response.data.address);
                        $('#editEmployeeModal #edit_telephone').val(response.data.telephone);
                        $('#editEmployeeModal #edit_basic_salary').val(response.data
                            .basic_salary);
                        $('#editEmployeeModal #edit_allowance_1').val(response.data
                            .allowance_1);
                        $('#editEmployeeModal #edit_daily_salary').val(response.data
                            .daily_salary);
                        $('#editEmployeeModal #edit_emp_epf').val(response.data.emp_epf);
                        $('#editEmployeeModal #edit_emp_bank_name').val(response.data
                            .bank_name);
                        $('#editEmployeeModal #edit_emp_bank_branch').val(response.data
                            .bank_branch);
                        $('#editEmployeeModal #edit_emp_bank_acc').val(response.data
                            .bank_account_no);
                        $('#editEmployeeModal #edit_emp_bank_acc_name').val(response.data
                            .bank_acc_name);

                        $('#editEmployeeModal #emp_id').val(empId);
                        $('#editEmployeeModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    },
                });
            });

            $('#editEmployeeModal').on('click', '#btn_edit_employee', function(e) {
                e.preventDefault();

                const formData = {
                    empId: $('#editEmployeeModal #emp_id').val(),
                    epf_no: $('#editEmployeeModal #edit_epf_no').val(),
                    emp_name: $('#editEmployeeModal #edit_emp_name').val(),
                    emp_address: $('#editEmployeeModal #edit_emp_address').val(),
                    telephone: $('#editEmployeeModal #edit_telephone').val(),
                    basic_salary: $('#editEmployeeModal #edit_basic_salary').val(),
                    allowance_1: $('#editEmployeeModal #edit_allowance_1').val(),
                    daily_salary: $('#editEmployeeModal #edit_daily_salary').val(),
                    emp_epf: $('#editEmployeeModal #edit_emp_epf').val(),
                    emp_bank_name: $('#editEmployeeModal #edit_emp_bank_name').val(),
                    emp_bank_branch: $('#editEmployeeModal #edit_emp_bank_branch').val(),
                    emp_bank_acc: $('#editEmployeeModal #edit_emp_bank_acc').val(),
                    emp_bank_acc_name: $('#editEmployeeModal #edit_emp_bank_acc_name').val(),
                    emp_location: $('#editEmployeeModal #edit_emp_location').val(),
                    emp_business: $('#editEmployeeModal #edit_emp_business').val(),
                    emp_ot: $('#editEmployeeModal #edit_OT').val(),
                    emp_category: $('#editEmployeeModal #edit_emp_category').val(),
                    emp_designation: $('#editEmployeeModal #edit_emp_designation').val(),
                };

                $.ajax({
                    url: '/employees/edit-employee',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#editEmployeeModal').modal('hide');
                            $('#editEmployeeForm')[0].reset();
                            tbl_employees.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    }
                });
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
                    error: function(xhr, status, error) {
                        toastr.error(error);
                    }
                });
            });

            $('#tbl_employees').on('click', '.btn_delete_emp', function(e) {
                let empId = $(this).data('id');

                $('#deleteConfirmationModal #delete_emp_id').val(empId);
            });

            $('#deleteConfirmationModal').on('click', '#confirmDeleteButton', function(e) {
                e.preventDefault();

                let empId = $('#delete_emp_id').val();

                $.ajax({
                    url: '/employees/delete-employee',
                    type: 'POST',
                    data: {
                        empId: empId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success(response.message);
                            $('#deleteConfirmationModal').modal('hide');
                            tbl_employees.ajax.reload();
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
        });
    </script>
@endsection
