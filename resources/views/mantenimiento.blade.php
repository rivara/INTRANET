@extends('layouts.app')
@section('content')
<article class="center ">
    <h1>Tareas de mantenimiento  <i class="fa fa-wrench " aria-hidden="true"></i></h1>
    <div>
        <p>En breve ya se dara de alta  </p>
    </div>
    <form action="{{ route('backHome') }}" method="GET">
        <button class="btn btn-primary"><b>volver</b></button>
    </form>
</article>
@endsection
