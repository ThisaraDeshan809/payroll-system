<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    public function index()
    {
        return view('pages.Business.index');
    }

    public function ajax_get_businesses()
    {
        $businessess = Business::whereNull('deleted_at')->get();

        return datatables()->of($businessess)
        ->addColumn('action', function ($row) {
            $btn = '
                <button type="button" class="btn btn-primary btn-sm btn_edit_business m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn_delete_business m-1" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"><i class="fa fa-trash" aria-hidden="true"></i></button>
            ';
            return $btn;
        })
        ->make(true);
    }

    public function ajax_get_business(Request $request)
    {
        $business = Business::where('id',$request->businessId)->first();
        return response()->json($business);
    }

    public function ajax_save_business(Request $request)
    {
        try{
            DB::beginTransaction();

            $request->validate([
                'business_name' => 'required|string|max:255',
                'address' => 'required|string',
                'email' => 'required|email',
                'telephone' => 'required|string',
            ]);

            $business = new Business();
            $business->business_name = $request->business_name;
            $business->address = $request->address;
            $business->email = $request->email;
            $business->telephone = $request->telephone;
            $business->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'New Business Added Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!']);
        }
    }

    public function ajax_edit_business(Request $request)
    {
        try{
            DB::beginTransaction();

            $request->validate([
                'business_name' => 'required|string|max:255',
                'address' => 'required|string',
                'email' => 'required|email',
                'telephone' => 'required|string',
            ]);

            $business = Business::where('id',$request->businessId)->first();
            $business->business_name = $request->business_name;
            $business->address = $request->address;
            $business->email = $request->email;
            $business->telephone = $request->telephone;
            $business->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Business Updated Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!']);
        }
    }

    public function ajax_delete_business(Request $request)
    {
        try{
            DB::beginTransaction();

            $business = Business::where('id',$request->businessId)->first();
            $business->deleted_at = Carbon::now();
            $business->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Business Deleted Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!']);
        }
    }
}
