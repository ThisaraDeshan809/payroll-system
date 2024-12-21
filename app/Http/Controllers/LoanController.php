<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('pages.loans.loans', compact('employees'));
    }

    public function ajax_get_loans_table()
    {
        $loans = Loan::get();
        // dd($attendances);
        return datatables()->of($loans)
            ->addColumn('name', function ($row) {
                $employee = Employee::where('id',$row->emp_id)->first();
                return $employee->name;
            })
            ->addColumn('guaranter_1', function ($row) {
                $employee = Employee::where('id',$row->guaranter_1)->first();
                return $employee->name;
            })
            ->addColumn('guaranter_2', function ($row) {
                $employee = Employee::where('id',$row->guaranter_2)->first();
                return $employee->name;
            })
            ->addColumn('action', function ($row) {
                $btn = '
                <button type="button" data-bs-toggle="modal" data-bs-target="#editLoanModal" class="btn btn-primary btn-sm btn_edit_emp m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" class="btn btn-danger btn-sm btn_delete_emp m-1" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
            ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ajax_save_loan(Request $request)
    {
        try {
            DB::beginTransaction();
            $loan = new Loan();
            $loan->emp_id = $request->emp_id;
            $loan->amount = $request->loan_amount;
            $loan->monthly_amount = $request->monthly_loan_amount;
            $loan->premiums = $request->loan_installments;
            $loan->guaranter_1 = $request->guaranter_1;
            $loan->guaranter_2 = $request->guaranter_2;
            $loan->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Loan Assigned Successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
