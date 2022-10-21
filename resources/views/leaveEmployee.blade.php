@extends('layouts.app')
@section('content')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@stop

@section('footer')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/leave.js') }}"></script>

@stop
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default border">
                <div class="panel-heading">
                    <h3 class="text-center"> Leave Request Form</h3>
                </div>
                <div class="alert alert-success" id="leaveRequestMessage" style="display: none;">

                </div>
                <div class="panel-body">
                    <form class="form" action="{{route('userLeave.store')}}" method="POST" id="leaveRequest">
                        {{csrf_field()}}
                        <div class="well well-lg">
                            <span> Date :{{$nowDate}}</span>

                        </div>
                        <div class="well-lg border well-border">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('leaveTypes') ? ' has-error' : '' }}">
                                        <label for="leaveTypes">Reason for requested leave :</label>
                                        <select class="form-control" id="leaveTypes" name="leaveTypes" required="required">
                                            @foreach($leaveCapables as $leaveCapable)

                                            <option value="{{$leaveCapable->LeaveType->id}}|{{$leaveCapable->id}}|{{$leaveCapable->remaining_days}}">{{ ($leaveCapable->remaining_days  ===  null) ? '' : '('.$leaveCapable->remaining_days.')' }} {{$leaveCapable->LeaveType->type }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('leave_from_date') ? ' has-error' : '' }}">
                                        <label for="leave_from_date">From Date : </label>
                                        <input type="text" id="leave_from_date" name="leave_from_date" value="{{ old('leave_from_date') }}" class="form-control" required="required">
                                        @if ($errors->has('leave_from_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leave_from_date') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('leave_to_date') ? ' has-error' : '' }}">
                                        <label for="leave_to_date">To Date : </label>
                                        <input type="text" id="leave_to_date" name="leave_to_date" class="form-control" value="{{ old('leave_to_date')}}" required="required">
                                        @if ($errors->has('leave_to_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leave_to_date') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 leaveDetailsCol" style="display: none;">
                                    <div class="form-group{{ $errors->has('leaveDetails') ? ' has-error' : '' }}">
                                        <label for="leaveDetails">Leave Details :</label>
                                        <select class="form-control" id="leaveDetails" name="leaveDetails" required="required">
                                            <option value="casual">Casual Leave</option>
                                            <option value="sick">Sick Leave</option>
                                            <option value="home">Home Leave</option>
                                        </select>
                                        @if ($errors->has('leaveDetails'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leaveDetails') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                        <label for="remarks">Remarks :</label>
                                        <textarea rows="4" id="remarks" name="remarks" rows="1" class="form-control" required="required">{{old('remarks')}}</textarea>
                                        @if ($errors->has('remarks'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('remarks') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Leave Type</label><br>
                                        <table class="table" style="width:auto;">
                                            <tr>
                                                <td>
                                                    <label style="font-weight:500" for="paid_">Unpaid Leave</label>
                                                </td>
                                                <td>
                                                    <input type="radio" id="paid_" name="paid_unpaid_status" value="1" checked>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label style="font-weight:500" for="unpaid_">Paid Leave</label>
                                                </td>
                                                <td>
                                                    <input type="radio" id="unpaid_" name="paid_unpaid_status" value="0">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-4 leaveTimeCol" style="display: none;">
                                    <div class="form-group{{ $errors->has('leaveTime') ? ' has-error' : '' }}">
                                        <label for="leaveTime">Leave Time :</label>
                                        <select class="form-control" id="leaveTime" name="leaveTime" required="required">
                                            <option value="whole">Whole Day</option>
                                            <option value="first">First Half</option>
                                            <option value="second">Second Half</option>
                                        </select>
                                        @if ($errors->has('leaveTime'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leaveTime') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right"> Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary" id="leaveRequestStatus">
                <div class="panel-heading">
                    <h4 class="text-center">Leave Request Status</h4>
                    <div class="alert alert-success" id="leaveRequestCancel" style="display: none;">

                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $leaveRequest)
                            <tr id="leaveRequest-{{$leaveRequest->id}}">
                                <td> {{$leaveRequest->LeaveType->type}}</td>
                                <td>
                                    @if($leaveRequest->status == 0)

                                    <!-- id="leaveRequestDelete" -->
                                    <span class="text-primary"><a class="leaveRequestDelete" data-token="{{ csrf_token() }}" href="{{route('userLeave.destroy',['leaveRequestId'=>$leaveRequest->id])}}" data-method="delete"> Cancel </a> </span>
                                    @elseif($leaveRequest->status == 1)
                                    <span class="text-success">Approved </span>
                                    @elseif($leaveRequest->status == 2)
                                    <span class="text-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-leave-request-{{$leaveRequest->id}}"> View</button>
                                </td>

                            </tr>
                            <div class="modal fade" id="modal-leave-request-{{$leaveRequest->id}}" role="dialog" id="employeeLeaveRequest">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="panel panel-warning">
                                                    <div class="panel-body">
                                                        <div class="col-md-6">
                                                            @if($leaveRequest->status == 0)
                                                            <strong>Name</strong> : {{$leaveRequest->users->name}}
                                                            @elseif($leaveRequest->status == 1)
                                                            <strong> Approve By </strong> : {{$leaveRequest->aprove_by}}
                                                            @elseif($leaveRequest->status == 2)
                                                            <strong>Rejected By </strong>: {{$leaveRequest->aprove_by}}
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Type</strong> : {{$leaveRequest->leaveType->type}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Request Date </strong> : {{$leaveRequest->request_date}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong> No. of days</strong> :{{$leaveRequest->no_of_days}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>From </strong> : {{$leaveRequest->from_date}}
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>To </strong> : {{$leaveRequest->to_date}}
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if($leaveRequest->status == 0)
                                                            <strong>Remarks </strong>: {{$leaveRequest->remarks}}
                                                            @else
                                                            <strong>Remarks </strong>: {{$leaveRequest->ap_remarks}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection