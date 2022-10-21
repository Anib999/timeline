@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
@stop

@section('footer')
<script type="text/javascript" src="{{ asset('js/moment.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/attendance.js') }}"></script>

<script>
    $(function () {
    $('#checkInTime').datetimepicker({

    format: 'HH:mm:ss'
    });
    $('#checkOutTime').datetimepicker({

    format: 'HH:mm:ss'
    });
    });
</script>
@stop
@section('content')
@if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('HRAdmin'))
@include('includes.adminhome')

@else
@include('includes.userhome')
@endif

@endsection

