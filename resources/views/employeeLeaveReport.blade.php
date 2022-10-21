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
<script type="text/javascript" src="{{ asset('js/leaveReport.js') }}"></script>

@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-defaut">
                <div class="panel panel-defaut">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h3 class="text-center"> Leave Report</h3>
                        </div>
                        <div class="col-md-12">
                            <form class="form">
                                <table class="table table-responsive table-borderless" id="date_filter" >
                                    {{csrf_field()}}
                                    <thead>
                                        <tr>
                                        @if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('HRAdmin') || Auth::user()->hasRole('Supervisor'))
                                                <th>
                                                    <span class="">User: </span>
                                                    <select name="" id="user_id">
                                                    @foreach($allUserDetails as $uDet)
                                                        <option value="{{$uDet['id']}}">{{$uDet['username']}}</option>
                                                    @endforeach
                                                    </select>
                                                </th>
                                            @else
                                                <input type="hidden" id='user_id' value="{{Auth::user()->id}}">
                                            @endif
                                            <th> <span id="leave_date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="text" id="leave_datepicker_from" /></th>
                                            <th><span id="leave_date-label-to" class="date-label">To:</span><input class="date_range_filter date" type="text" id="leave_datepicker_to" /></th>
                                            <th><button class="load_data">Load</button></th>
                                        </tr>
                                    </thead>
                                </table>
                            </form>
                        </div>
                        <!-- <div class="col-md-2 text-right">
                            <a href="{{route('downloadLeavePDF',Auth::user()->id)}}"> <i class="fa fa-2x fa-file-pdf-o text-danger" aria-hidden="true"></i></a>
                        </div> -->
                    </div>
                </div>
            </div>
            <table class="table table-responsive table-condensed table-hover" id="employeeleave">
                <thead>
                    <tr>
                        <th>Request Day</th>
                        <th> No of Day</th>
                        <th> Start Date</th>
                        <th> End Date</th>
                        <th> Approve By</th>
                        <th> Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_leves as $user_leve)
                    <tr>
                        <td>{{$user_leve->request_date}}</td>
                        <td>{{$user_leve->no_of_days}}</td>
                        <td>{{$user_leve->from_date}}</td>
                        <td>{{$user_leve->to_date}}</td>
                        <td>{{$user_leve->aprove_by}}</td>
                        <td>{{$user_leve->ap_remarks}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>  
        </div>
    </div>
</div>
@endsection

