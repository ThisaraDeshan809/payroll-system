<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('pages.employees.index');
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
        $employees = Employee::all();

        return datatables()->of($employees)
            ->addColumn('OT', function ($row) {
                return $row->OT == 1 ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($row) {
                $btn = '
                    <button type="button" class="btn btn-info btn-sm btn_view_emp m-1" data-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-primary btn-sm btn_edit_emp m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-danger btn-sm btn_delete_emp m-1" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                ';
                return $btn;
            })
            ->rawColumns(['OT', 'action'])
            ->make(true);
    }
}
