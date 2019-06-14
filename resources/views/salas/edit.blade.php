@extends('layouts.app')
@section('content')
<h1>---</h1>
<div>
    <form action="{{ route('goIndexSala') }}" method="GET">
        <button class="btn btn-primary  floatRight"><-</button>
        <input type="hidden" name="nombre" value="<?php echo $nombre ?>">
    </form>
</div>
@endsection