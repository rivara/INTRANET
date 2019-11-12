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
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/hall.js') }}" ></script>
    <script src="{{ asset('js/app.js') }}" ></script>
    <script src="{{ asset('js/Gruntfile.js')}}"></script>
    <script src="{{ asset('js/simple-iconpicker.js')}}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('js/jsDrag/plugins/sortable.js')}}"></script>
    <script src="{{ asset('js/jsDrag/fileinput.js')}}"></script>
    <script src="{{ asset('js/jsDrag/locales/es.js')}}"></script>
    <script src="{{ asset('js/calendar/moment.min.js')}}"></script>
    <script src="{{ asset('js/calendar/fullcalendar.js')}}"></script>
    <script src="{{ asset('js/calendar/gcal.js')}}"></script>
    <script src="{{ asset('js/calendar/locale-all.js')}}"></script>
    <script src="{{ asset('js/calendar/locale/es.js') }}"></script>
    <script src="{{ asset('js/colorselector.js') }}"></script>
    <script src="{{ asset('js/jquery2.min.js') }}"></script>
    <script src="{{ asset('js/tag-editor.js') }}"></script>
    <script src="{{ asset('js/list.js') }}"></script>




<!-- picker color -->
    <script>
        $(function() {
            window.prettyPrint && prettyPrint();
            $('#colorselector_1').colorselector();
            $('#colorselector_2').colorselector({
                callback : function(value, color, title) {
                    $("#colorValue").val(value);
                    $("#colorColor").val(color);
                    $("#colorTitle").val(title);
                }
            });
        });
    </script>
    @stack('scripts')
<!-- Icons -->
    <?php $var= Illuminate\Support\Facades\App::basePath();

    if($var === 'C:\laragon\www\hall'){ ?>
        <link rel="icon" href="{{asset('icono2.ico')}}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{asset('icono2.ico')}}" type="image/x-icon"/>
    <?php }else{ ?>
        <link rel="icon" href="{{asset('icono1.ico')}}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{asset('icono1.ico')}}" type="image/x-icon"/>
    <?php } ?>


<!-- Fonts -->

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

<!-- Styles -->
    <link href="{{ asset('css/tag-editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-iconpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fileinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/themes/explorer-fas/theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/calendar/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/colorselector.css') }}" rel="stylesheet">

    <link href="{{ asset('css/list.css') }}" rel="stylesheet">

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
                        <a href="mailto:informatica@company.es">
                            informatica@company.es
                        </a>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>


