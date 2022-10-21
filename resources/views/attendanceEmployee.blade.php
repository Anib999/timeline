
@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('js/moment.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ asset('js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/attendance.js') }}"></script>
    <script type="text/javascript">
        var today = new Date();
        const isSuperAdmin = <?= $userDetail->isSuperAdmin; ?>;
        const isAdmin = <?= $userDetail->isAdmin; ?>;
        // request date for request check in/out
        // console.log();
        // $('#requestDatePicker').val(today.toLocaleDateString())
        // 
        let minDater = {minDate: -3}
        // || isAdmin == true
        if(isSuperAdmin == true)
            minDater = {}
        
        $('#requestDatePicker, #checkIndatepicker').val(moment().format('Y-M-D'))
        $('#checkIndatepicker').trigger('change')
        $('#requestDatePicker').datepicker({
            dateFormat: 'yy-m-d',
            autoclose:true,
            endDate: "today",
            maxDate: today,
            ...minDater
        }).on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });

        $('#requestDatePicker').keyup(function () {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9^-]/g, '');
            }
        });
        // time format
        $(function () {
            $('#requestCheckIn').datetimepicker({
                format: 'HH:mm:ss'
            });
            $('#requestCheckOut').datetimepicker({
                format: 'HH:mm:ss'
            });
            // prevent input in request time
            $('#requestDatePicker').keydown(function (event) {
                event.preventDefault();
            })

            $('#requestCheckIn').keydown(function (event) {
                event.preventDefault();
            })

            $('#requestCheckOut').keydown(function (event) {
                event.preventDefault();
            })

        // request date for direct attendance date request 
            $('#checkIndatepicker').datepicker({
                dateFormat: 'yy-m-d',
                autoclose:true,
                endDate: "today",
                maxDate: today,
                ...minDater
            }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });

            $('#checkIndatepicker').keyup(function () {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9^-]/g, '');
                }
            });
            // prevent input in request time
            $('#checkIndatepicker').keydown(function (event) {
                event.preventDefault();
            })
        });


        (function(){
            var workday_route = $('#workday-route').val();
            var holidayJson = JSON.parse($('#holiday-json').text());
            var attendencesJson = JSON.parse($('#attendences-json').text());
            var leavesJson = JSON.parse( $('#leaves-json').text() );

            // console.log(holidayJson,attendencesJson,leavesJson);
            var zeroformat = function(d){
                            if(d < 10)
                                return '0'+d;
                            
                            return d;
                        };
            $('#'+today.getFullYear()+'-'+zeroformat(today.getMonth()+1)+'-'+zeroformat(today.getDate())).css('box-shadow','0px 0px 3px 2px #FFC107 inset');

            var getDatesId = function(startDate, endDate) {
                    var dates = [],
                        currentDate = startDate,
                        addDays = function(days) {
                            var date = new Date(this.valueOf());
                            date.setDate(date.getDate() + days);
                            return date;
                        };
                        
                    while (currentDate <= endDate) {
                        dates.push('#'+currentDate.getFullYear()+'-'+zeroformat(currentDate.getMonth()+1)+'-'+zeroformat(currentDate.getDate()));

                        currentDate = addDays.call(currentDate, 1);
                    }
                    return dates;
                };

            for(let i = 0; i < holidayJson.length; i++){
                $('#'+holidayJson[i].holidayDay)
                .addClass('bg-holiday-o holiday')
                //.attr('title',holidayJson[i].description)
                .attr('data-content',holidayJson[i].type_name)
                .attr('data-trigger','hover');
 
            }

            for(let i = 0; i < leavesJson.length; i++){
                //console.log( leavesJson[i] );
                let fromDate = new Date(leavesJson[i].from_date);
                let toDate = new Date(leavesJson[i].to_date);
                if( fromDate == toDate){
                    $('#'+leavesJson[i].from_date)
                    .addClass('bg-leave leaves')
                    .attr('data-content',leavesJson[i].remarks)
                    .attr('data-trigger','hover')
                    .val('L');
                }else{
                    let leaveDates = getDatesId(fromDate,toDate);
                    $(leaveDates.join(','))
                    .addClass('bg-leave leaves')
                    .attr('data-content',leavesJson[i].remarks)
                    .attr('data-trigger','hover')
                    .val('L');
                }


            }

            for(let i = 0; i < attendencesJson.length; i++){
                if(attendencesJson[i].total_work_hour < 5)
                    $('#'+attendencesJson[i].day).css('color','#673ab7');

                $('#'+attendencesJson[i].day)
                .addClass('text-attendence attendence')
                .val( attendencesJson[i].total_work_hour + 1 );

                // if(attendencesJson[i]?.check_in_time != null)
                const checkInCon = checkInChecker(attendencesJson[i]?.check_in_time)
                if(checkInCon != 0){
                    $('#'+attendencesJson[i].day)
                    .addClass('text-attendence attendence overer')
                    .attr('data-content',checkInCon == 2 ? 'First' : checkInCon == 3 ? 'Second' : '')
                    .attr('data-trigger','hover')
                    .val(checkInCon == 2 ? 'F' : checkInCon == 3 ? 'S' : attendencesJson[i].total_work_hour + 1);
                }
            }

            $('.holiday,.leaves,.overer').popover();
            $('.attendance_input').on('click',function(){
                var calendar_date_field = $(this);
                if( calendar_date_field.val().trim() != '' && !isNaN( calendar_date_field.val() ) && calendar_date_field.val() != 1)
                    window.location = workday_route+'?workday='+calendar_date_field.attr('id');
            });
            
            /*var CalendarDays = [];
            for(let i = 1; i <= 32; i++){
                CalendarDays.push('<th class="bg-primary text-white attendance_input">'+ i +'</th>');
            }
            $('#employee_attendence').before( '<table class=" table-hover"><tr><th class="bg-primary text-white attendance_input">Month/Day</th>'+CalendarDays.join('')+'</tr></table>' );

            var CalendarMonths = [];
            $.each($('#employee-atten tr.month th'),function(){
                CalendarMonths.push('<tr><th class="bg-primary text-white attendance_input">'+ this.innerText +'</th></tr>');
            });
            $('#employee_attendence').before( '<table class=" table-hover">'+CalendarMonths.join('')+'</table>' );
            */

            function checkInChecker(check_in_time) {
                let checkInDateTime = getDateFromHours(check_in_time)

                let newDateTime = new Date(checkInDateTime)

                let fromEarly = newDateTime.setHours(7, 0, 0, 0);
                let toEarly = newDateTime.setHours(9, 45, 0, 0);

                let fromLate = newDateTime.setHours(10, 1, 0, 0);
                let toLate = newDateTime.setHours(10, 30, 0, 0);
                
                let fromFirstHalfLeave = newDateTime.setHours(10, 31, 0, 0);
                let toFirstHalfLeave = newDateTime.setHours(12, 0, 0, 0);

                let fromSecondHalfLeave = newDateTime.setHours(12, 1, 0, 0);
                let toSecondHalfLeave = newDateTime.setHours(17, 0, 0, 0);

                if (fromEarly <= checkInDateTime && checkInDateTime <= toEarly) {
                    // console.log('early reason required');
                } else if (fromLate <= checkInDateTime && checkInDateTime <= toLate) {
                    // console.log('late reason required');
                } else if (fromFirstHalfLeave <= checkInDateTime && checkInDateTime <= toFirstHalfLeave) {
                    // console.log('first half leave');
                    return 2
                }else if(fromSecondHalfLeave <= checkInDateTime && checkInDateTime <= toSecondHalfLeave){
                    // console.log('second half leave');
                    return 3
                }
                return 0
            }

            function checkOutCheckerOnAttendance(check_in_time) {
                // let checkInDateTime = check_in_time
                let checkInDateTime = getDateFromHours(check_in_time)

                let newDateTime = new Date(checkInDateTime)

                let fromEarly = newDateTime.setHours(17, 1, 0, 0);
                
                let fromLate = newDateTime.setHours(19, 1, 0, 0);

                if(checkInDateTime < fromEarly){
                    // console.log('early');
                }else if (checkInDateTime >= fromLate){
                    // console.log('late');
                }

            }

            function getDateFromHours(time) {
                time = time.split(':');
                let now = new Date();
                return new Date(now.getFullYear(), now.getMonth(), now.getDate(), ...time);
            }

        })();

</script>
@endsection

@section('content')
<input type="hidden" id="workday-route" value="{{ route('dayWorkHour.index') }}">
<span style="display:none;" id="holiday-json">{{ json_encode( $holidays) }}</span>
<span style="display:none;" id="attendences-json">{{json_encode($attendances) }}</span>
<span style="display:none;" id="leaves-json">{{json_encode($leaves) }}</span>

<div class="container" >
    <div class="row">
        @if($setting == 0)
        @include('includes.withCheckInRequest')
        @elseif($setting == 1)
        @include('includes.withOutCheckInRequest')
        @endif
        <div class="col-md-12">
            <div class="bharne-faram-wrapper">
                <table class="table table-hover" id="employee_attendence" style="box-shadow: 0px 0px 0px 1px #d2cdcd;">
                    <thead>
                        <tr id="day" class="bg-primary text-white">
                            <th>Month/Day</th>
                            @for ($i = 1; $i <= 32; $i++) 
                            <th> {{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody id="employee-atten">
                    @php
                    $defaultHoliday = array(6);
                    @endphp
                        @for ($m = 1; $m <= 12; $m++) 
                        <tr class="month"> 
                            <th class="bg-primary text-white">{{date("F", mktime(0, 0, 0, $m, 1))}}</th>
                            @for ($j = 1; $j <= cal_days_in_month(CAL_GREGORIAN, $m, date('y')); $j++) 
                            @php
                            $cur_date = date('Y') . '-' . str_pad($m,2,0,STR_PAD_LEFT) . '-' . str_pad($j,2,0,STR_PAD_LEFT);
                            $dd = new DateTime($cur_date);
                            $loopedDate = explode( 'T', $dd->format('c'))[0];
                            $isHoliday = in_array($dd->format('w'),$defaultHoliday) || in_array($loopedDate, $holidayDays_array);
                            //var_dump( $dd->format('w') ); 
                            
                            @endphp
                            
                            <td><input type="text" class="attendance_input @php echo ( $isHoliday)?'bg-holiday':''; @endphp"  value="" id="<?php echo $cur_date ?>" readonly="readonly" /> </td>
                            @endfor
                        </tr>
                        @endfor

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- alll required modal -->
<div class="modal fade" id="lateModal" tabindex="-1" role="dialog" aria-labelledby="lateModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Check In Reason</h4>
      </div>
      <form method="POST" id="allReasonForm">
      <div class="modal-body">
        <label for="allReasonField">Reason</label>
        <div class="input-group col-xs-12">
            <textarea name="allReasonField" id="allReasonField" class="form-control"></textarea>
        </div>
        <div class="leaveModalAuto">
            Note: <span class="comment"></span>
            <input type="hidden" name="dl" id="dl">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- alll required modal -->

<!-- alll required modal -->
<div class="modal fade" id="lateOutModal" tabindex="-1" role="dialog" aria-labelledby="lateOutModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Check Out Reason</h4>
      </div>
      <form method="POST" id="allReasonOutForm">
      <div class="modal-body">
        <label for="allReasonOutField">Reason</label>
        <div class="input-group col-xs-12">
            <textarea name="allReasonOutField" id="allReasonOutField" class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- alll required modal -->

@endsection