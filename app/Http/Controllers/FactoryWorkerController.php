<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\LoanPaymentFail;
use App\Models\SalaryAdvance;
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
        $is_funeral = $request->is_funeral;
        $month = $request->month;
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
        $funeral_fund_amount = 225;
        $loan_deduction_amount = 0;
        $guaranter_loan_amount = 0;
        $salary_advance_amount = 0;

        $weekdays_both_conditions_count = 0;
        $sunday_hours_count = 0;
        $total_attendance_duration = 0; // Total attendance duration in hours
        $late_durations = 0; // Store late durations

        $weekdays_both_conditions_count = 0;

        // Tracking Sundays
        $sunday_hours_count = 0;

        foreach ($employee_attendances as $attendance) {
            $check_in = $attendance->check_in ? Carbon::parse($attendance->check_in) : null;
            $check_out = $attendance->check_out ? Carbon::parse($attendance->check_out) : null;

            // Calculate attendance duration in hours
            if ($check_in && $check_out) {
                $work_duration = $check_out->diffInMinutes($check_in) / 60; // Convert minutes to hours
                $total_attendance_duration += $work_duration;
            }

            $attendance_date = $attendance->date;
            $is_weekday = Carbon::parse($attendance_date)->isWeekday();
            $is_sunday = Carbon::parse($attendance_date)->isSunday();
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

            if ($duration = $attendance->duration) {
                $parsed_duration = Carbon::parse($duration);
                if ($parsed_duration->greaterThan(Carbon::createFromTime(4, 0)) && $parsed_duration->lessThan(Carbon::createFromTime(8, 0))) {
                    $half_day_count++;
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
                $work_duration = $check_out->diffInMinutes($check_in) / 60;
                $sunday_hours_count += $work_duration;
            }

            // Calculate late duration for check-ins after 08:45
            if ($check_in && $check_in->greaterThan(Carbon::createFromTime(8, 45))) {
                $late_start = Carbon::createFromTime(8, 30);
                $late_duration = $check_in->diffInMinutes($late_start)/60; // Late duration in minutes
                $late_durations += $late_duration; // Store the late duration
            }

                $work_duration = $check_out->diffInHours($check_in);
                $sunday_hours_count += $work_duration;
            }
        }

        $full_day_salary_amount = $employee->daily_salary * $both_conditions_count;
        $half_day_salary_amount = ($employee->daily_salary / 2) * $half_day_count;
        $sundays_salary_amount = ($employee->daily_salary / 8) * 1.5 * $sunday_hours_count;

        if (($total_attendance_duration / 8) >= 23) {
            $attendance_allowance = Settings::where('title', 'attendance_allowance')->first()->value;
        }

        if (($total_attendance_duration / 8) >= 25) {
            $fixed_allowance = $employee->allowance_1;
        }

        if ($employee->designation == 'Supervisor' && ($total_attendance_duration / 8) >= 10) {
            $supervisor_allowance = $employee->supervisor_allowance;
        }

        $full_daily_salary = ($total_attendance_duration / 8) * $employee->daily_salary;
        $epf_amount = ($full_daily_salary * 8) / 100;

        $late_cut_amount = ($employee->daily_salary/8) * $late_durations;

        if($is_funeral == 1) {
            $funeral_fund_amount = 275;
        }

        $salary_advance = SalaryAdvance::where('month',$month)->where('emp_id',$employee->id)->where('status','requested')->first();
        if($salary_advance) {
            $salary_advance->status = 'paid';
            $salary_advance->save();

            $salary_advance_amount = $salary_advance->amount;
        }

        $full_salary = $full_day_salary_amount + $half_day_salary_amount + $total_ot_salary + $attendance_allowance + $sundays_salary_amount + $fixed_allowance + $supervisor_allowance - $epf_amount - $late_cut_amount - $funeral_fund_amount - $salary_advance_amount;

        $guaranter_loans = Loan::where('guaranter_1', $employee_id)->orWhere('guaranter_2', $employee_id)->get();
        if($guaranter_loans) {
            foreach($guaranter_loans as $loan) {
                $guaranter_loan_amount += $loan->monthly_amount/2;

                $guaranter_loan_payment = new LoanPayment();
                $guaranter_loan_payment->emp_id = $employee->id;
                $guaranter_loan_payment->loan_id = $loan->id;
                $guaranter_loan_payment->amount = $loan->monthly_amount/2;
                $guaranter_loan_payment->type = 'guaranter';
                $guaranter_loan_payment->save();
            }
        }

        $employee_loan = Loan::where('emp_id',$employee_id)->first();

        if($employee_loan) {
            if($full_salary > $employee_loan->monthly_amount || $total_attendance_duration > 0) {
                $loan_payment = new LoanPayment();
                $loan_payment->emp_id = $employee->id;
                $loan_payment->loan_id = $employee_loan->id;
                $loan_payment->amount = $employee_loan->monthly_amount;
                $loan_payment->type = 'direct';
                $loan_payment->save();

                $loan_deduction_amount = $employee_loan->monthly_amount;
            } else {
                $loan_fail = new LoanPaymentFail();
                $loan_fail->emp_id = $employee->id;
                $loan_fail->loan_id = $employee_loan->id;
                $loan_fail->type = 'direct';
                $loan_fail->amount = $employee_loan->monthly_amount;
                $loan_fail->save();

                $loan_deduction_amount = 0;
            }
        }

        $full_salary_with_loan = $full_salary - $loan_deduction_amount - $guaranter_loan_amount;
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
            'total_attendance_duration' => round($total_attendance_duration / 8, 2), // Total duration rounded to 2 decimal places
            'late_durations' => $late_durations,
            'full_salary' => $full_salary,
            'fixed_allowance' => $fixed_allowance,
            'supervisor_allowance' => $supervisor_allowance,
            'attendance_allowance' => $attendance_allowance,
            'full_salary_with_loan' => $full_salary_with_loan,
        ]);
    }
}
