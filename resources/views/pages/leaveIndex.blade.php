@extends('layouts.app')
@section('content')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
<style type="text/css">
#leaveYear-table .form-control, #leaveTypeTable .form-control{
    padding: 0px 3px;
    height: 29px;
}
#leaveYear-table td, #leaveTypeTable td{
    cursor: pointer;
    position: relative;
}
#leaveYear-table td i, #leaveTypeTable td i{
    top: 7px;
}
</style>
@stop

@section('footer')
<script src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/leaveIndex.js') }}"></script>
<script type="text/javascript">
    // leave year

    /*$('#leaveYear').datepicker({
    changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy',
            onClose: function(dateText, inst) {
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, 1));
            }
    });
    $('#leaveYear').focus(function () {
        $('.ui-datepicker-calendar').hide();
    });
    // prevent input in request date
    $('#leaveYear').keydown(function (event) {
        event.preventDefault();
    })*/

    // leave year
    $('#leaveFromDate').datepicker({
        dateFormat: 'yy-m-d',
    })
    // prevent input in request date
    $('#leaveFromDate').keydown(function (event) {
        event.preventDefault();
    })

    // leave year
    $('#leaveToDate').datepicker({
        dateFormat: 'yy-m-d',
    })
    // prevent input in request date
    $('#leaveToDate').keydown(function (event) {
        event.preventDefault();
    })

</script>
@stop
<!-- <div class="container"> -->
<div class="container-fluid">
    @if (Session::has('message'))
    <div class="alert alert-success text-center">{{ Session::get('message') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-md-12">
                        {{-- <h3> Leave Type</h3> --}}
                    </div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-type" id="leave-type"><i class="fa fa-plus" aria-hidden="true"></i> Add New Leave Type</button>
                    </div>
                </div>
                <div class="modal fade" id="modal-type" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('leave.store')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                                <input type="text" class="form-control" name="type" value="{{ old('name') }}" id="type" placeholder="Leave Type name eg: casual, sick,etc" required="required" autofocus>
                                                @if ($errors->has('type'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('type') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" name="no_of_days" class="form-control" id="no_of_days" placeholder="No of Days"  autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                                                <textarea name="description" class="form-control" id="description" placeholder="Leave Type description" required="required" autofocus></textarea>
                                                @if ($errors->has('description'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="leave-modal-submit">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-block">Create</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well well-sm">
                <div class="row">
                    
                    <div class="col-md-12">
                        {{-- <h3> Leave Year</h3> --}}
                    </div>
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-year" id="leave-year"><i class="fa fa-plus" aria-hidden="true"></i> Add New Leave Year</button>
                    </div>
                </div>
                <div class="modal fade" id="modal-year" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                            </div>

                            <div class="modal-body">

                                <form action="{{route('leaveYear.creteLeaveYear')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('leaveYear') ? ' has-error' : '' }}">
                                        <input type="text" id="leaveYear" name="leaveYear" placeholder="Year" value="{{ old('leaveYear')}}" class="form-control" required>
                                        @if ($errors->has('leaveYear'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leaveYear') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('leaveFromDate') ? ' has-error' : '' }}">
                                        <input type="text" id="leaveFromDate" name="leaveFromDate" value="{{ old('leaveFromDate')}}" placeholder=" From Date" class="form-control" required>
                                        @if ($errors->has('leaveFromDate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leaveFromDate') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('leaveToDate') ? ' has-error' : '' }}">
                                        <input type="text" id="leaveToDate" name="leaveToDate" placeholder="To Date" value="{{ old('leaveToDate')}}"  class="form-control" required>
                                        @if ($errors->has('leaveToDate'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('leaveToDate') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="leave-modal-submit">
                                        <div class="form-group ">
                                            <button type="submit" class="btn btn-primary btn-block">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well well-sm">
                <div class="row">
                    {{-- <div class="col-md-12">
                        <h3> Leave Applicable</h3>
                    </div> --}}
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-leave-applicable" id="leave-applicable"><i class="fa fa-plus" aria-hidden="true"></i> Make Leave Applicable</button>
                    </div>
                </div>
                <div class="modal fade" id="modal-leave-applicable" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('leaveApplicable.makeLeaveApplicable')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('userName') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="userName" class="col-md-3 col-md-offset-2"> Name : </label>
                                            <div class="col-md-6"> 
                                                <select class="form-control" id="userName" name="userName[]" multiple="multiple" required>
                                                    @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="userNameForResponseContainer">
                                                    {{-- <input type="hidden" name="userNameForResponse"> --}}
                                                </div>
                                            </div>
                                            @if ($errors->has('userName'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('userName') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('leaveYear') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="leaveYear" class="col-md-3 col-md-offset-2"> Leave Year: </label>
                                            <div class="col-md-6"> 
                                                <select class="form-control" id="leaveYear" name="leaveYear">
                                                    @foreach($leaveYears as $leaveYear)
                                                    <option value="{{$leaveYear->id}}">{{$leaveYear->year}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('leaveYear'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('leaveYear') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('leaveType') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="leaveType" class="col-md-3 col-md-offset-2"> Leave Type: </label>
                                            <div class="col-md-6"> 
                                                <select class="form-control" id="leaveType" name="leaveType[]" multiple>
                                                    @foreach($leaveTypes as $leaveType)
                                                    <option value="{{$leaveType->id}}|{{$leaveType->leaveDays->no_of_days}}">{{$leaveType->type}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('leaveType'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('leaveType') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-offset-5 col-md-6 ">
                                            <button type="submit" class="btn btn-primary btn-block">Save </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Leave Types</div>
                <div class="panel-body">
                    <table class="table" id="leaveTypeTable" data-removeroute={{ route('leave.removeLeaveType') }}>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Days</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveTypes as $leaveType)
                            <tr data-leavetype="{{ $leaveType->id }}">
                                <td class="ltype">{{$leaveType->type}}</td>
                                <td class="ldescription">{{$leaveType->description}}</td>
                                <td class="lnodays">{{$leaveType->leaveDays->no_of_days}}</td>
                                <td>
                                    <!--  -->
                                    <button type="button" class="btn edit-leaveType btn-xs btn-info"> <i class="fa fa-edit"></i> </button>
                                    <!--  -->
                                    <button type="button" class="btn remove-leaveType btn-xs btn-info"> <i class="fa fa-times"></i> </button>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">total Leave Days</th>
                                <th>{{$totalLeaveDays}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
            <div class="panel-heading">Leave Years</div>
                <div class="panel-body">
                    <table class="table" id="leaveYear-table" data-removeroute={{ route('leave.removeLeaveYear') }}>
                        <thead>
                            <tr>
                                <th> Leave Year</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveYears as $leaveYear)
                            <tr data-yearid="{{$leaveYear->id}}">
                                <td class="year_">{{$leaveYear->year}}</td>
                                <td class="from_date_">{{$leaveYear->from_date}}</td>
                                <td class="to_date_">{{$leaveYear->to_date}}</td>
                                <td>
                                    <!--  -->
                                    <button type="button" class="btn edit-leaveYear btn-xs btn-info"> <i class="fa fa-edit"></i> </button>
                                    <!--  -->
                                    <button type="button" class="btn remove-leaveYear btn-xs btn-info"> <i class="fa fa-times"></i> </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">Applicable Users List</div>
                <div class="panel-body">
                    <table class="table" id="leaveApplicableTable" data-removeroute={{ route('leave.removeLeaveApplicableUser') }}>
                        <thead>
                            <tr>
                                <th> Employee Name</th>
                                <th>Year</th>
                                <th>Leave Type</th>
                                <th> Available days</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaveApplicables as $leaveApplicable)
                            <tr data-leaveapplicableid="{{ $leaveApplicable->id }}">
                                <td>{{$leaveApplicable->user->name}}</td>
                                <td>{{$leaveApplicable->leaveYear->year}}</td>
                                <td>{{$leaveApplicable->leaveType->type}}</td>
                                <td>{{$leaveApplicable->remaining_days}}</td>
                               <td><button type="button" class="btn remove-leaveApplicableUser btn-xs btn-info"> <i class="fa fa-times"></i> </button></td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function(){
        $('#userName, #leaveType').select2();

        $('#userName').on('change',function(){
            var userNameSelector = $(this);
            var userNameForResponse = '';
            for(let userName of userNameSelector.val()){
                userNameForResponse += '<input type="hidden" value="'+$('#userName option[value="'+userName+'"]').text()+'" name="userNameForResponse_'+userName+'">';
            }
            $('#userNameForResponseContainer').html(userNameForResponse);
            //$('input[name="userNameForResponse"]').val()
        })

        $('#leaveTypeTable').on('click','.ltype,.ldescription,.lnodays',function(e){
            //console.log(this.querySelector('input') == null);
            if(this.querySelector('input') != null)
                return;

            var classType = e.target.className;
            var prevData = this.innerText.trim();
            if(classType == 'lnodays'){
                this.innerHTML = '<input type="number" classtype="no_of_days" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }else if(classType == 'ltype'){
                this.innerHTML = '<input type="text" classtype="type" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }else if(classType == 'ldescription'){
                this.innerHTML = '<input type="text" classtype="description" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }else{
                this.innerHTML = '<input type="text" classtype="'+classType+'" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }
            
            this.querySelector('input').focus();
        })

        $('#leaveTypeTable').on('blur','input',function(){
            var input_ = $(this);
            var data_ = {};
                data_['updateLeave'] = {'value':input_.val(),'key':input_.attr('classtype')};
                // data_['leaveTypeId'] = input_.parents('tr').attr('leavetype');
                data_['leaveTypeId'] = input_.parents('tr').data('leavetype');
                data_['_token'] = $('meta[name="csrf-token"]').attr('content');
            var editLeaveType = $.ajax({
                url: '{{ route('Leave.editLeaveType') }}',
                method: 'post',
                data: data_
            });

            input_.after('<i class="fa fa-2x fa-spinner fa-spin position-absolute"></i>');
            editLeaveType.done(function(res){
                //console.log(res);
                if(res[0] == 1){
                    if(input_.attr('classtype') == 'no_of_days'){
                        let totalLeaveDaysContainer = $('#leaveTypeTable tbody tr:last-child th:last-child');
                        totalLeaveDaysContainer.text( Number(totalLeaveDaysContainer.text()) - Number(input_.attr('old-data')) + Number(input_.val()) );
                    }
                    input_.parent().text( input_.val() );
                }else
                    input_.parent().text( input_.attr('old-data') );
            });
            
            editLeaveType.fail(function(res){
                //console.log(res);
                input_.parent().text( input_.attr('old-data') );
            });

            
        });


        $('#leaveYear-table tbody').on('click','.year_,.from_date_,.to_date_',function(e){
            if(this.querySelector('input') != null)
                return;
            
            var classType = e.target.className;
            var prevData = this.innerText.trim();

            if(classType == 'year_'){
                this.innerHTML = '<input type="text" classtype="year" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }else if(classType == 'from_date_'){
                this.innerHTML = '<input type="date" classtype="from_date" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }else if(classType == 'to_date_'){
                this.innerHTML = '<input type="date" classtype="to_date" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }
            else{
                this.innerHTML = '<input type="text" classtype="'+classType+'" class="form-control" old-data="'+prevData+'" value="'+prevData+'">';
            }

            this.querySelector('input').focus();

        });

        $('#leaveYear-table').on('blur','input',function(){
            var input_ = $(this);
            var data_ = {};
            data_['_token'] = $('meta[name="csrf-token"]').attr('content');
            data_['leaveYearId'] = input_.parents('tr').data('yearid');
            data_['updateLeaveYear'] = {'value':input_.val(),'key':input_.attr('classtype')};

            input_.after('<i class="fa fa-2x fa-spinner fa-spin position-absolute"></i>');
            var editLeaveYear = $.ajax({
                url: '{{ route('Leave.editLeaveYear') }}',
                method: 'post',
                data: data_
            });

            editLeaveYear.done(function(res){
                //console.log(res);
                if(res[0] == 1){
                    input_.parent().text( input_.val() );
                }else
                    input_.parent().text( input_.attr('old-data') );
            });
            
            editLeaveYear.fail(function(res){
                //console.log(res);
                input_.parent().text( input_.attr('old-data') );
            });

        });

        /*
        * remove leave type
        */
        $('#leaveTypeTable').on('click','.remove-leaveType',function(e){
            if(confirm('Are you sure want to remove this leave type ?')){
                var button = $(this);
                button.after('<i class="fa fa-spin fa-spinner"></i>');
                button.hide();
                var removeLeaveType = $.ajax({
                    'url': $('#leaveTypeTable').data('removeroute'),
                    'method': 'post',
                    'data': {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        leaveTypeId: button.parents('tr').data('leavetype')
                        }
                });

                removeLeaveType.done(function(res){
                    if(res[0] == 1)
                        button.parents('tr').remove();
                    
                    alert(res[1]);
                    button.show();
                    button.siblings('i').remove();
                });

                removeLeaveType.fail(function(res){
                    button.show();
                    button.siblings('i').remove();
                });
            }
        })

        $('#leaveYear-table').on('click','.remove-leaveYear',function(){
            if(confirm('Are you sure want to remove this leave year ?')){
                var button = $(this);
                button.after('<i class="fa fa-spin fa-spinner"></i>');
                button.hide();
                var removeLeaveYear = $.ajax({
                    'url': $('#leaveYear-table').data('removeroute'),
                    'method': 'post',
                    'data': {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        leaveYearId: button.parents('tr').data('yearid')
                        }
                });

                removeLeaveYear.done(function(res){
                    if(res[0] == 1)
                        button.parents('tr').remove();
                    
                    alert(res[1]);
                    button.show();
                    button.siblings('i').remove();
                });

                removeLeaveYear.fail(function(res){
                    button.show();
                    button.siblings('i').remove();
                });
            }
        })

        $('#leaveApplicableTable').on('click','.remove-leaveApplicableUser',function(){
            if(confirm('Are you sure want to remove this applicable user ?')){
                var button = $(this);
                button.after('<i class="fa fa-spin fa-spinner"></i>');
                button.hide();
                var removeLeaveApplicableUser = $.ajax({
                    'url': $('#leaveApplicableTable').data('removeroute'),
                    'method': 'post',
                    'data': {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        leaveApplicableId: button.parents('tr').data('leaveapplicableid')
                        }
                });

                removeLeaveApplicableUser.done(function(res){
                    if(res[0] == 1)
                        button.parents('tr').remove();
                    
                    alert(res[1]);
                    button.show();
                    button.siblings('i').remove();
                });

                removeLeaveApplicableUser.fail(function(res){
                    button.show();
                    button.siblings('i').remove();
                });
            }
        })


        $('.edit-leaveType').on('click', function(e) {
            e.preventDefault()
            resetModalField()

            var input_ = $(this),
                inputPar = input_.parents('tr'),
                data_ = {};
            let typeLeave = inputPar.children()[0].textContent,
                descLeave = inputPar.children()[1].textContent,
                dayLeave = inputPar.children()[2].textContent

            $('#type').val(typeLeave)
            $('#no_of_days').val(dayLeave)
            $('#description').val(descLeave)

            inputTex = document.createElement('input')
            inputTex.setAttribute("type", "hidden");
            inputTex.setAttribute("id", "leaveTypeId");
            inputTex.setAttribute("name", "leaveTypeId");
            inputTex.value = inputPar.data('leavetype')
            $('#modal-type form').append(inputTex)

            // data_['updateLeave'] = {'value':input_.val(),'key':input_.attr('classtype')};
            // data_['leaveTypeId'] = inputPar.attr('leavetype');
            data_['leaveTypeId'] = inputPar.data('leavetype');
            data_['_token'] = $('meta[name="csrf-token"]').attr('content');

            $('#modal-type button[type="submit"]').text('Update')
            $('#modal-type').modal('show')
        })

        $('#leave-type').on('click', function(e){
            resetModalField()
        })

        $('.edit-leaveYear').on('click', function(e) {
            e.preventDefault()
            resetYearModalField()

            var input_ = $(this),
                inputPar = input_.parents('tr'),
                data_ = {};
            let typeLeave = inputPar.children()[0].textContent,
                descLeave = inputPar.children()[1].textContent,
                dayLeave = inputPar.children()[2].textContent

            $('#leaveYear').val(typeLeave)
            $('#leaveFromDate').val(descLeave)
            $('#leaveToDate').val(dayLeave)

            inputTex = document.createElement('input')
            inputTex.setAttribute("type", "hidden");
            inputTex.setAttribute("id", "leaveYearId");
            inputTex.setAttribute("name", "leaveYearId");
            inputTex.value = inputPar.data('yearid')
            $('#modal-year form').append(inputTex)

            data_['leaveYearId'] = inputPar.data('yearid');
            data_['_token'] = $('meta[name="csrf-token"]').attr('content');

            $('#modal-year button[type="submit"]').text('Update')
            $('#modal-year').modal('show')
        })

        $('#leave-year').on('click', function(e){
            resetYearModalField()
        })

        function resetModalField() {
            $('#leaveTypeId').remove()
            $('#type').val('')
            $('#no_of_days').val('')
            $('#description').val('')
            $('#modal-type button[type="submit"]').text('Create')
        }

        function resetYearModalField() {
            $('#leaveYear').val('')
            $('#leaveFromDate').val('')
            $('#leaveToDate').val('')
            $('#modal-year button[type="submit"]').text('Create')
        }
        

    })()
</script>

@endsection
