
@include('reporting.bar')
            @if(isset($option))
                @if($option =="IndiceDeRotacion")
                    <form action="{{ route('indiceDeRotacion')}}" method="get">
                        @include('reporting.almacen.indiceDeRotacion')
                    </form>
                @elseif ($option=="obsoletos")
                    <form action="{{ route('obsoletos')}}" method="get">
                        @include('reporting.almacen.obsoletos')
                    </form>

                @elseif ($option=="marcaPropia")
                    <form action="{{ route('marcaPropia')}}" method="get">
                        @include('reporting.ventas.marcaPropia')
                    </form>
                @elseif ($option=="marcaPropiaPrueba")
                    <form action="{{ route('marcaPropiaPrueba')}}" method="get">
                        @include('reporting.ventas.marcaPropiaPrueba')
                    </form>
                @elseif ($option=="otros")
                    <h3>en construccion</h3>
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                @endif
            @endif

