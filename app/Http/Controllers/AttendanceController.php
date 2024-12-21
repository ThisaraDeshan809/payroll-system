<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TempAttendance;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AttendanceController extends Controller
{
    public function attendance_page()
    {
        return view('pages.employees.attendance');
    }

    public function uploadAttendance(Request $request)
    {
        // Validate file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        DB::table('temp_attendences')->truncate(); // Optional - ensure this is intended

        try {
            DB::beginTransaction();

            $file = $request->file('csv_file')->getRealPath();
            $spreadsheet = IOFactory::load($file);

            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, false, true, true); // Get all rows as an array

            $processedData = [];

            foreach ($rows as $index => $row) {
                if ($index === 1) {
                    continue;
                }
                // Skip empty rows or invalid data
                if (!isset($row['G']) || $row['G'] === null) {
                    continue;
                }

                $personId = str_replace("'", "", $row['A']) ?? '';
                $name = $row['B'] ?? '';
                $department = $row['C'] ?? '';
                $date = $row['D'] ?? '';
                $shift = $row['E'] ?? '';
                $checkIn = $row['H'] ?? '';
                $checkOut = $row['I'] ?? '';
                $attendance = $row['G'] ?? '';
                $duration = null;

                // Calculate duration if check-in and check-out times are valid
                if (preg_match('/\d{1,2}:\d{2}:\d{2}/', $checkIn) && preg_match('/\d{1,2}:\d{2}:\d{2}/', $checkOut)) {
                    $checkInTime = DateTime::createFromFormat('H:i:s', $checkIn);
                    $checkOutTime = DateTime::createFromFormat('H:i:s', $checkOut);

                    if ($checkInTime && $checkOutTime) {
                        $interval = $checkOutTime->diff($checkInTime);
                        $duration = $interval->format('%H:%I:%S');
                    }
                }

                // Add the record to the batch insert array
                $processedData[] = [
                    'emp_id' => $personId,
                    'name' => $name,
                    'department' => $department,
                    'date' => $date,
                    'shift' => $shift,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'duration' => $duration,
                    'attendance' => $attendance,
                ];
            }

            // Batch insert processed data
            if (!empty($processedData)) {
                TempAttendance::insert($processedData);
            }

            DB::commit();

            return response()->json([
                'message' => 'Attendance records uploaded and saved successfully!',
                'success' => true,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function ajax_get_incomplete_attendance(Request $request)
    {
        $attendances = TempAttendance::where(function ($query) {
                $query->where('check_in', '-')
                    ->orWhere('check_out', '-');
            })
            ->get();
        // dd($attendances);
        return datatables()->of($attendances)
            ->addColumn('action', function ($row) {
                $btn = '
                    <button type="button" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal" class="btn btn-info btn-sm btn_view_emp m-1" data-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" class="btn btn-primary btn-sm btn_edit_emp m-1" data-id="' . $row->id . '"><i class="fa fa-pencil-square" aria-hidden="true"></i></button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" class="btn btn-danger btn-sm btn_delete_emp m-1" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
