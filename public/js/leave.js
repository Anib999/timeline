/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    'use strict';

    // leave request  from date
    var today = new Date();

    var from_date = $('#leave_from_date');
    var to_date = $('#leave_to_date');

    from_date.val(today.toLocaleDateString())
    to_date.val(today.toLocaleDateString())

    from_date.datepicker({
        dateFormat: 'yy-m-d',
        autoclose: true,
        startDate: "today",
        minDate: today,
		/*minDate: new Date( today.setDate( today.getDate()-7 ) ),*/
        onSelect: function () {
            var from_dat = new Date(from_date.val());
            var to_dat = new Date(to_date.val());
            var oneDay = 24 * 60 * 60 * 1000;
            var diff = 0;
            diff = Math.round((to_dat - from_dat) / (oneDay));
            to_date.val(from_date.val())
            
            disTypeTime(diff)
            // if (to_date.val()) {
            //     if (diff < 0) {
            //         alert('To date is must be greater than or equal From Date');
            //         from_date.val('');
            //     }
            // }
        }
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
    from_date.keyup(function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9^-]/g, '');
        }
    });


    // prevent input in request date
    from_date.keydown(function (event) {
        event.preventDefault();
    });




    // leave request  to date


    to_date.datepicker({
        dateFormat: 'yy-m-d',
        autoclose: true,
        startDate: "today",
        minDate: today,
        onSelect: function () {
            var from_dat = new Date(from_date.val());
            var to_dat = new Date(to_date.val());
            var oneDay = 24 * 60 * 60 * 1000;
            var diff = 0;
            diff = Math.round((to_dat - from_dat) / (oneDay));
            if (diff < 0) {
                alert('To date is must be greater than or Equal From Date');
                to_date.val('');
            }
            disTypeTime(diff)
           
        }
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });
    to_date.keyup(function () {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9^-]/g, '');
        }
    });
    // prevent input in request date
    to_date.keydown(function (event) {
        event.preventDefault();
    });

    function disTypeTime(diff) {
        console.log(diff);
        if(diff <= 0){
            $('#leaveTime').attr('readonly', false)
            $('#leaveTime').css('pointer-events', '')
        }else{
            $('#leaveTime').attr('readonly', true)
            $('#leaveTime').css('pointer-events', 'none')
        }
        $('#leaveTime').val('whole')
    }


    /*
     * Leave Request Send
     */


    $('#leaveRequest').on('submit', function (e) {
        e.preventDefault();
        var response = $.ajax({
            url: $(this).attr('action'),
            method: 'post',
            data: $(this).serialize()

        });
        response.done(function (res) {
            console.log(res);
            var msg_container = $('#leaveRequestMessage');
            msg_container.text(res.message).show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 5000);
            if(res.stat != 0)
                // location.reload()
            $('#leaveRequest')[0].reset();

            from_date.val(today.toLocaleDateString())
            to_date.val(today.toLocaleDateString())
        });

        response.fail(function (res) {
            var msg_container = $('#leaveRequestMessage');
            setTimeout(function () {
                msg_container.fadeOut();
            }, 5000);
            $('#leaveRequest')[0].reset();
        });

    });



    /*
     * Leave Request Cancel
     */


    $('.leaveRequestDelete').on('click', function (e) {
        e.preventDefault();

        var response = $.ajax({
            url: $(this).attr('href'),
            method: $(this).attr('data-method')

        });
        response.done(function (res) {
            var msg_container = $('#leaveRequestCancel');
            msg_container.text(res.message).show();
            setTimeout(function () {
                msg_container.fadeOut();
            }, 5000);
            $('#leaveRequest-'+res.id).hide();
        });

        response.fail(function (res) {
            var msg_container = $('#leaveRequestCancel');
            setTimeout(function () {
                msg_container.fadeOut();
            }, 5000);

        });

    });


    // scroll 
    $('#leaveRequestStatus').css('max-height', $(window).height()).css('overflow-y', 'auto');

    //new
    const paidUnpaidEle = $('input[name="paid_unpaid_status"]')
    showHideDetails(paidUnpaidEle.val())

    paidUnpaidEle.on('change', function(e){
        showHideDetails(this.value)
    })

    function showHideDetails(paidStatus) {
        paidStatus == 1 ? $('.leaveDetailsCol').hide() : $('.leaveDetailsCol').show()
        // paidStatus == 1 ? $('.leaveTimeCol').hide() : $('.leaveTimeCol').show()
    }

    //new

})();