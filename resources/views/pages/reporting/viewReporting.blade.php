@extends('layouts.app')

@section('content')

<style>
    .report-tab {
        padding: 0;
    }
    .report-tab li{
        list-style: none;
    }
    .report-tab a {
        color: inherit;
        font-weight: bold;
    }
    .report-tab a:hover {
        text-decoration: none;
    }
</style>

<div class="">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Attendance Report</div>
            <div class="panel-body">
                <ul class="report-tab ">
                    <!-- <li><a href="#">Attendance Report</a></li> -->
                    <li><a href="{{URL::to('employeeAttendance')}}">Location Attendance Report</a></li>
                    <!-- <li><a href="#">User List Report</a></li> -->
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Day Work Report</div>
            <div class="panel-body">
                <ul class="report-tab ">
                    <li><a href="{{URL::to('employeeDayWorkReport')}}">Day Work Report</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Leave Report</div>
            <div class="panel-body">
                <ul class="report-tab ">
                    <li><a href="{{URL::to('employeeLeave')}}">Leave Report</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
<!-- <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.swMenuForm .list-group').append(
        `<li class="list-group-item">
            <a class="" href="{{URL::to('employeeAttendance')}}">View Attendance with location Report</a>
        </li>`
    )
    })
</script> -->
