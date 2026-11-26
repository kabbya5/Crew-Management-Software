<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Maritime Unified HRMS — Crew Portal &amp; Office Operations</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('asset/bootstrap-5.3/css/bootstrap.min.css')}}">
    <script src="{{asset('asset/jquery/jquery-4.0.js')}}"> </script>
    <script src="{{asset('asset/bootstrap-5.3/js/bootstrap.bundle.min.js')}}"> </script>

    {{-- Custome css --}}

    <link rel="stylesheet" href="{{asset('asset/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/header.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/style.css')}}">

    {{-- Extra Link  --}}

    @yield('link')
</head>

<body>
    <header>
        <div>
            <strong>MARITIME • CREW PORTAL</strong><br>
            <small>Personal career workspace</small>
        </div>

        <div class="actions">
            @auth
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}">
                    <button type="button"> Dashboard </button>
                </a>
                @else
                <a href="{{ route('home') }}">
                    <button type="button"> Dashboard </button>
                </a>
                @endif 

                {{-- Show Sign Out if logged in --}}
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Sign out</button>
                </form>
            @else
                {{-- Show Register if not logged in --}}
                <a href="{{ route('register') }}">
                    <button type="button">Register</button>
                </a>
            @endauth
        </div>
    </header>

    <div class="p-4">
        @yield('content')
    </div>
    
    @yield('js')
</body>

</html>



