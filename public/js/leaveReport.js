/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$(function () {


    var oTable = $('#employeeleave').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'pageLength','copy', 'csv', 'excel', 'pdf', 'print'  
        ],
        columns: [
            {data: 'request_date'},
            {data: 'no_of_days'},
            {data: 'from_date'},
            {data: 'to_date'},
            {data: 'aprove_by'},
            {data: 'ap_remarks'},
        ]
    });

    $("#leave_datepicker_from").datepicker({
        dateFormat: 'yy-mm-dd',
        showOn: "button",
        // buttonImageOnly: false,
        buttonText: "<i class='fa fa-calendar'></i>",
        "onSelect": function (date) {
            // minDateFilter = new Date(date).getTime();
            // oTable.draw();
        }
    }).keyup(function () {
        // minDateFilter = new Date(this.value).getTime();
        // oTable.draw();
    });

    $("#leave_datepicker_to").datepicker({
        dateFormat: 'yy-mm-dd',
        showOn: "button",
        // buttonImageOnly: false,
        buttonText: "<i class='fa fa-calendar'></i>",
        "onSelect": function (date) {
            // maxDateFilter = new Date(date).getTime();
            // oTable.draw();
        }
    }).keyup(function () {
        // maxDateFilter = new Date(this.value).getTime();
        // oTable.draw();
    });

    $('.load_data').on('click', function(e){
        e.preventDefault()
        loadData()
    })
    
    async function loadData() {
        const data = {
            datepicker_from: $('#leave_datepicker_from').val(),
            datepicker_to: $('#leave_datepicker_to').val(),
            user_id: $('#user_id').val()
        }
        console.log(data);
        const res = await ajaxLeaveOfUser(data)
        oTable.clear().draw();
        if(res && res.length > 0)
            oTable.rows.add(res).draw()
    }
    
    function ajaxLeaveOfUser(data) {
        return new Promise(resolve => {
            $.ajax({
                url: 'ajaxLeaveOfUser',
                data: data,
                method: 'get',
                dataType: 'json'
            }).done(res => {
                resolve(res);
            }).fail(res => {
                resolve(false)
                console.log('server error');
            })
        })
    }

});


// Date range filter
minDateFilter = "";
maxDateFilter = "";

$.fn.dataTableExt.afnFiltering.push(
        function (oSettings, aData, iDataIndex) {
            if (typeof aData._date == 'undefined') {
                aData._date = new Date(aData[0]).getTime();
            }

            if (minDateFilter && !isNaN(minDateFilter)) {
                if (aData._date < minDateFilter) {
                    return false;
                }
            }

            if (maxDateFilter && !isNaN(maxDateFilter)) {
                if (aData._date > maxDateFilter) {
                    return false;
                }
            }

            return true;
        }
);

