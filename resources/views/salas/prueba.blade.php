@extends('layouts.app')
@section('content')

    <link href="{{ asset('css/list.css') }}" rel="stylesheet">


    <script src="{{ asset('js/jquery2.min.js') }}"></script>
  <!--  <script src="{{ asset('tagtag-editor.jscript> -->
   <script src="https://www.jqueryscript.net/demo/Powerful-Lightweight-jQuery-Tag-Management-Plugin-tagEditor/jquery.tag-editor.js"></script>
    <script src="{{ asset('js/list.js') }}"></script>






    <form action="{{ route('goIndexSala') }}" method="GET">
        <button class="btn btn-primary  floatRight marginRight30px">
            <i class="fa fa-arrow-left"></i>
        </button>
        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
    </form>
    <link rel="stylesheet" href="https://www.jqueryscript.net/demo/Powerful-Lightweight-jQuery-Tag-Management-Plugin-tagEditor/jquery.tag-editor.css">
</head>
<body>


<div style="border-top: 1px solid #eee;border-bottom:1px solid #eee;background:#fafafa;margin:30px 0;padding:20px 5px">
    <div style="padding :0 7px 0 5px;max-width:900px;margin:auto">
        <textarea id="hero-demo">example tags, sortable, autocomplete, edit in place, tab/cursor navigation, duplicate check, callbacks, copy-paste, placeholder, public methods, custom delimiter, graceful degradation</textarea>
    </div>
</div>





</body>
</html>
@endsection