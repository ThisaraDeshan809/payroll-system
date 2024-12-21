<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Location;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployeeController extends Controller
{
    public function index()
    {
        $businesses = Business::whereNull('deleted_at')->get();
        $locations = Location::whereNull('deleted_at')->get();
        $categories = Category::all();
        $designations = Designation::all();
        return view('pages.employees.index',compact('businesses','locations','categories','designations'));
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xls,xlsx|max:2048',
        ]);

        $file = $request->file('excelFile');
        $path = $file->getPathName();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            DB::beginTransaction();

            foreach ($rows as $key => $row) {
                if ($key === 0)
                    continue;

                $if_ot = 0;
                if ($row[6] == 'Ok') {
                    $if_ot = 1;
                } else {
                    $if_ot = 0;
                }

                $emp_epf = $row[10];

                if ($emp_epf == null) {
                    $emp_epf = 'K 1377';
                }

                $basicSalary = str_replace(',', '', $row[2]); // Remove commas
                $allowance1 = str_replace(',', '', $row[3]);
                $dailySalary = str_replace(',', '', $row[5]);

                Employee::create([
                    'epf_no' => $row[0],
                    'name' => $row[1],
                    'basic_salary' => (double) $basicSalary,
                    'allowance_1' => (double) $allowance1,
                    'daily_salary' => (double) $dailySalary,
                    'OT' => $if_ot,
                    'emp_category' => $row[7],
                    'designation' => $row[8],
                    'emp_epf' => $emp_epf,
                    'bank_acc_name' => $row[11],
                    'bank_account_no' => $row[12],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'File uploaded and data saved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error processing file: ' . $e->getMessage()]);
        }
    }

    public function ajax_get_employees()
    {
        $employees = Employee::whereNull('deleted_at')->get();

        return datatables()->of($employees)
            ->addColumn('OT', function ($row) {
                return $row->OT == 1 ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($row) {
                $btn = '
                    <button type="button" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal" class="btn btn-info btn-sm btn_view_emp m-1" data-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" class="btn btn-primary btn-sm btn_edit_emp m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" class="btn btn-danger btn-sm btn_delete_emp m-1" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                ';
                return $btn;
            })
            ->rawColumns(['OT', 'action'])
            ->make(true);
    }

    public function ajax_employee_save(Request $request)
    {
        $request->validate([
            'epf_no' => 'required',
            'emp_name' => 'required|string',
            'OT' => 'required',
            'emp_category' => 'required',
            'emp_designation' => 'required',
            'emp_bank_name' => 'required',
            'emp_location' => 'required',
            'emp_business' => 'required',
            'telephone' => 'required',
            'emp_bank_branch' => 'required',
            'emp_bank_acc' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $employee = new Employee();
            $employee->epf_no = $request->epf_no;
            $employee->name = $request->emp_name;
            $employee->address = $request->emp_address;
            $employee->loacation_id = $request->emp_location;
            $employee->business_id = $request->emp_business;
            $employee->telephone = $request->telephone;
            $employee->basic_salary = $request->basic_salary;
            $employee->allowance_1 = $request->allowance_1;
            $employee->daily_salary = $request->daily_salary;
            $employee->OT = $request->OT;
            $employee->emp_category = $request->emp_category;
            $employee->designation = $request->emp_designation;
            $employee->emp_epf = $request->emp_epf;
            $employee->bank_name = $request->emp_bank_name;
            $employee->bank_branch = $request->emp_bank_branch;
            $employee->bank_account_no = $request->emp_bank_acc;
            $employee->bank_acc_name = $request->emp_bank_acc_name;
            $employee->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Employee Added Successfully']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => 'Something Went Wrong']);
        }
    }

    public function ajax_get_employee(Request $request)
    {
        $employee = Employee::where('id',$request->empId)->first();
        return response()->json(['data' => $employee]);
    }

    public function ajax_view_employee(Request $request)
    {
        $employee = Employee::with(['location', 'business'])
            ->where('id', $request->empId)
            ->first();

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        return response()->json(['data' => $employee]);
    }

    public function ajax_edit_employee(Request $request)
    {
        $request->validate([
            'epf_no' => 'required',
            'emp_name' => 'required|string',
            'emp_ot' => 'required',
            'emp_category' => 'required',
            'emp_designation' => 'required',
            'emp_bank_name' => 'required',
            'emp_location' => 'required',
            'emp_business' => 'required',
            'telephone' => 'required',
            'emp_bank_branch' => 'required',
            'emp_bank_acc' => 'required',
        ]);

        try{
            DB::beginTransaction();

            $employee = Employee::where('id',$request->empId)->first();
            $employee->epf_no = $request->epf_no;
            $employee->name = $request->emp_name;
            $employee->address = $request->emp_address;
            $employee->loacation_id = $request->emp_location;
            $employee->business_id = $request->emp_business;
            $employee->telephone = $request->telephone;
            $employee->basic_salary = $request->basic_salary;
            $employee->allowance_1 = $request->allowance_1;
            $employee->daily_salary = $request->daily_salary;
            $employee->OT = $request->emp_ot;
            $employee->emp_category = $request->emp_category;
            $employee->designation = $request->emp_designation;
            $employee->emp_epf = $request->emp_epf;
            $employee->bank_name = $request->emp_bank_name;
            $employee->bank_branch = $request->emp_bank_branch;
            $employee->bank_account_no = $request->emp_bank_acc;
            $employee->bank_acc_name = $request->emp_bank_acc_name;
            $employee->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Employee Updated Successfully']);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => 'Something Went Wrong' . $e->getMessage()]);
        }
    }

    public function ajax_delete_employee(Request $request)
    {
        try{
            DB::beginTransaction();

            $employee = Employee::where('id',$request->empId)->first();
            $employee->deleted_at = Carbon::now();
            $employee->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Employee Deleted Successfully.']);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => 'Something Went Wrong']);
        }
    }
}
