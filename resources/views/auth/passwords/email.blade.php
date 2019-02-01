@extends('layouts.app')
@section('content')
    <div class="container width600px">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-light fa fa-arrow-left fa-lg "></button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h3>Reenvio de mail</h3>
                    </div>
                    <div class="col-md-5"></div>

                    <div class="col-md-6">
                        @if (session('statusOk'))
                            <div id="alert" class="alert alert-success">
                                {{ session('statusOk') }}
                            </div>
                        @endif
                            @if (session('statusFail'))
                                <div id="alert" class="alert alert-danger">
                                    {{ session('statusFail') }}
                                </div>
                            @endif
                        <br>
                        <form class="form-horizontal" method="POST" action="{{ route('forgot') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Enviar
                            </button>
                            <input type="hidden" name="token" value="<?php echo csrf_token(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection