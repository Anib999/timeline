<div class="panel panel-default daily_attendance">
    <div class="col-md-12">
        <div class="row">
            <div class="panel-heading">
                <div class="col-md-11">
                    <div class="information-picker-wrapper">
                        <div class="date-picker-wrapper">
                            <form action="{{route('attendence.requestForCheckIn')}}" method="POST" id="checkInRequest" class="form-inline">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input type="text" class="form-control" name="attendance_date"  id="requestDatePicker" placeholder="Request Date"  required="required"  >
                                </div>
                                <div class="form-group">
                                    <select class="form-control"  id="request_type" name="request_type" required="required">
                                        <option value=""> ---- </option>  
                                        @for($i=0; $i<count($requestTypes); $i++)
                                            <option value="{{ $i }}">{{ $requestTypes[$i] }} </option>  
                                            @endfor
                                    </select>
                                </div>
                                <!-- <div class="form-group check_in_request">
                                    <input type="text" class="form-control" name="check_in_request_time" placeholder="Check In Time"  id="requestCheckIn" >
                                </div> -->
                                <!-- <div class="form-group check_out_request">
                                    <input type="text" class="form-control" name="check_out_request_time"  placeholder="Check Out Time" id="requestCheckOut">
                                </div> -->
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="describe request.." rows="1" cols="20" id="em_comment" name="em_comment" required="required"></textarea>
                                </div>
                                <input type="submit"  class="btn btn-primary" value="Send request"  id="sendCheckIn"  >
                            </form>
                            <div class="alert alert-success" id="message" style="display: none;">

                            </div>
                            <div class="alert alert-success" id="checkin_sucess_message" style="display: none;">

                            </div>
                        </div>
                    </div>
                </div>
                @if($already_checkIn== false)
                <div class="col-md-1 text-center">
                    <form action="{{route('attendence.store')}}" method="POST" id="checkIn">
                        {{ csrf_field() }}
                        <label for="check_in" style="display:inline-table;" class="btn btn-success">
                        Check In
                        {{ Form::checkbox('check_in', 0, 0,['id' => 'check_in','style'=>'display:none']) }}
                         </label>
                    </form>
                </div>
                @endif
                @if($already_checkOut == true)
                <div class="col-md-1 text-center">
                    <form action="{{route('attendence.update',['user_id'=>Auth::user()->id])}}" method="POST" id="checkOut">
                        {{ csrf_field() }}
                        <input name="_method" type="hidden" value="PATCH">

                        <label for="check_out" style="display:inline-table;" class="btn btn-warning">
                        Check out
                        {{ Form::checkbox('check_out', 0, 0,['id' => 'check_out','style'=>'display:none']) }}
                        </label>
                    </form>
                </div>
                @endif
            </div>
            <input type="hidden" value="{{Auth::user()->id}}" name="user_id" id="user_id">
        </div>
    </div>
    <div class="panel-body">


    </div>
</div>