@extends('layouts.default')

@section('content')

<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/settings') }}">Settings</a></li>
        </ol>
    </nav>
    <div class="card p-3 text-bg-secondary">
        <div class="row">
            <h2>Attendance Settings</h2>
            <div class="col-4">
                <div class="mb-3">
                    <label for="telephone" class="form-label">OT Check In Time : </label>
                    <input type="time" class="form-control" id="ot_time" name="ot_time"
                        required>
                </div>
            </div>
            <div class="col-4">
                <div class="mb-3">
                    <label for="telephone" class="form-label">Fixed Amount For Attendance Allowance : </label>
                    <input type="text" class="form-control" id="attendance_allowance" name="attendance_allowance"
                        required>
                </div>
            </div>
        </div>
        <button id="btn_save_sattings" class="btn btn-primary mt-3" style="width: 15%;">Save Settings</button>
    </div>
</div>

@endsection
