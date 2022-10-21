@extends('pdf.layout.template')
@section ('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center"> {{Auth::user()->name}}'s Attendance Report</h3>
            @if($user_attendances->count() > 0)
            @if($setting == 0)
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th> Check In By</th>
                        <th> Check In Time</th>
                        <th> Check Out Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_attendances as $user_attendance)
                    <tr>
                        <td>{{$user_attendance->day}}</td>
                        <td>{{$user_attendance->check_in_by}}</td>
                        <td>{{$user_attendance->check_in_time}}</td>
                        <td>{{$user_attendance->check_out_time}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @elseif($setting == 1)
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th> Day</th>
                        <th> Check In By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_attendances as $user_attendance)
                    <tr>
                        <td>{{$user_attendance->day}}</td>
                        <td>{{$user_attendance->check_in_by}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection