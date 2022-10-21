@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
<link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('css/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" >
@stop
@section('footer')
<script type="text/javascript" src="{{ asset('js/moment.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/buttons.print.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/attendanceReport.js') }}"></script>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-defaut">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="text-center"> Attendance Report</h3>
                    </div>
                    <div class="col-md-12">
                        <form class="form">
                            <table class="table table-responsive table-borderless" id="date_filter" >
                                {{csrf_field()}}
                                <thead>
                                    <tr>
                                    <?php if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('Supervisor')){ ?>
                                            <th>
                                                <span class="">User: </span>
                                                <select name="" id="user_id">
                                                @foreach($allUserDetails as $uDet)
                                                    <option value="{{$uDet['id']}}">{{$uDet['username']}}</option>
                                                @endforeach
                                                </select>
                                            </th>
                                        <?php }else{ ?>
                                            <input type="hidden" id='user_id' value="{{Auth::user()->id}}">
                                        <?php } ?>
                                        <th><span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="datepicker_from" /></th>
                                        <th><span id="date-label-to" class="date-label">To:<input class="date_range_filter date" type="text" id="datepicker_to" /></th>
                                        <th><button class="load_data">Load</button></th>
                                    </tr>
                                    </thead>
                            </table>
                        </form>
                    </div>
                    <!-- <div class="col-md-1 text-right">
                        <a href="{{route('downloadAttendancePDF',Auth::user()->id)}}"> <i class="fa fa-2x fa-file-pdf-o text-primary" aria-hidden="true"></i></a>
                    </div> -->
                </div>
            </div>
            <div class="panel panel-danger">
                <div class="panel-body">
                    <input type="hidden" name="s" id="s" value="<?= $setting ?>">
                    <table class="table table-responsive table-hover " id="employeeAttendance" class="display" cellspacing="0" width="100%">
                        @if($setting == 0)

                        <thead>
                            <tr>
                                <th>Day</th>
                                <th> Check In By</th>
                                <th>Location</th>
                                <th> Check In Time</th>
                                <th> Check Out Time</th>
                                <th>Work Hour</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_attendances as $user_attendance)
                            <tr>
                                <td>{{$user_attendance->day}}</td>
                                <td>{{$user_attendance->check_in_by}}</td>
                                <td>{{$user_attendance->checkin_location}}</td>
                                <td>{{$user_attendance->check_in_time}}</td>
                                <td>{{$user_attendance->check_out_time}}</td>
                                <td>{{$user_attendance->total_work_hour + 1}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        @elseif($setting == 1)
                        <thead>
                            <tr>
                                <th> Day</th>
                                <th> Check In By</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user_attendances as $user_attendance)
                            <tr>
                                <td>{{$user_attendance->day}}</td>
                                <td>{{$user_attendance->check_in_by}}</td>
                                <td>
                                    {{$user_attendance->checkin_location}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @endif
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

