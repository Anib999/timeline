<div class="panel panel-default daily_attendance">
            <div class="col-md-12">
                <div class="row">
                    <div class="panel-heading">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="information-picker-wrapper">
                                        <div class="date-picker-wrapper">
                                            <form action="{{route('attendence.checkIn')}}" method="POST" id="sendcheckIn">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="u" id="u" value="{{ Auth::user()->id }}">
                                            <p><span>Select Date :</span> 
                                                <input type="text" name="attendance_date"  id="checkIndatepicker">
                                                <input type="submit" value="check in" class="btn btn-success" id="userCheckIn" >

                                                <input style="display: none;" type="button" value="check out" class="btn btn-warning" id="userCheckOut" >
                                            </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-success" id="checkin_sucess_message" style="display: none;">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="information-wrapper">
                                <ul > 
                                    <li>Attendance : <span class="at-green">n</span> </li>
                                    <li>Leave: <span class="le-red">L</span> </li>
                                    <li>Holiday: <span class="at-pink">H</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{Auth::user()->id}}" name="user_id" id="user_id">
                </div>
            </div>
            <div class="panel-body">


            </div>
        </div>