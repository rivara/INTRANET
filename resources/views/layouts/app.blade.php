<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal-Empresa') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.3.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/hall.js') }}" ></script>
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/Gruntfile.js')}}"></script>
    <script src="{{ asset('js/simple-iconpicker.js')}}"></script>
    <script>
        var whichInput = 0;

        $(document).ready(function(){
            $('.input1').iconpicker(".input1");
            $('#inputid2').iconpicker("#inputid2");
            $('.input3').iconpicker(".input3");
        });
    </script>
    @stack('scripts')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-iconpicker.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if (isset($nombre[0]))
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                                @csrf
                                <button type="submit" class="btn btn-primary fa fa-sign-out ">
                                    {{ __('salir') }}
                                </button>
                            </form>
                        @endif
                            <button type="submit"   class=" btn btn-circle btn-outline-info fa fa-question"
                                    data-toggle="modal"
                                    data-target="#confirmInf">
                            </button>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Modal Dialog -->
    <div class="modal fade" id="confirmInf" aria-labelledby="confirmDeleteLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h6>Si tienes problemas contacte con informática</h6>
                        <a href="mailto:informatica@comafe.es">
                            informatica@comafe.es
                        </a>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>
