<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('includes.head')
</head>

<body>
    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>Loading...</span>
        </div>
    </div>
    <div class="">
        @include('partials.sidebar')
        <div class="connect-container align-content-stretch d-flex flex-wrap">
            <div class="page-container">
                @include('includes.header')
                @include('partials.messages')
                <div class="page-content">
                    @yield('content')
                </div>
            </div>
        </div>
        <footer>
            @include('includes.footer')
        </footer>
    </div>
</body>

</html>
