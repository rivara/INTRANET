@extends('layouts.app')
@section('content')

    <div class="row">
        <div  class="col-md-2 paddingLeft50px" >
            <form   action="{{ route('redirect') }}" method="POST">
                @csrf
                <button type="submit" name="submit" value="Edit" class="btn btn-outline-primary  btnE ">
                    <i class="fa fa-arrow-left fa-lg"></i></button>
                <input type="hidden" name="id" value=1>
                <input type="hidden" name="name" value="" style="display:none;">
            </form>
        </div>
        <div  class="col-md-2" ></div>
        <div  class="col-md-3" >
            <h1 class="paddingtop10px">&nbsp;Modificación usuarios</h1>
        </div>
        <div   class="col-md-5"></div>
    </div>
    <br />
    <div class="container wrapper mitad ">
        <?php $portales = DB::table('portales')->where('id',$portalId)->get() ?>
        <form id="logout-form" action="{{route('updatePortal')}}" method="get">
            <h3>PORTAL</h3>
            <label for="Niombre">Nombre</label>
            <input type="text" class="form-control width300px" name="nombre" value="{{$portales[0]->nombre}}">
            @if ($errors->has('nombre'))
                <span class="error">
                    <strong>{{ $errors->first('nombre') }}</strong>
                </span>
            @endif
            <br>
            <label for="url">URL</label>
            <input type="text" class="form-control width300px" name="url" value="{{$portales[0]->url}}">
            @if ($errors->has('url'))
                <span class="error">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif

            <br>
            <label for="Icono">Icono</label>
            <div class="page">
                <input type="text" class="input1 input form-control width300px" name="icono" value="{{$portales[0]->icono}}"/>
            </div>
            <br>
            <label for="Target">target</label>
            <div>
                @if($portales[0]->target==0)
                    <input type="radio"  name="target" value="0" checked>
                    <label for="type">_blank</label>
                @else
                    <input type="radio"  name="target" value="0">
                    <label for="type">_blank</label>
                @endif
            </div>
            <div>
                @if($portales[0]->target==1)
                    <input type="radio"  name="target" value="1" checked>
                    <label for="type">_self</label>
                @else
                    <input type="radio"  name="target" value="1">
                    <label for="type">_self</label>
                @endif
            </div>
            <div>
                @if($portales[0]->target==2)
                    <input type="radio"  name="target" value="2" checked>
                    <label for="type">_parent</label>
                @else
                    <input type="radio"  name="target" value="2">
                    <label for="type">_parent</label>
                @endif
            </div>
            <div>
                @if($portales[0]->target==3)
                    <input type="radio"  name="target" value="3" checked>
                    <label for="type">_top</label>
                @else
                    <input type="radio"  name="target" value="3">
                    <label for="type">_top</label>
                @endif
            </div>
            <button class="btn btn-primary floatRight"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
                grabar
            </button>
               <input type="hidden" name="id" value="{{$portalId}}" />
        </form>
    </div>
@endsection

