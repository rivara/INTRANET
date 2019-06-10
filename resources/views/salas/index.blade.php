@extends('layouts.app')
@section('content')


    <script>
/*
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                height: 'parent',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                defaultView: 'dayGridMonth',
                defaultDate: '2019-06-12',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventLimit: true, // allow "more" link when too many events

            });

            calendar.render();
        });*/

    </script>



{!! $calendar->calendar() !!}
{!! $calendar->script() !!}









<!-- -->
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <form action="{{ route('backHome') }}" method="GET">
                    <button class="btn btn-primary btnE "><i class=" fa fa-home  fa-lg"></i></button>
                </form>
            </div>
            <div class="col-md-11 paddingLeft30">
                <h2>Reserva de salas</h2>
            </div>
        </div>
    </div>
    <br><br/>
    <div class="container">

            <div class="row">
                <div class="col-md-4" style="background-color: red">
                    <select class="form-control" id="exampleFormControlSelect1">
                        <option>sala1</option>
                        <option>sala2</option>
                        <option>sala3</option>
                    </select>

                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" id="exampleFormControlSelect2">
                            <option>persona1@persona.com</option>
                            <option>person2@persona.com</option>
                            <option>persona3@persona.com</option>
                        </select>
                    </div>

                </div>
                <div class="col-md-8" >

                    <div id="schedule-demo"></div>
                </div>
            </div>

    </div>
@endsection
