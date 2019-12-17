@extends('layouts.app')
@section('content')
    <div class="subtitle">
        <h4>Administración de menus</h4>
    </div>

    <div class="container" >

            <div class="row">
                <div class="col-md-4">
                    <form action="{{ route('goRoom') }}" method="get">
                        @csrf
                        <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                            <i class="fa fa-arrow-left fa-lg"></i></button>
                        <input type="hidden" name="id" value=1>
                    </form>
                </div>
    </div>

@endsection
