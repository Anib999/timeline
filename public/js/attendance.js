
(function () {
    'use strict';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const udata = $('#u').val(),
        updateCheckOutWithoutAdmin = '',
        checkWhetherCheckOuts = '',
        getLocationData = ''

    let positionCoordinate = {},
        apiPositionCoordinate = {}
    // Attendance direct Check In/out from requested date


    function selectedDateReturn() {
        var input_val = $('#checkIndatepicker').val().split('-');
        var addZero = function(d){
            if(d < 10)
                return '0'+d;
            return d;
        };
        input_val = input_val[0]+'-'+addZero(input_val[1])+'-'+addZero(input_val[2]);
        return input_val
    }

    $('#checkIndatepicker').on('change', async function(e){
        const input_val = selectedDateReturn()
        var calendar_val = $('#'+input_val).val();
        if(calendar_val !== 'L'){
            const res = await checkWhetherCheckOut(input_val)
            if(res == true){
                $('#userCheckOut').show()
            }else{
                $('#userCheckOut').hide()
            }
        }else{
            $('#userCheckOut').hide()
        }
    })

    $('#userCheckOut').on('click', async function(e){
        e.preventDefault()
        const input_val = selectedDateReturn()
        const check_out_time = new Date();

        const checOut = checkOutCheckerOnAttendance(check_out_time)
        if(checOut == 0) {
            const res = await checkOutWithoutAdmin(input_val)
        }
    })

    function checkOutWithoutAdmin(input_val, remData='') {
        $('#lateOutModal').modal('hide')
        
        return new Promise(resolve => {
            $.ajax({
                url: `updateCheckOutWithoutAdmin/${udata}`,
                data: {'selDate': input_val, 'remarks': remData},
                dataType: 'json',
                method: 'get'
            }).done(res => {
                if(res) {
                    $('#userCheckOut').hide()
                    var msg_container = $('#checkin_sucess_message');
                    msg_container.text(res.message).show();
        
                    setTimeout(function () {
                        msg_container.fadeOut();
                        // location.reload()
                    }, 2000);
                }
                resolve(res);
            }).fail(rs => {
                resolve(false)
            })
        })
    }

    function checkWhetherCheckOut(calendar_val){
        return new Promise(resolve => {
            $.ajax({
                url: 'checkWhetherCheckOut',
                data: {'selDate': calendar_val},
                method: 'get',
                dataType: 'json'
            }).done(res => {
                resolve(res)
            }).fail(res => {
                console.log('server error');
            })
        })
    }

    $('#sendcheckIn').on('submit', function (e) {
        e.preventDefault();
        const check_in_time = new Date();
        const allArrDa = $(this).serializeArray();
        const selectedDate = allArrDa[2].value;
        const attCheck = checkInCheckerOnAttendance(check_in_time, selectedDate)
        if(attCheck == 4){
            $('#checkIn').remove()
            var msg_container = $('#checkin_sucess_message');
            msg_container.text('Not allowed before 7AM. Please refresh after 7AM').show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 2000);
        }else if(attCheck == 0){
            var msg_container = $('#checkin_sucess_message');
            msg_container.text('Cannot check in today. Whole day has already passed. Please try again tommorow').show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 2000);
        }
        // else if(attCheck != 1){
        //     const comment = attCheck == 2 ? '&allReasonField=firsthalf' : (attCheck == 3 ? '&allReasonField=secondhalf' : '')
        //     withoutAdminCheckIn(comment)
        // }
    });

    function withoutAdminCheckIn(comment='') {
        $('#lateModal').modal('hide')
        var input_val = $('#checkIndatepicker').val().split('-');
        var addZero = function(d){
            if(d < 10)
                return '0'+d;
            return d;
        };
        input_val = input_val[0]+'-'+addZero(input_val[1])+'-'+addZero(input_val[2]);

        let positionConst = ''
        if(positionCoordinate?.lat != undefined && positionCoordinate?.lat != '')
            positionConst = `&gMap=https://www.google.com/maps/@${positionCoordinate?.lat},${positionCoordinate?.lon},17z`
        else if(apiPositionCoordinate?.latitude != undefined && apiPositionCoordinate?.latitude != '' && apiPositionCoordinate?.latitude != null)
            positionConst = `&gMap=https://www.google.com/maps/@${apiPositionCoordinate?.latitude},${apiPositionCoordinate?.longitude},17z`
        
        let fsLeave = `&dl=${$('#dl').val()}`

        var msg_container = $('#checkin_sucess_message');
            var calendar_val = $('#'+input_val).val();
            // console.log(input_val);
            if (calendar_val.trim() == '' || calendar_val.trim() == 'H') {

                var response = $.ajax({
                    url: $('#sendcheckIn').attr('action'),
                    method: 'post',
                    data: comment != '' ? $('#sendcheckIn').serialize()+comment+positionConst+fsLeave : $('#sendcheckIn').serialize()+positionConst

                });
                response.done(function (res) {

                    msg_container.text(res.message).show();
                    $('#' + input_val).val(1).css('color', 'green');
                    $('#checkIndatepicker').trigger('change')
                    setTimeout(function () {
                        msg_container.fadeOut();
                    }, 2000);
                });

                response.fail(function (res) {
                    var msg_container = $('#checkin_sucess_message');
                    msg_container.text(res.error).css('color', 'red').show();
                    setTimeout(function () {
                        msg_container.fadeOut();
                    }, 2000);
                });
            } else if(calendar_val.trim() == 'L'){
                msg_container.text('This day is Your Leave day').css('color', 'red').show();
                setTimeout(function () {
                    msg_container.fadeOut();
                }, 2000);
            }/*else if(calendar_val.trim() == 'H'){
                msg_container.text('This day is Your Holiday').css('color', 'red').show();
                setTimeout(function () {
                    msg_container.fadeOut();
                }, 2000);
            }*/else{
                msg_container.text('You had already checked in on this date').css('color', 'red').show();
                setTimeout(function () {
                    msg_container.fadeOut();
                }, 2000);
            }
    }

// Attendance Approve Check In/out from requested user


//    $('#requestCinCoutPost').on('submit', function (e) {
//        e.preventDefault();
//
//        var response = $.ajax({
//            url: $(this).attr('action'),
//            method: 'post',
//            data: $(this).serialize()
//
//        });
//        response.done(function (res) {
//            var msg_container = $('#cInOutmessage');
//            msg_container.text(res.message).show();
//            $('#checkInOut-' + res.date).html('Approved').css('color', 'white').css('background', 'blue');
//            setTimeout(function () {
//                msg_container.fadeOut();
//            }, 2000);
//        });
//
//        response.fail(function (res) {
//            var msg_container = $('#cInOutmessage');
//            msg_container.text(res.error).css('color', 'red').show();
//            setTimeout(function () {
//                msg_container.fadeOut();
//            }, 2000);
//        });
//
//    });




    // Attendance Rejected Check In/out from requested user
//
//
//    $('#checkInOutReject').on('submit', function (e) {
//        e.preventDefault();
//
//        var response = $.ajax({
//            url: $(this).attr('action'),
//            method: 'post',
//            data: $(this).serialize()
//
//        });
//        response.done(function (res) {
//            var msg_container = $('#rejectMessage');
//            msg_container.text(res.message).show();
//            $('#checkInOut-' + res.date).html('Rejected').css('color', 'red').removeAttr('data-toggle data-target');
//            setTimeout(function () {
//                msg_container.fadeOut();
//            }, 2000);
//        });
//
//        response.fail(function (res) {
//            var msg_container = $('#cInOutmessage');
//            msg_container.text(res.error).css('color', 'red').show();
//            setTimeout(function () {
//                msg_container.fadeOut();
//            }, 2000);
//        });
//
//    });
//



    // Attendance Check In

    $('#check_in').on('change', function (e) {
        e.preventDefault();
        const check_in_time = new Date();
        var checkbox = $(this);
        //here always today
        const todayDate = check_in_time.toISOString().split('T')[0]
        const attCheck = checkInCheckerOnAttendance(check_in_time, todayDate)
        if(attCheck == 4){
            $('#checkIn').remove()
            var msg_container = $('#checkin_sucess_message');
            msg_container.text('Not allowed before 7AM. Please refresh after 7AM').show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 2000);
        }else if(attCheck == 0){
            var msg_container = $('#checkin_sucess_message');
            msg_container.text('Cannot check in today. Whole day has already passed. Please try again tommorow').show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 2000);
        }
        // else if(attCheck != 1){
        //     const comment = attCheck == 2 ? '&allReasonField=firsthalf' : (attCheck == 3 ? '&allReasonField=secondhalf' : '')
        //     attendanceCheckInFun(checkbox, comment)
        // }
    });

    function attendanceCheckInFun(checkbox, newRem='') {
        var status = checkbox.prop('checked');

        $('#lateModal').modal('hide')

        var form = $('#checkIn');

        // const positionConst = `&=${$.param(positionCoordinate)}`;
        let positionConst = ''
        if(positionCoordinate?.lat != undefined && positionCoordinate?.lat != '')
            positionConst = `&gMap=https://www.google.com/maps/@${positionCoordinate?.lat},${positionCoordinate?.lon},17z`
        else if(apiPositionCoordinate?.latitude != undefined && apiPositionCoordinate?.latitude != '' && apiPositionCoordinate?.latitude != null)
            positionConst = `&gMap=https://www.google.com/maps/@${apiPositionCoordinate?.latitude},${apiPositionCoordinate?.longitude},17z`
        
        let fsLeave = `&dl=${$('#dl').val()}`
        
        if (status) {
            var response = $.ajax({
                url: form.attr('action'),
                method: 'post',
                data: newRem != '' ? form.serialize()+newRem+positionConst+fsLeave : form.serialize()+positionConst
            });

            response.done(function (res) {
                var msg_container = $('#checkin_sucess_message');
                msg_container.text(res.message).show();
                $('#' + res.date).val(1).css('color', 'green');

                setTimeout(function () {
                    msg_container.fadeOut();
                    location.reload()
                }, 2000);
            });

            response.fail(function (res) {
                var msg_container = $('#checkin_sucess_message');
                msg_container.text(res.error).css('color', 'red').show();
                setTimeout(function () {
                    msg_container.fadeOut();
                }, 2000);
            });
        }

    }



    // Attendance Check Out

    $('#check_out').on('change', function (e) {
        e.preventDefault();

        var checkbox = $(this);

        const check_out_time = new Date();

        const checOut = checkOutCheckerOnAttendance(check_out_time)
        if(checOut == 0) {
            attendanceCheckOutFun(checkbox)
        }
    });

    function attendanceCheckOutFun(checkbox, newRem='') {
        
        var status = checkbox.prop('checked');

        $('#lateOutModal').modal('hide')

        var form = $('#checkOut');
        if (status) {
            var response = $.ajax({
                url: form.attr('action'),
                method: 'post',
                data: newRem != '' ? form.serialize()+newRem : form.serialize()
            });

            response.done(function (res) {
                var msg_container = $('#checkin_sucess_message');
                msg_container.text(res.message).show();

                setTimeout(function () {
                    msg_container.fadeOut();
                    location.reload()
                }, 2000);
            });

            response.fail(function (res) {

                alert('Something went wrong!! Please try again later...');
            });
        }
    }



    /* 
     * user check In/out request send *
     */

    var check_in = $('.check_in_request');
    var check_out = $('.check_out_request');
    check_in.hide();
    check_out.hide();
    $('#request_type').change(function () {

        var request_type_val = parseInt($(this).val());
        if (request_type_val == 0) {

            check_in.show();
            $('#requestCheckIn').attr('required', 'required');
            check_out.hide();
            $('#requestCheckOut').removeAttr('required');

        } else if (request_type_val == 1) {
            $('#requestCheckOut').attr('required', 'required');
            check_out.show();
            check_in.hide();
            $('#requestCheckIn').removeAttr('required');
        } else if (request_type_val == 2) {
            $('#requestCheckIn').attr('required', 'required');
            $('#requestCheckOut').attr('required', 'required');
            check_in.show();
            check_out.show();
        } else {
            check_in.hide();
            check_out.hide();
        }
    });


// On submit check in/out request

    $('#checkInRequest').on('submit', function (e) {
        e.preventDefault();

        var response = $.ajax({
            url: $(this).attr('action'),
            method: 'post',
            data: $(this).serialize()

        });

        response.done(function (res) {
            var msg_container = $('#checkin_sucess_message');
            msg_container.text(res.message).show();

            setTimeout(function () {
                msg_container.fadeOut();
            }, 2000);
        });

        response.fail(function (res) {

            alert('Something went wrong!! Please try again later...');
        });

    });




    // make message to read message


    $('#messages').on('click', '.message', function (e) {
        e.preventDefault();

        var notification = $('#notification_count');
        var notification_count_val = parseInt(notification.text()) - 1;



        var response = $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize
        });

        response.done(function (res) {

            notification.text(notification_count_val);

            setTimeout(function () {
                $('#message-' + res.id).fadeOut();
            }, 500);
        });

        response.fail(function (res) {

            $('#message-' + res.id).html('error').css('color', 'red');
        });

    });


    function checkInCheckerOnAttendance(check_in_time='', selectedDate='') {
        const checkInDateTime = check_in_time
        resetNoteAuto(false, '')

        const newDateTime = new Date(checkInDateTime)
        const newDateParse = Date.parse(newDateTime.toISOString().split('T')[0])
        const selectedDateTime = Date.parse(selectedDate);

        if(selectedDateTime < newDateParse){
            $('#lateModal').modal('show')
            return 1
        }

        const fromEarly = newDateTime.setHours(7, 0, 0, 0);
        const toEarly = newDateTime.setHours(9, 45, 59, 0);

        const fromLate = newDateTime.setHours(10, 1, 0, 0);
        const toLate = newDateTime.setHours(10, 30, 59, 0);
        
        const fromFirstHalfLeave = newDateTime.setHours(10, 31, 0, 0);
        const toFirstHalfLeave = newDateTime.setHours(12, 0, 59, 0);

        const fromSecondHalfLeave = newDateTime.setHours(12, 1, 0, 0);
        const toSecondHalfLeave = newDateTime.setHours(17, 0, 59, 0);

        if(checkInDateTime <= fromEarly){
            return 4
        }else if (fromEarly <= checkInDateTime && checkInDateTime <= toEarly) {
            $('#lateModal').modal('show')
            return 1
        } else if (fromLate <= checkInDateTime && checkInDateTime <= toLate) {
            $('#lateModal').modal('show')
            return 1
        } else if (fromFirstHalfLeave <= checkInDateTime && checkInDateTime <= toFirstHalfLeave) {
            resetNoteAuto(true, 'first half leave', 1)
            $('#lateModal').modal('show')
            return 2
        }else if(fromSecondHalfLeave <= checkInDateTime && checkInDateTime <= toSecondHalfLeave){
            resetNoteAuto(true, 'second half leave', 2)
            $('#lateModal').modal('show')
            return 3
        }
        return 0
    }

    function resetNoteAuto(show=false, comment='', dlVal='') {
        if(show == false){
            $('.leaveModalAuto').hide()
        }else{
            $('.leaveModalAuto').show()
        }
        $('.comment').text(comment)
        $('#dl').val(dlVal)
    }

    $("#lateModal").on("hidden.bs.modal", function () {
        resetNoteAuto()
    })

    function checkOutCheckerOnAttendance(check_in_time='') {
        let checkInDateTime = check_in_time

        let newDateTime = new Date(checkInDateTime)

        let fromEarly = newDateTime.setHours(17, 1, 0, 0);
        
        let fromLate = newDateTime.setHours(19, 1, 59, 0);
        
        if(checkInDateTime < fromEarly){
            // console.log('early');
            $('#lateOutModal').modal('show')
            return 1
        }else if (checkInDateTime >= fromLate){
            // console.log('late');
            $('#lateOutModal').modal('show')
            return 2
        }
        return 0
    }

    $('#allReasonForm').on('submit', function(e){
        e.preventDefault()
        const remData = '&'+$(this).serialize();
        let checkbox = $('#check_in')
        if(typeof checkbox.val() == 'undefined')
            withoutAdminCheckIn(remData) 
        else
            attendanceCheckInFun(checkbox, remData)
    })

    $('#allReasonOutForm').on('submit', function(e){
        e.preventDefault()
        const remData = $(this).serializeArray();
        let checkbox = $('#check_out')
        if(typeof checkbox.val() == 'undefined'){
            const input_val = selectedDateReturn()
            checkOutWithoutAdmin(input_val, remData[0].value)
        }
        else
            attendanceCheckOutFun(checkbox, remData)
    })

    // function getDateFromHoursOnAttendance(time) {
    //     time = time.split(':');
    //     let now = new Date();
    //     return new Date(now.getFullYear(), now.getMonth(), now.getDate(), ...time);
    // }

    getLocation()
    getLocationAPI()

    function getLocationAPI() {
        $.ajax({
            url: 'getLocationData',
            dataType: 'json',
            method: 'get',
            data: {}
        }).done((res) => {
            apiPositionCoordinate = res;
        })
    }
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            // console.log('location not supported');
        }
    }

    function showPosition(position) {
        positionCoordinate = {
            'lat': position.coords.latitude, 
            'lon': position.coords.longitude
        };
    }

})();