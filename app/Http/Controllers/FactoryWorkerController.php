<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Settings;
use App\Models\TempAttendance;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class FactoryWorkerController extends Controller
{
    public function index()
    {
        return view('pages.factory workers.index');
    }

    public function get_factory_workers()
    {
        $employees = Employee::whereNull('deleted_at')->where('')->get();

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

    public function calculate_factory_worker_salary(Request $request)
    {
        $employee_id = $request->id;
        $employee = Employee::where('id', $employee_id)->first();
        $employee_attendances = TempAttendance::where('emp_id', $employee->emp_id)->get();

        $early_check_in_count = 0;
        $late_check_out_count = 0;
        $both_conditions_count = 0;
        $half_day_count = 0;
        $ot_day_count = 0;
        $total_ot_salary = 0;
        $attendance_allowance = 0;
        $fixed_allowance = 0;
        $supervisor_allowance = 0;

        $weekdays_both_conditions_count = 0;

        // Tracking Sundays
        $sunday_hours_count = 0;

        foreach ($employee_attendances as $attendance) {
            $check_in = $attendance->check_in ? Carbon::parse($attendance->check_in) : null;
            $check_out = $attendance->check_out ? Carbon::parse($attendance->check_out) : null;
            $duration = $attendance->duration ? Carbon::parse($attendance->duration) : null;

            $attendance_date = $attendance->date;
            $is_weekday = Carbon::parse($attendance_date)->isWeekday();
            $is_sunday = Carbon::parse($attendance_date)->isSunday(); // Check if the date is Sunday

            $is_early_check_in = $check_in && $check_in->lessThan(Carbon::createFromTime(8, 45));
            $is_late_check_out = $check_out && $check_out->greaterThan(Carbon::createFromTime(17, 30));
            $ot_check_out = $check_out && $check_out->greaterThan(Carbon::createFromTime(17, 45));

            if ($is_early_check_in) {
                $early_check_in_count++;
            }

            if ($is_late_check_out) {
                $late_check_out_count++;
            }

            if ($is_early_check_in && $is_late_check_out) {
                $both_conditions_count++;

                if ($is_weekday) {
                    $weekdays_both_conditions_count++;
                }
            }

            if ($duration && $duration->greaterThan(Carbon::createFromTime(4, 0, 0)) && $duration->lessThan(Carbon::createFromTime(8, 0, 0))) {
                $half_day_count++;
            }

            if ($ot_check_out) {
                $ot_day_count++;

                $otCheckInTime = DateTime::createFromFormat('H:i:s', '17:30:00');
                $otCheckOutTime = DateTime::createFromFormat('H:i:s', $check_out->format('H:i:s'));

                if ($otCheckInTime && $otCheckOutTime) {
                    $interval = $otCheckOutTime->diff($otCheckInTime);
                    $ot_hours = $interval->h + ($interval->i / 60);

                    $ot_salary = $employee->daily_salary * 1.5 * $ot_hours;
                    $total_ot_salary += $ot_salary;
                }
            }

            if ($is_sunday && $check_in && $check_out) {
                $work_duration = $check_out->diffInHours($check_in);
                $sunday_hours_count += $work_duration;
            }
        }

        $full_day_salary_amount = $employee->daily_salary * $both_conditions_count;
        $half_day_salary_amount = ($employee->daily_salary / 2) * $half_day_count;
        $sundays_salary_amount = ($employee->daily_salary/8) * 1.5 * $sunday_hours_count;

        if ($weekdays_both_conditions_count >= 23) {
            $attendance_allowance = Settings::where('title', 'attendance_allowance')->first()->value;
        }

        if($both_conditions_count >= 25) {
            $fixed_allowance = $employee->allowance_1;
        }

        if($employee->designation == 'Supervisor' && $both_conditions_count == 10) {
            $supervisor_allowance = $employee->supervisor_allowance;
        }

        $full_salary = $full_day_salary_amount + $half_day_salary_amount + $total_ot_salary + $attendance_allowance + $sundays_salary_amount + $fixed_allowance + $supervisor_allowance;

        dd([
            'total_ot_salary' => $total_ot_salary,
            'both_conditions_count' => $both_conditions_count,
            'weekdays_both_conditions_count' => $weekdays_both_conditions_count,
            'sunday_hours_count' => $sunday_hours_count,
            'full_salary' => $full_salary,
        ]);
    }
}
