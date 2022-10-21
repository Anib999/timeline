/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {


    var oTable = $('#employeeDayWork').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columns: [
            {data: 'created_at', render: function(row, meta, data){
                return row.split(' ')[0]
            }},
            {data: '', render: function(row, meta, data){
                return data?.projects?.name;
            }},
            {data: '', render: function(row, meta, data){
                return data?.sub_categories?.name;
            }},
            {data: '', render: function(row, meta, data){
                return data?.work_details?.name;
            }},
            {data: 'workHour'},
            {data: 'workComment'},
        ]
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
        const res = await ajaxDayworkOfUser(data)
        oTable.clear().draw();
        if(res && res.length > 0)
            oTable.rows.add(res).draw()
    }

    function ajaxDayworkOfUser(data) {
        return new Promise(resolve => {
            $.ajax({
                url: 'ajaxDayworkOfUser',
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