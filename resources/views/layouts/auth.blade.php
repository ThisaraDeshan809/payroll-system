<!DOCTYPE html>
<html>

<head>
    @include('includes.head')
</head>

<body>
    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>Loading...</span>
        </div>
    </div>
    <div class="">


        @yield('content')

        <footer>@include('includes.footer')</footer>
    </div>
</body>

</html>
