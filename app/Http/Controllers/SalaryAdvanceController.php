<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryAdvance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryAdvanceController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('pages.salaryAdvance.index',compact('employees'));
    }

    public function get_salary_advances()
    {
        $salary_advances = SalaryAdvance::all();

        return datatables()->of($salary_advances)
            ->addColumn('name', function ($row) {
                $employee = Employee::where('id',$row->emp_id)->first();
                return $employee->name;
            })
            ->addColumn('action', function ($row) {
                $btn = '
                    <button type="button" data-bs-toggle="modal" data-bs-target="#markPaidConfirmationModal" class="btn btn-sm btn-info btn-sm btn_mark_complete m-1" data-id="' . $row->id . '"><i class="fa fa-check" aria-hidden="true"></i> Mark As Paid</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#editSalaryAdvanceModal" class="btn btn-sm btn-primary btn-sm btn_edit_salary_advance m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                ';
                return $btn;
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function ajax_save_salary_advance(Request $request)
    {
        try{
            DB::beginTransaction();

            $salary_advance = new SalaryAdvance();
            $salary_advance->emp_id = $request->emp_id;
            $salary_advance->month = $request->month;
            $salary_advance->amount = $request->amount;
            $salary_advance->status = 'requested';
            $salary_advance->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Salary Advance Added Successfully.']);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => $e->getMessage()]);
        }
    }

    public function ajax_get_salary_advance(Request $request)
    {
        $salary_advance = SalaryAdvance::where('id',$request->salary_advance_id)->first();
        return response()->json(['data' => $salary_advance]);
    }

    public function ajax_edit_salary_advance(Request $request)
    {
        try{
            DB::beginTransaction();

            $salary_advance = SalaryAdvance::where('id',$request->salary_advance_id)->first();
            $salary_advance->emp_id = $request->emp_id;
            $salary_advance->month = $request->month;
            $salary_advance->amount = $request->amount;
            $salary_advance->status = 'requested';
            $salary_advance->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Salary Advance Updated Successfully.']);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => $e->getMessage()]);
        }
    }

    public function ajax_mark_as_paid(Request $request)
    {
        try{
            DB::beginTransaction();

            $salary_advance = SalaryAdvance::where('id',$request->salary_advance_id)->first();
            $salary_advance->status = 'paid';
            $salary_advance->save();

            DB::commit();

            return response()->json(['success' => true , 'message' => 'Salary Advance Marked As Paid']);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false , 'message' => $e->getMessage()]);
        }
    }
}
