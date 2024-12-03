<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Location;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function index()
    {
        $businesses = Business::whereNull('deleted_at')->get();
        return view('pages.Locations.index',compact('businesses'));
    }

    public function ajax_get_locations()
    {
        $businessess = Location::whereNull('deleted_at')->get();

        return datatables()->of($businessess)
        ->addColumn('business', function ($row) {
            $business = Business::where('id',$row->business_id)->first();
            $business_name = 'N/A';
            if($business) {
                $business_name = $business->business_name;
            }
            return $business_name;
        })
        ->addColumn('action', function ($row) {
            $btn = '
                <button type="button" class="btn btn-primary btn-sm btn_edit_location m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-danger btn-sm btn_delete_location m-1" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"><i class="fa fa-trash" aria-hidden="true"></i></button>
            ';
            return $btn;
        })
        ->make(true);
    }

    public function ajax_get_location(Request $request)
    {
        $location = Location::where('id',$request->locationId)->first();
        return response()->json($location);
    }

    public function ajax_save_location(Request $request)
    {
        try{
            DB::beginTransaction();

            $request->validate([
                'location_name' => 'required|string|max:255',
                'business_id' => 'required',
                'address' => 'required|string',
                'telephone' => 'required|string',
            ]);

            $location = new Location();
            $location->location_name = $request->location_name;
            $location->business_id = $request->business_id;
            $location->address = $request->address;
            $location->telephone = $request->telephone;
            $location->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'New Location Added Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!' . $e->getMessage()]);
        }
    }

    public function ajax_edit_location(Request $request)
    {
        try{
            DB::beginTransaction();

            $request->validate([
                'location_name' => 'required|string|max:255',
                'address' => 'required|string',
                'business_id' => 'required',
                'telephone' => 'required|string',
            ]);

            $location = Location::where('id',$request->location_id)->first();
            $location->location_name = $request->location_name;
            $location->business_id = $request->business_id;
            $location->address = $request->address;
            $location->telephone = $request->telephone;
            $location->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Location Updated Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!' . $e->getMessage()]);
        }
    }

    public function ajax_delete_location(Request $request)
    {
        try{
            DB::beginTransaction();

            $business = Location::where('id',$request->location_id)->first();
            $business->deleted_at = Carbon::now();
            $business->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Location Deleted Succesfully']);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Something Went Wrong!']);
        }
    }
}
