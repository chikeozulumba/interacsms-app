<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Fast, Cheap and Reliable Bulk SMS in Nigeria.</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bulma-pricingtable.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-divider@2.0.1/dist/css/bulma-divider.min.css">

    <!-- CSS -->
    <link href="{{ asset('css/index.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bulma.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.3.0/css/iziToast.min.css" />
    @yield('stylesheets')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.3.0/js/iziToast.min.js"></script>

</head>
<body>
    {{--  {{ Auth::check() }}  --}}
    @routes
    <div id="app">
        <section class="navWrapper">
            <div class="container is-fullhd">
                <nav class="navbar nav-container container" role="navigation" aria-label="dropdown navigation">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="{{ url('/') }}">
                            <img src="/img/interacsms.png" alt="Fast, Cheap and Reliable Bulk SMS in Nigeria." width="112" height="auto">
                        </a>
                        {{--  <a  style="float:right" role="button" class="navbar-burger is-hidden-mobile" aria-label="menu" aria-expanded="false">
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                        </a>  --}}

                        {{-- <div class="navbar-menu" id="navMenu">

                        </div> --}}
                    </div>
                    <div class="navbar-menu">
                        @if (Route::has('login'))
                        @auth
                            <div class="navbar-end">
                                <div class="navbar-item has-dropdown">
                                    <a class="navbar-link">
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="navbar-dropdown">
                                        <a class="navbar-item" href="{{ route('home') }}">
                                            Dashboard
                                        </a>
                                        <a class="navbar-item" href="{{ route('settings') }}">
                                            Settings
                                        </a>
                                        <hr class="navbar-divider">
                                        <a class="navbar-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="navbar-end">
                                @yield('links')
                                @if (!Request::is('login') && !Request::is('register'))
                                    <a class="navbar-item" href="{{ route('auth') }}">
                                        Get Started
                                    </a>
                                @endif
                            </div>
                        @endauth
                        @endif
                    </div>
                </nav>
            </div>
        </section>
        @yield('content')
    </div>
    @yield('error-scripts')
    @yield('scripts')
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

        // Get all "navbar-burger" elements
        var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        // Check if there are any navbar burgers
        if ($navbarBurgers.length > 0) {

            // Add a click event on each of them
            $navbarBurgers.forEach(function ($el) {
                $el.addEventListener('click', function () {

                    // Get the target from the "data-target" attribute
                    var target = $el.dataset.target;
                    var $target = document.getElementById(target);

                    // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                    $el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                });
            });
        }

        });
    </script>
</body>
</html>
