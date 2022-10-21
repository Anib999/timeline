@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
<style type="text/css">
    .addGroup-loader {
        position: absolute;
        right: 21px;
        top: 8px;
        font-size: 20px;
        background: #fff;
    }
</style>
@if( isset($_GET['workday']) )
<style type="text/css">
    #projectDetailsContainer-table th:first-child,
    #projectDetailsContainer-table td:first-child {
        display: none !important;
        pointer-events: none;
    }

    .add-daywork-container {
        display: none !important;
    }

    .display-daywork-container {
        width: 100% !important;
    }
</style>
@endif
@endsection

@section('footer')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>

<script type="text/javascript">
    $(function() {
        $('#workDayDate').datepicker({
            dateFormat: 'yy-m-d',
            autoclose: true,
            endDate: "today",
            maxDate: Date.today(),
            minDate: Date.today().add(-3).days()
            //defaultDate: today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()
        });
        $('#workDayDate').on('keydown', function(e) {
            e.preventDefault();
        })
    });
</script>

@endsection

@section('content')

<div class="container">

    <div class="row">
        @include('partials.fieldVisitReport')
        <div class="col-md-5 add-daywork-container">
            <div class="panel panel-default">
                <div id="reqDate-wait" style="display:none; position:absolute;width: calc(100% - 31px);text-align: center;height: calc(100% - 24px);z-index:  99;background-color: rgba(0,0,0,0.06);">
                    <i class="fa fa-spinner fa-spin fa-5x" style="position:relative; top:calc(50% - 84px);"></i>
                </div>
                <div class="panel-body">
                    @if (Session::has('message'))
                    <div class="alert alert-success text-center">{{ Session::get('message') }}</div>
                    @endif
                    <form action="{{route('storeFieldVisit')}}" method="POST" class="form-horizontal" id="dayWorkHour">
                        {{csrf_field()}}

                        <div id="project-dropdown-container">
                            <div class="form-group{{ $errors->has('workDayDate') ? ' has-error' : '' }} ">
                                <label for="workDayDate" class="col-md-4">Work Day Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="workDayDate" name="workDayDate" value="{{ (isset($_GET['workday']))?$_GET['workday']:'' }}" class="form-control" getWorkEntriesByDate-route="{{ route('getFieldVisitEntriesByDate') }}">
                                    @if ($errors->has('workDayDate'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('workDayDate') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if(count($users))
                            <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }} ">
                                <label class="col-md-4">Users</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="user" id="user" required="required">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('user') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="form-group{{ $errors->has('project_id') ? ' has-error' : '' }} ">
                                <label class="col-md-4">Project</label>
                                <div class="col-md-8">
                                    <select class="form-control" name="project_id" id="project" data-url="{{ url('api/projectdropdown')}}" required="required">
                                        <option> </option>
                                        @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('project_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('project_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('workDetail') ? ' has-error' : '' }}">
                            <label for="workDetail" class="col-md-4">Work Detail</label>
                            <div class="col-md-8">
                                <!-- <select route="{{route('workDetailsAPI')}}" class="form-control" id="workDetail" name="workDetail" required>
                                    <option value=""> </option>
                                </select> -->
                                <textarea class="form-control" name="workDetail" id="workDetail" placeholder="Add Work Detail"> {{ old('workDetail') }} </textarea>
                                @if ($errors->has('workDetail'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('workDetail') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div id="dayWorkHourdetail"> -->
                        <div class="form-group{{ $errors->has('dayWorkdHour') ? ' has-error' : '' }}">
                            <label for="dayWorkdHour" class="col-md-4">Work Hour</label>
                            <div class="col-md-8">

                                <input type="number" step="0.25" class="form-control" id="dayWorkdHour" placeholder="Work Hour" value="{{ old('dayWorkHour') }}" name="dayWorkdHour" required>
                                @if ($errors->has('dayWorkdHour'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dayWorkdHour') }}</strong>
                                </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('workComment') ? ' has-error' : '' }}">
                            <label for="workComment" class="col-md-4">Comments</label>
                            <div class="col-md-8">

                                <textarea class="form-control" name="workComment" id="workComment" placeholder="Add Description"> {{ old('workComment') }} </textarea>
                                @if ($errors->has('workComment'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('workComment') }}</strong>
                                </span>
                                @endif

                            </div>
                        </div>
                        <!-- </div> -->

                        <div class="form-group">
                            <div class="col-md-3 pull-right text-right">
                                <button class="btn btn-success" type="button" id="add-workDetail" style="padding-left:32px;padding-right:32px;">Add</button>
                            </div>
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function() {
        var today = new Date();
        let user_ = $('#user');
        /*$("#fromDate").datepicker();
        
        $("#toDate").datepicker();*/

        if ($('#workDayDate').val() == '')
            $('#workDayDate').val(today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate());

        //$('#tablework-date').text($('#workDayDate').val());


        function HtmlEncode(s) {
            var el = document.createElement("div");
            el.innerText = el.textContent = s;
            s = el.innerHTML;
            return s;
        }

        $('#add-workDetail').on('click', function() {
            var project = parseInt($('#project').val());
            var subCategory = parseInt($('#sub_category').val());
            var workDetail = $('#workDetail').val();
            var workComment = HtmlEncode($('#workComment').val().trim());
            var workHour = parseFloat($('#dayWorkdHour').val());
            var user = $('#user').val();
            if (user !== undefined && user == '') {
                alert('Please select the User');
                return;
            }
            // isNaN(subCategory) || isNaN(workDetail) ||
            if (isNaN(project) || workComment == '' || isNaN(workHour) || workHour < 0) {
                if (workHour < 0) {
                    alert('Work Hour cannot be in negative.');
                    return;
                }
                alert('These field/s are empty: \n' +
                    ((isNaN(project)) ? 'Project\n' : '') +
                    // ((isNaN(subCategory)) ? 'Sub Category\n' : '') +
                    // ((isNaN(workDetail)) ? 'Work Detail\n' : '') +
                    ((workComment == '') ? 'Comment\n' : '') +
                    ((isNaN(workHour)) ? 'Work Hour' : ''));
                return;
            }

            var projectDetailsContainer = $('#projectDetailsContainer-table');
            var last_workDetailRow_ID = projectDetailsContainer.find('tbody.projectDetailsContainer-fields-container tr:last-child').attr('id');
            if (last_workDetailRow_ID == undefined)
                last_workDetailRow_ID = 'workDetail_row__1';
            else
                last_workDetailRow_ID = 'workDetail_row__' + (parseInt(last_workDetailRow_ID.split('__')[1]) + 1);

            projectDetailsContainer.find('tbody.projectDetailsContainer-fields-container').append(
                '<tr title="' + ((user !== undefined) ? $('#user option[value="' + user + '"]').text() : '') + '" id="' + last_workDetailRow_ID + '">' +
                '<td><span class="delete-row text-danger">&times;</span></td>' +
                '<td class="project-field" project="' + project + '">' + $('#project :selected').text() + '</td>' +
                // '<td class="subCategory-field" subCategory="' + subCategory + '">' + $('#sub_category :selected').text() + '</td>' +
                '<td class="workDetail-field" workDetail="' + workDetail + '">' + workDetail + '</td>' +
                '<td class="workHour-field">' + workHour + '</td>' +
                '<td class="workComment-field">' + ((user !== undefined) ? '<input type="hidden" class="wd_user" value="' + user + '">' : '') + workComment + '</td>' +
                '</tr>'
            );

            //var projectDetailsGroupContainer = $('#projectDetailsGroupContainer-'+project);

            var totalHours = projectDetailsContainer.find('tbody.totalHours-container .totalHours-field');
            totalHours.text(parseFloat(totalHours.text()) + workHour);
            var workDayDate = $('#workDayDate').val();
            $('#dayWorkHour')[0].reset();
            $('#workDayDate').val(workDayDate);
            $('.submitWorkDay').show();

            $('.state_msg').remove();
        });

        $('#dayWorkHour').on('submit', function(e) {
            e.preventDefault();
            //$('#add-workDetail').trigger('click');
        });

        $('#WorkDayDetails').on('submit', function(e) {
            e.preventDefault();
            $('body,html').animate({
                scrollTop: 0
            });

            var data = {
                'workDayDetails': {},
                'workDayDate': $('#workDayDate').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'totalWorkHour': parseFloat($('#projectDetailsContainer-table tbody.totalHours-container .totalHours-field').text())
            };
            var workDayDetails_row = $('#projectDetailsContainer-table tbody.projectDetailsContainer-fields-container tr:not(".status,.warning")');

            if (workDayDetails_row.length == 0) {
                alert('Please add your work day detail/s.');
                return;
            }

            var workDetail_data = [];
            $.each(workDayDetails_row, function() {
                let workDetail_row = $(this);
                let data_row = [];
                let project = workDetail_row.find('.project-field');
                let subcategory = workDetail_row.find('.subCategory-field');
                let workDetail = workDetail_row.find('.workDetail-field');
                let wd_user = workDetail_row.find('.wd_user');
                data_row.push({
                    'project': project.attr('project'),
                    'project_name': project.text(),
                    'subCategory': subcategory.attr('subCategory'),
                    'subCategory_name': subcategory.text(),
                    'workDetail': workDetail.attr('workDetail'),
                    'workDetail_name': workDetail.text(),
                    'workComment': workDetail_row.find('.workComment-field').text(),
                    'workHour': workDetail_row.find('.workHour-field').text(),
                    'workDetail_row': workDetail_row.attr('id'),
                    'user': wd_user.val()
                });

                workDetail_data.push(data_row);
            });


            data['workDayDetails'] = workDetail_data;
            //console.log( data );

            var submitWorkDetails = $.ajax({
                url: $('#dayWorkHour').attr('action'),
                method: 'post',
                data: data,
                dataType: 'json'
            });

            $('#submitting-status').height($('#WorkDayDetails').height()).width($('#WorkDayDetails').width()).show();

            submitWorkDetails.done(function(res) {
                //console.log( res );
                if (res.hasOwnProperty('msg')) {
                    alert(res.msg);
                    return;
                }

                var inserted_row = res.inserted;
                var alreadyExist = res.alreadyExists;

                for (let i = 0; i < alreadyExist.length; i++) {
                    $('#' + alreadyExist[i])
                        .addClass('warning')
                        .after('<tr class="text-center info status" id="workDetail_row_warning__' + alreadyExist[i].split('__')[1] + '"><td colspan="6"><small style="font-weight:bold;">You have already submitted this work detail</small></td></tr>');
                }

                let inserted_row_IDs = Object.keys(inserted_row);
                for (let i = 0; i < inserted_row_IDs.length; i++) {
                    let row_delete_icon = $('#' + inserted_row_IDs[i]).find('span.delete-row');
                    row_delete_icon.removeClass('delete-row');
                    row_delete_icon.addClass('deleteTodaysWorkEntry-row');
                    row_delete_icon.attr('entryid', inserted_row[inserted_row_IDs[i]]);

                    $('#' + inserted_row_IDs[i]).addClass('success');

                    var workDayDetailsTableBody = '#projectDetailsContainer-table tbody.todaysWorkEntriesContainer';
                    $('#' + inserted_row_IDs[i]).clone().removeAttr('id').appendTo(workDayDetailsTableBody);

                    $('#' + inserted_row_IDs[i]).remove();
                }

                $('#submitting-status').hide();
            });

            submitWorkDetails.fail(function(res) {
                console.log(res);
                alert('Something went wrong with the server. Please contact your administrator.');
                $('#submitting-status').hide();
            });

        });

        $('#projectDetailsContainer-table').on('click', '.delete-row', function() {
            var table_row = $(this).parents('tr');
            var workHour = parseFloat(table_row.find('.workHour-field').text());

            var totalHours = $('#projectDetailsContainer-table tbody.totalHours-container .totalHours-field');
            totalHours.text(parseFloat(totalHours.text()) - workHour);

            var next_row = table_row.next('tr.status');
            var next_row_prev_row = next_row.prev('tr');
            if (next_row_prev_row.attr('id') == table_row.attr('id'))
                next_row.remove();

            table_row.remove();

            if ($('#projectDetailsContainer-table tbody.projectDetailsContainer-fields-container tr').length == 0)
                $('#WorkDayDetails button[type="submit"]').hide();

        });

        $('#projectDetailsContainer-table').on('click', '.deleteTodaysWorkEntry-row', function(e) {
            if (!confirm('Are you sure want to delete your work entry ?'))
                return;

            var delete_span = $(this);
            var data = {
                'workDetailId': delete_span.attr('entryid'),
                'workHour': delete_span.parents('tr').find('td.workHour-field').text(),
                'workDayDate': $('#workDayDate').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'),
            }
            var delete_selected_row = delete_span.parents('tr');
            var workHour = parseFloat(delete_selected_row.find('td.workHour-field').text());

            var deleteTodayWorkEntryStatus = $.ajax({
                url: $('#deleteWorkEntryAPI').val(),
                method: 'post',
                data: data,
                dataType: 'json'
            });
            delete_span.hide();
            delete_span.after('<i class="wait_ fa fa-spinner fa-spin"></i>');

            deleteTodayWorkEntryStatus.done(function(res) {
                //console.log( res );
                if (res.delete == true) {
                    delete_selected_row.fadeOut(1000, function() {
                        $(this).remove();
                    });
                    var totalHours = $('#projectDetailsContainer-table tbody.totalHours-container .totalHours-field');
                    totalHours.text(parseFloat(totalHours.text()) - workHour);
                } else {
                    alert('Ooops! Could not delete your work entry at the moment, Please try again later.');
                    delete_span.siblings('.wait_').remove();
                    delete_span.show();
                }
            });

            deleteTodayWorkEntryStatus.fail(function(res) {
                console.log(res);
                alert('Ooops! Something went wrong with the server, Please try again later.');
                delete_span.siblings('.wait_').remove();
                delete_span.show();
            });

        });

        $('#workDayDate').on('change', function() {
            //console.log( $(this).val() );
            var workDayDate = $(this);
            var data_ = {
                'workDate': workDayDate.val(),
                '_token': $('meta[name="csrf-token"]').attr('content')
            };

            if (user_.length == 1) {
                if (user_.val() == '') {
                    alert('Please Select the user.');
                    return;
                }
                data_['userId'] = user_.val();
            }

            $('#tablework-date').text(workDayDate.val());

            $('#submitting-status').hide();
            var workDayTableBody = $('#projectDetailsContainer-table tbody.todaysWorkEntriesContainer');
            workDayTableBody.html('<tr class="info text-center text-danger"><td></td><td colspan="6">Please Wait..............</td></tr>');

            $('#projectDetailsContainer-table tbody.projectDetailsContainer-fields-container').html('');
            var totalHour_field = $('#projectDetailsContainer-table tbody.totalHours-container .totalHours-field');
            totalHour_field.text(0);

            var getWorkEntriesByDate = $.ajax({
                url: workDayDate.attr('getWorkEntriesByDate-route'),
                method: 'post',
                data: data_,
                dataType: 'json'
            });

            $('#reqDate-wait').show();
            //NProgress.start();
            getWorkEntriesByDate.done(function(res) {
                console.log(res);
                if (res.length == 0) {
                    workDayTableBody.html('<tr class="state_msg"><td colspan="6" class="text-center danger text-danger"> There are no work entries on this date </td></tr>');
                    $('#reqDate-wait').hide();
                    //NProgress.done();
                    return;
                }
                var tableRows = [];
                var totalHour = 0;
                for (let i = 0; i < res.length; i++) {
                    tableRows.push(
                        '<tr>' +
                        '<td><span entryid="' + res[i].workEntryId +
                        '" class="deleteTodaysWorkEntry-row text-warning">&times;</span></td>' +
                        '<td class="project-field" project="' + res[i].projectId + '">' + res[i].projectName + '</td>' +
                        // '<td class="subCategory-field" subCategory="' + res[i].subCategoryId + '">' + res[i].subCategoryName + '</td>' +
                        '<td class="workDetail-field" workDetail="' + res[i].workDetailId + '">' + res[i].workDetailName + '</td>' +
                        '<td class="workHour-field">' + res[i].workHour + '</td>' +
                        '<td class="workComment-field">' + res[i].workComment + '</td>' +
                        '</tr>'
                    );
                    totalHour += res[i].workHour;
                }
                workDayTableBody.html(tableRows.join(''));
                totalHour_field.text(totalHour);

                $('#reqDate-wait').hide();
                //NProgress.done();
            });

            getWorkEntriesByDate.fail(function(res) {
                //NProgress.done();
                workDayTableBody.html('<tr class="state_msg"><td colspan="6" class="text-center danger text-danger"> Something went wrong with the server. Please try again later </td></tr>');
                $('#reqDate-wait').hide();
            });

        });

        if (user_.length == 0)
            $('#workDayDate').trigger('change');
    })();
</script>

@endsection