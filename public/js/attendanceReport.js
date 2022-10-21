/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$(function () {

    const se = $('#s').val()

    let columnData = [
        {data: 'day'},
        {data: 'check_in_by'},
        {data: 'checkin_location', render: function (row) {
            if(row != '' && row != null)
                return `<a class="btn btn-success btn-sm" href="${row}" target="_blank">View</a>`
            return ''
        }},
    ]

    if(se == 0){
        let columnDataNew = [
            ...columnData,
            {data: 'check_in_time'},
            {data: 'check_out_time'},
            {data: 'total_work_hour'}
        ]
        columnData = columnDataNew;
    }


    var oTable = $('#employeeAttendance').DataTable({
        dom: 'Bfrtip',
        ordering: [[0, 'desc']],
        buttons: [
            'pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columns: columnData
    });

    $("#datepicker_from").datepicker({
        dateFormat: 'yy-m-d',
        showOn: "button",
        buttonText: "<i class='fa fa-calendar'></i>",
        "onSelect": function (date) {
            minDateFilter = new Date(date).getTime();
            // oTable.draw();
        }
    }).keyup(function () {
        // minDateFilter = new Date(this.value).getTime();
        // oTable.draw();
    });

    $("#datepicker_to").datepicker({
        dateFormat: 'yy-m-d',
        showOn: "button",
        buttonText: "<i class='fa fa-calendar'></i>",
        "onSelect": function (date) {
            // loadData()
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
            datepicker_from: $('#datepicker_from').val(),
            datepicker_to: $('#datepicker_to').val(),
            user_id: $('#user_id').val()
        }
        const res = await ajaxAttendanceOfUser(data)
        oTable.clear().draw();
        if(res && res.length > 0)
            oTable.rows.add(res).draw()
    }

    function ajaxAttendanceOfUser(data) {
        return new Promise(resolve => {
            $.ajax({
                url: 'ajaxAttendanceOfUser',
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

