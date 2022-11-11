<div class="container-fluid">
    <div class="row">
        @if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin'))
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="well well-sm text-center"> 
                    <p> Check In/Out Request Type setting </p>
                </div>
                <ul class="list-group">
                    <li class="list-group-item ">

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th colspan="2">Previous Day Check In Request</th>
                                </tr>
                            </thead>
                            <tbody>

                            <form action="{{ route('withCheck.withCheckstore')}}" method="POST" id="withcheck" class="text-center">
                                {{ csrf_field() }}
                                <tr>
                                    <th> <input type="radio" name="check_in" value="0"  id="with_check_in" {{$setting == 0 ? 'checked':''}}> </th>
                                    <td> With Admin Approval </td>
                                </tr>
                                <tr>
                                    <th> <input type="radio" name="check_in" value="1"  id="with_check_in" {{$setting == 1 ? 'checked':''}}> </th>
                                    <td> With Out Admin Approval  </td>
                                </tr>
                                <tr>
                                    <td colspan="2"> <button type="submit" class="btn btn-primary btn-block"> Save </button></td> 
                                </tr>

                            </form>
                            </tbody>
                        </table>

                    </li>
                </ul>
            </div>
        </div>
        @else
        <div class="col-md-1"></div>
        @endif

        @if($employeeInOutRequests->count()>0)
        <div class="col-md-5">
            @if (Session::has('message'))
            <div class="alert alert-info text-center">
                {{ Session::get('message') }}
            </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="text-center"> Check In/Out Request</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive table-hover checkinoutapproveTable admin-table_chInOut_Leave" id="CheckInOutRequestTable">
                        <thead>
                            <tr>
                                <th> Employee</th>
                                <th> Request Date</th>
                                <th> Request Type</th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employeeInOutRequests as $employeeInOutRequest)
                            <tr><?php //dd($employeeInOutRequest); ?>
                                <td>{{ $employeeInOutRequest->user->name}}</td>
                                <td> <span class="appRePopup" data-html="true" data-toggle="tooltip" data-placement="top" title="CheckIn:- {{ $employeeInOutRequest->check_in_request_time }}<br> CheckOut:- {{$employeeInOutRequest->check_out_request_time}}">{{ $employeeInOutRequest->day }}</span></td>
                                <td> 
                                    @if( $employeeInOutRequest->request_type == 0 )
                                    Check In
                                    @elseif( $employeeInOutRequest->request_type == 1 )
                                    Check Out
                                    @elseif( $employeeInOutRequest->request_type == 2 )
                                    Check In/Out
                                    @endif
                                </td>
                                <td> 
                                    @if($employeeInOutRequest->status == 0)
                                    <button type="button" class="btn btn-sm btn-default pull-right action-checkInOut-request check_inOut-request-action"  data-requestjson='<?php echo json_encode($employeeInOutRequest); ?>'> Action</button>
                                    @elseif($employeeInOutRequest->status == 1)
                                    <span class="status-a btn-primary pull-right" data-requestjson='<?php echo json_encode($employeeInOutRequest); ?>' title="By: {{ $employeeInOutRequest->aprove_by }}"> Approved </span>
                                    @elseif($employeeInOutRequest->status == 2)
                                    <span class="status-a btn-danger text-primary pull-right" title="By: {{ $employeeInOutRequest->aprove_by }}"> Rejected </span>
                                    @elseif($employeeInOutRequest->status == 4)
                                    <span class="text-success  pull-right" >  Done By {{ $employeeInOutRequest->aprove_by}}</span>
                                    @endif
                                </td>
                        
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if($leaveRequests->count()>0)
        <div class="col-md-5">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4 class="text-center"> Leave Request</h4>
                </div>
                <div class="panel-body">
                    <table class="table table-responsive admin-table_chInOut_Leave" id="leaveRequestTable">
                        <thead>
                            <tr>
                                <th>Name </th>
                                <th>Leave Type </th>
                                <th>Date </th>
                                <th>Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveRequests as $leaveRequest)
                            <tr>
                                <td>{{$leaveRequest->users->name}}</td>
                                <td>{{$leaveRequest->leaveType->type}}</td>
                                <td>
                                    Request: <strong>{{$leaveRequest->request_date}}</strong>  <br />
                                    From: <strong>{{$leaveRequest->from_date}}</strong>  <br />
                                    To: <strong>{{$leaveRequest->to_date}}</strong>  <br />
                                </td>
                                <td>{{$leaveRequest->leave_time}}</td>
                                <td>
                                    @if($leaveRequest->status == 0)
                                    <button class="btn btn-default btn-sm leave-request-action" data-toggle="modal" remaining-days="{{ $leaveRequest->leaveApplicable->remaining_days }}" data-leaverequestjson='<?php echo json_encode($leaveRequest); ?>' >Action</button>
                                    @elseif($leaveRequest->status == 1)
                                    <span class="status-b btn-success" title="By: {{$leaveRequest->aprove_by}}">Approved</span>
                                    @elseif($leaveRequest->status == 2)
                                    <span class="status-b btn-danger" title="By: {{$leaveRequest->aprove_by}}">Rejected</span>
                                    @endif
                                </td>  
                        
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
    </div>
    @endif
</div>
</div>


{{-- leave request modal --}}
<div class="modal fade" id="modal-leave" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Leave Request</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="col-md-4">
                                Name: <span class="name"></span>
                            </div>
                            <div class="col-md-3">
                                Type: <span class="type"></span>
                            </div>
                            <div class="col-md-5">
                                Request Date: <span class="request_date"></span>
                            </div>
                            <div class="col-md-4">
                                No. of days: <span class="no_of_days"></span>
                            </div>
                            <div class="col-md-8">
                                Leave Type: <span class="leave_type"></span>
                            </div>

                            <div class="col-md-4">
                                From: <span class="request_from"></span>
                            </div>
                            <div class="col-md-4">
                                To: <span class="request_to"></span>
                            </div>
                            
                            <div class="col-md-12">
                                Remarks: <span class="remarks"></span>
                            </div>      
                        </div>
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Leave Summary</label> <br />
                        <label for="">Maturity Period: <span class="mat_period">0</span></label>
                        <label for="">Leave Taken: <span class="lea_taken">0</span></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form action="{{route('leave.leaveRequestAction')}}" method="POST">
                            {{ csrf_field()}}
                            <input type="hidden" value="" name="leave_id">
                            <input type="hidden" value="" name="user_id">
                            <input type="hidden" value="" name="leave_type_id">
                            <input type="hidden" value="" name="remaining_days">
                            <input type="hidden" value="" name="request_days">
                            <div class="radio {{ $errors->has('leaveRequest') ? ' has-error' : '' }}">
                                <label><input type="radio" value="1" name="leaveRequest" checked="checked">Accept</label>
                                @if ($errors->has('leaveRequest'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('leaveRequest') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="radio {{ $errors->has('leaveRequest') ? ' has-error' : '' }}">
                                <label><input type="radio" value="0" name="leaveRequest">Reject</label>
                                @if ($errors->has('leaveRequest'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('leaveRequest') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" name="remarks" placeholder="Remarks"></textarea>
                                @if ($errors->has('remarks'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('remarks') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit"  class="btn btn-primary btn-block">Submit</button>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /leave request modal --}}

{{-- check in/out modal --}}
<div class="modal fade" id="modal-checkInOut" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <ul class="nav nav-tabs" style="position:relative;top:16px;">
                    <li class="active"><a data-toggle="pill" href="#approve-tab">Approve</a></li>
                    <li><a data-toggle="pill" href="#reject-tab">Reject</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div id="approve-tab" class="tab-pane fade in active">
                    <form action="{{ route('EmployeeRequestForCheckInOut.store')}}" method="POST" class="form" id="requestCinCoutPost" role="form">
                        {{ csrf_field() }}
                        <input type="hidden" value="" name="user_id" />
                        <input type="hidden" value="" name="checkInOutRequest_id">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <p class="well">
                                        <strong> Remarks :</strong><span class="remarks"></span>
                                    </p>
                                    <label for="checkInOutday" class=" control-label col-md-1">Date</label>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="" name="checkInOutday" id="checkInOutday" readonly>
                                    </div>

                                    <div class="checkInTime-container" title="Requested CheckIn">
                                        <label for="checkInDayTime" class=" control-label col-md-1">In</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="" name="checkInDayTime" id="checkInDayTime" readonly>
                                        </div>
                                    </div>
                                    <div class="checkOutTime-container">
                                        <label for="checkOutDayTime" class=" control-label col-md-1">Out</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" value="" name="checkOutDayTime" id="checkOutDayTime" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    
                                    <input type="hidden" value="" name="request_type">
                                    <div class="checkInTime-container">
                                        <label for="checkInTime" class=" control-label col-md-2  text-nowrap"> Check In:</label>
                                        <div class="col-md-4">
                                            <input type='text' class="form-control" id="checkInTime" name="checkInTime" required="required" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="checkOutTime-container">
                                        <label for="checkOutTime" class=" control-label col-md-2 text-nowrap "> Check Out:</label>
                                        <div class="col-md-4">
                                            <input type='text' class="form-control" id="checkOutTime" name="checkOutTime" required="required"  >
                                        </div>
                                   </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="isLeaveGiven" class=" control-label col-md-2  text-nowrap"> Leave Add :</label>
                                    <div class="col-md-4">
                                        <select name="isLeaveGiven" id="isLeaveGiven" class="form-control">
                                            <option value="0">No Leave</option>
                                            <option value="3">Full Leave</option>
                                            <option value="1">First Leave</option>
                                            <option value="2">Second Leave</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row leaveGivenRow" style="display: none;">
                                    <label for="reasonLeave" class=" control-label col-md-2  text-nowrap"> Reason :</label>
                                    <div class="col-md-4">
                                        <select name="reasonLeave" id="reasonLeave" class="form-control">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="ap_comment" class=" control-label col-md-2  text-nowrap"> Remarks :</label>
                                    <div class="col-md-8">
                                        <textarea name="ap_comment"  id="ap_comment" rows="1" class="form-control"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
    <!-- <div class="alert alert-success" id="cInOutmessage" style="display: none;"></div>  -->
                                <button type="submit" class="btn btn-default" id="requestCInOutSubmit" >Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="reject-tab" class="tab-pane fade in ">
                    <form action="" method="post" class="form" id="checkInOutReject" role="form">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="checkInOutRequest_id" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <label for="checkInOutday" class=" control-label col-md-3"> Request Date :</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" value="" name="checkInOutday" id="checkInOutday" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="ap_comment_reject" class=" control-label col-md-3 "> Remarks :</label>
                                    <div class="col-md-9">
                                        <textarea name="ap_comment_reject"  id="ap_comment_reject" rows="2" class="form-control"> </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" >Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
{{-- /check in/out modal --}}

<script type="text/javascript">
(function(){
    let checkInOutActionUrl = '{{ route('EmployeeRequestForCheckInOut.update',['user_id'=> '@id@']) }}';
    var CheckInOutRequestTable = $('#CheckInOutRequestTable').DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'aaSorting': [],
            }),
        leaveRequestTable = $('#leaveRequestTable').DataTable({
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'aaSorting': [],
        });

    $('#leaveRequestTable').on('click','.leave-request-action',function(){
        var modal_ = $('#modal-leave');
        var requestJson = $(this).data('leaverequestjson');
        // console.log(requestJson);
        modal_.find('.name').text(requestJson.users.name);
        modal_.find('.type').text(requestJson.leave_type.type);
        modal_.find('.request_date').text(requestJson.request_date);
        modal_.find('.no_of_days').text(requestJson.no_of_days);
        modal_.find('.leave_type').text(!requestJson.paid_unpaid_status?'Paid':'UnPaid');
        modal_.find('.request_from').text(requestJson.from_date);
        modal_.find('.request_to').text(requestJson.to_date);
        modal_.find('.remarks').text(requestJson.remarks);

        modal_.find('input[name="leave_id"]').val(requestJson.leave_applicable.leave_id);
        modal_.find('input[name="user_id"]').val(requestJson.user_id);
        //modal_.find('input[name="leave_type_id"]').val(requestJson.leave_type_id);
        modal_.find('input[name="leave_type_id"]').val(requestJson.id);
        modal_.find('input[name="remaining_days"]').val($(this).attr('remaining-days'));
        modal_.find('input[name="request_days"]').val(requestJson.no_of_days);

        //here keep maturity period
        calculateMaturityPeriod(requestJson)

        modal_.modal('show');
    });

    
    $('#CheckInOutRequestTable').on('click','.check_inOut-request-action',function(){
        var modal_ = $('#modal-checkInOut');
        var requestJson = $(this).data('requestjson');

        var checkOutTimeContainer = $('.checkOutTime-container');
        var checkInTimeContainer = $('.checkInTime-container')

        checkOutTimeContainer.show();
        checkOutTimeContainer.find('input').removeAttr('disabled');

        checkInTimeContainer.show();
        checkInTimeContainer.find('input').removeAttr('disabled');
//console.log(requestJson);
        modal_.find('input[name="user_id"]').val(requestJson.user.id);
        modal_.find('input[name="checkInOutRequest_id"]').val(requestJson.id);
        modal_.find('span.remarks').text(requestJson.em_comment);
        modal_.find('input[name="checkInOutday"]').val(requestJson.day);
        modal_.find('input[name="checkInDayTime"]').val(requestJson.check_in_request_time);
        modal_.find('input[name="checkOutDayTime"]').val(requestJson.check_out_request_time);
        modal_.find('input[name="request_type"]').val(requestJson.request_type);

        modal_.find('input[name="checkInTime"]').val(requestJson.check_in_request_time);
        modal_.find('input[name="checkOutTime"]').val(requestJson.check_out_request_time);

        if(requestJson.request_type == 0){
            checkOutTimeContainer.hide();
            checkOutTimeContainer.find('input').attr('disabled','disabled');
            
        }
        
        if(requestJson.request_type == 1){
            checkInTimeContainer.hide();
            checkInTimeContainer.find('input').attr('disabled','disabled');
        }
        
        $('#checkInOutReject').attr('action',checkInOutActionUrl.replace('@id@',requestJson.user_id));
        modal_.modal('show');
    });

    $('#isLeaveGiven').on('change', function(e){
        const UID = $('#requestCinCoutPost input[name="user_id"]').val()
        $('#reasonLeave').empty()
        $('.leaveGivenRow').hide()
        if(this.value != 0){
            loadNewData(UID)
        }
    })

    async function loadNewData(usId) {
        const res = await ajaxLeaveApplicableByUserId(usId);
        let optionList = ''
        if(res){
            res.map(e => {
                optionList += `<option value="${e?.leave_type?.id}|${e.id}|${e.remaining_days}|${e.leave_id}">${e?.leave_type?.type} (${e.remaining_days})</option>`
            })
            $('#reasonLeave').append(optionList)
            $('.leaveGivenRow').show()
        }
    }

    function ajaxLeaveApplicableByUserId(usId) {
        return new Promise(resolve => {
            $.ajax({
                url: 'ajaxLeaveApplicableByUserId',
                data: {usId: usId},
                method: 'get',
                dataType: 'json'
            })
            .done(res => resolve(res))
            .fail(res => resolve(false))
        })
    }

    $('#modal-checkInOut').on('hidden.bs.modal', function (e) {
        $('#isLeaveGiven').val(0)
        $('#reasonLeave').val('')
        $('.leaveGivenRow').hide()
    })

    async function calculateMaturityPeriod(requestJson={}) {
        $('.mat_period').text(0)
        $('.lea_taken').text(0)
        
        const res = await ajaxCheckLeaveSummary(requestJson);
        if(res){
            $('.mat_period').text(res.mp)
            $('.lea_taken').text(res.lt)
        }
    }

    function ajaxCheckLeaveSummary(requestJson = {}) {
        return new Promise(resolve => {
            $.ajax({
                url: 'checkLeaveSummary',
                data: {requestJson : JSON.stringify(requestJson)},
                method: 'post',
                dataType: 'json'
            })
            .done(res => resolve(res))
            .fail(res => resolve(false))
        })
    }
    
})()
</script>