@extends('layouts.app')
@section('content')
    <?php
   echo  $_SESSION['favcolor'];

    ?>
    {{redirect()->route('google.com')}}
@endsection
