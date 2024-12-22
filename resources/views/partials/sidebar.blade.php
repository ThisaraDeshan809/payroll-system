<div class="page-sidebar">
    <div class="logo-box">
        <img src="{{ asset('assets/images/logo/payroll-logo.png') }}" width="80%">
        <a href="#" id="sidebar-close">
            <i class="material-icons">close</i>
        </a>
        {{-- <a href="#" id="sidebar-state">
            <i class="material-icons">adjust</i>
            <i class="material-icons compact-sidebar-icon">panorama_fish_eye</i>
        </a> --}}
    </div>
    <div class="page-sidebar-inner slimscroll">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Apps
            </li>
            <li class="">
                <a href="/" class=""><i class="material-icons-outlined">dashboard</i>Dashboard</a>
            </li>


            <li>
                <a href="#"><i class="material-icons">apps</i>Employees<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('employee.index')}}">Manage Employees</a>
                    </li>
                    <li>
                        <a href="{{route('attendance.index')}}">Employee Attendance</a>
                    </li>
                    <li>
                        <a href="{{route('loans.index')}}">Employee Loans</a>
                    </li>
                    <li>
                        <a href="{{route('salaryAdvance.index')}}">Employee Salary Advances</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">calendar_today</i>Factory Workers<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('factoryWorker.index')}}">Manage Factory Workers</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">input</i>Shift Workers<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="#">Manage Shift Workers</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">bookmark_border</i>Security Members<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Manage Security Members</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">access_time</i>Drivers<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Manage Drivers</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">payments</i>Payments<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">

                    <li>
                        <a href="">Manage Payments</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">bar_chart</i>Reports<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">

                    <li>
                        <a href="">Generate Reports</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">settings</i>Settings<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{route('business.index')}}">Businesess</a>
                    </li>
                    <li>
                        <a href="{{route('locations.index')}}">Locations</a>
                    </li>
                    <li>
                        <a href="{{route('settings.index')}}">Other Settings</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
