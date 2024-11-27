<div class="page-sidebar">
    <div class="logo-box"><img src="{{ asset('assets/images/logo/staane_logo.png') }}" width="80%"><a href="#"
            id="sidebar-close"><i class="material-icons">close</i></a> <a href="#" id="sidebar-state"><i
                class="material-icons">adjust</i><i class="material-icons compact-sidebar-icon">panorama_fish_eye</i></a>
    </div>
    <div class="page-sidebar-inner slimscroll">
        <ul class="accordion-menu">
            <li class="sidebar-title">
                Apps
            </li>
            <li class="active-page">
                <a href="index.html" class="active"><i class="material-icons-outlined">dashboard</i>Dashboard</a>
            </li>


            <li>
                <a href="#"><i class="material-icons">apps</i>Service Providers<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Create New</a>
                    </li>
                    <li>
                        <a href="">Service Providers</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">calendar_today</i> Meetings<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    {{-- <li>
                        <a href="">Create New</a>
                    </li> --}}
                    <li>
                        <a href="">Manage Meetings</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">input</i>Categories<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('category.new') }}">Create New</a>
                    </li>
                    <li>
                        <a href="{{ route('category.index') }}">Manage Categories</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">bookmark_border</i>Sub Categories<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Create New</a>
                    </li>
                    <li>
                        <a href="">Manage Categories</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">access_time</i>Time Slots<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Create New</a>
                    </li>
                    <li>
                        <a href="">Manage Slots</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons">map</i>Locations<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">
                    <li>
                        <a href="">Create New</a>
                    </li>
                    <li>
                        <a href="">Manage Locations</a>
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
                <a href="/elements"><i class="material-icons">bar_chart</i>Elements<i
                        class="material-icons has-sub-menu">add</i></a>
                <ul class="sub-menu">

                    <li>
                        <a href="">Generate Reports</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
