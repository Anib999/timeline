@extends('layouts.app')
@section('content')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
<style type="text/css">
.deleteHoliday_day{
    font-weight: bold;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
}

</style>
@stop

<div class="container-fluid">
    <div class="row">
        @if (Session::has('message'))
        <div class="alert alert-info text-center">{{ Session::get('message') }}</div>
        @endif

        <div class="col-md-3">
            <div class="well well-sm">
                <div class="row">
                    {{-- <div class="col-md-12">
                        <h3> Holiday Year</h3>
                    </div> --}}
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-year" id="Holiday-year"><i class="fa fa-plus" aria-hidden="true"></i> Add New Holiday Year</button>
                    </div>
                </div>

                <!-- Holiday Year modal -->
                <div class="modal fade" id="modal-year" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                <h4 class="modal-title">Holiday Year</h4>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('holiday.createHoliday')}}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="year" class="col-md-3 col-md-offset-2"> Holiday Year </label>
                                            <div class="col-md-6">
                                                <input type="text" id="year" name="year" placeholder="Year" class="form-control" required>
                                            </div>
                                            @if ($errors->has('year'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('year') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('from_date') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="from_date" class="col-md-3 col-md-offset-2"> From Date </label>
                                            <div class="col-md-6">
                                                <input type="text" id="from_date" name="from_date" placeholder="From Date" class="form-control" required>
                                            </div>
                                            @if ($errors->has('from_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('from_date') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('to_date') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="to_date" class="col-md-3 col-md-offset-2"> To Date </label>
                                            <div class="col-md-6">
                                                <input type="text" id="to_date" name="to_date" placeholder="To Date" class="form-control" required>
                                            </div>
                                            @if ($errors->has('to_date'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('to_date') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="form-group col-md-offset-5 col-md-6 ">
                                            <button type="submit" class="btn btn-primary btn-block">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- end of holiday year modal -->

            </div>
        </div>

        <div class="col-md-4">
            <div class="well well-sm">
                <div class="row">
                    {{-- <div class="col-md-12">
                        <h3> Holiday Type</h3>
                    </div> --}}
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-type" id="holiday-type"><i class="fa fa-plus" aria-hidden="true"></i> Add Holiday Type</button>
                    </div>
                </div>

                <!-- Holiday type modal -->
                <div class="modal fade" id="modal-type" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                <h4 class="modal-title">Holiday Type</h4>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('holiday.store')}}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="form-group {{ $errors->has('type_name') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="type_name" class="col-md-3 col-md-offset-2"> Holiday Type: </label>
                                            <div class="col-md-6"> 
                                                <input type="text" class="form-control" name="type_name" value="{{ old('type_name') }}" id="type_name" placeholder="Holiday Type name" required="required" autofocus>
                                            </div>
                                           @if ($errors->has('type_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="description" class="col-md-3 col-md-offset-2"> Description </label>
                                            <div class="col-md-6">
                                                <textarea name="description" class="form-control" id="description" placeholder="Holiday Type description" required="required" autofocus></textarea>
                                            </div>
                                           @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                        
                                    <div class="form-group row">
                                        <div class="form-group col-md-offset-5 col-md-6 ">
                                            <button type="submit" class="btn btn-primary btn-block">Create</button>
                                        </div>
                                    </div>
                                        
                                    
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /end of holiday type modal -->

            </div>
        </div>

        <div class="col-md-5">
            <div class="well well-sm">
                <div class="row">
                    {{-- <div class="col-md-12">
                        <h3> Holiday Applicable</h3>
                    </div> --}}
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-Holiday-applicable" id="Holiday-applicable"><i class="fa fa-plus" aria-hidden="true"></i> Add Holiday</button>
                    </div>
                </div>

                <!-- Holiday date Modal -->
                <div class="modal fade" id="modal-Holiday-applicable" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" style="opacity:0.8" data-dismiss="modal"><i class="fa fa-times"></i></button>
                                <h4 class="modal-title">Holiday Date</h4>
                            </div>

                            <div class="modal-body">
                                <form action="{{route('holiday.makeHolidayApplicable')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('holidayYear') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="holidayYear" class="col-md-3 col-md-offset-2"> Holiday Year: </label>
                                            <div class="col-md-6"> 
                                                <select class="form-control" id="holidayYear" name="holidayYear">
                                                    @foreach($holidayYears as $holidayYear)
                                                    <option value="{{$holidayYear->id}}">{{$holidayYear->year}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('holidayYear'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('holidayYear') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('holidayType') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="holidayType" class="col-md-3 col-md-offset-2"> Holiday Type: </label>
                                            <div class="col-md-6"> 
                                                <select class="form-control" id="holidayType" name="holidayType">
                                                    @foreach($holidayTypes as $holidayType)
                                                    <option value="{{$holidayType->id}}">{{$holidayType->type_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('holidayType'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('holidayType') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('holidayDay') ? ' has-error' : '' }}">
                                        <div class="row">
                                            <label for="holidayDay" class="col-md-3 col-md-offset-2"> Holiday Date: </label>
                                            <div class="col-md-6"> 
                                                <input type="text" name="holidayDay" id="holidayDay" class="form-control" required="required">
                                            </div>
                                            @if ($errors->has('holidayDay'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('holidayDay') }}</strong>
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
                <!-- end of holiday date modal -->

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">Holiday Years</div>
                <div class="panel-body">
                    <table class="table" id="holiYear" data-removehol={{ route('holiday.removeHolidayYear') }}>
                        <thead>
                            <tr>
                                <th>Holiday Year</th>
                                <th>From</th>
                                <th>To</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidayYears as $holidayYear)
                            <tr data-leavetype="{{ $holidayYear->id }}">
                                <td> {{$holidayYear->year}}</td>
                                <td> {{$holidayYear->from_date}}</td>
                                <td> {{$holidayYear->to_date}}</td>
                                <td>
                                    <!-- remove button -->
                                    <button type="button" class="btn remove-HolidayYear btn-xs btn-info"> <i class="fa fa-times"></i> </button>
                                    <!-- remove button -->
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
                <div class="panel-heading">Holiday Types</div>
                <div class="panel-body">
                    <table class="table" id="holiType" data-removehol={{ route('holiday.removeHolidayType') }}>
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidayTypes as $holidayType)
                            <tr data-holidaytype="{{ $holidayType->id }}">
                                <td>{{$holidayType->type_name}}</td>
                                <td>{{$holidayType->description}}</td>
                                <td>
                                    <!-- remove button -->
                                    <button type="button" class="btn remove-HolidayType btn-xs btn-info"> <i class="fa fa-times"></i> </button>
                                    <!-- remove button -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="panel panel-primary">
                <div class="panel-heading">Holiday Dates</div>
                <div class="panel-body">
                    <table id="holidayDay-table" class="table" delete-holiday-dayroute="{{ route('holiday.deleteHoliday_day') }}">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Year</th>
                                <th>Day</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidayApplicables as $holidayApplicable)
                            <tr holiday_day_id="{{ $holidayApplicable->id }}">
                                <td>{{$holidayApplicable->holidaytype->type_name}}</td>
                                <td>{{$holidayApplicable->holidayyear->year}}</td>
                                <td>{{$holidayApplicable->holidayDay}}</td>
                                <td><span class="deleteHoliday_day">&times;</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript">


    $(function() {
        $('#holidayDay').datepicker({
            dateFormat: 'yy-m-d'
        })
        
        $('#from_date, #to_date').datepicker({
            dateFormat: 'yy-mm-d'
        })

        // prevent input in request date
        $('#holidayDay').keydown(function (event) {
            event.preventDefault();
        })

        // $('#year').datepicker({
        //     changeYear: true,
        //     showButtonPanel: true,
        //     dateFormat: 'yy',
        //     onClose: function(dateText, inst) {
        //                 var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        //                 $(this).datepicker('setDate', new Date(year, 1));
        //             }
        // });

        // $('#year').focus(function () {
        //     $('.ui-datepicker-calendar').hide();
        // });
    });

    (function(){
        $('#holidayDay-table').on('click','.deleteHoliday_day',function(){
            if( !confirm('Are you sure want to delete this holiday day ?') )
                return;
            var delete_btn = $(this);
            var holiday_row = delete_btn.parents('tr');
            var deleteHoliday_day_route = $('table[delete-holiday-dayroute]').attr('delete-holiday-dayroute');
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'holiday_dayID': holiday_row.attr('holiday_day_id')
            };

            var deleteHoliday_day_state = $.ajax({
                url: deleteHoliday_day_route,
                method: 'post',
                data: data,
                dataType: 'json'
            });
            
            delete_btn.hide();
            delete_btn.after('<i class="wait_ fa fa-spinner fa-spin"></i>');

            deleteHoliday_day_state.done(function(res){
                if(res.delete == true){
                    holiday_row.fadeOut(1000,function(){
                        $(this).remove();
                    });
                }else{
                    alert('Could not delete holiday day. Please try again later');
                    delete_btn.siblings('.wait_').remove();
                    delete_btn.show();
                }
            });

            deleteHoliday_day_state.fail(function(res){
                console.log(res);
                alert('Something went wrong with the server. Please try again later.');
                delete_btn.siblings('.wait_').remove();
                delete_btn.show();
            });

        });

        $('.remove-HolidayYear').on('click', function(e){
            e.preventDefault()
            if(confirm('Are you sure want to remove this holiday year ?')){
                var button = $(this);
                button.after('<i class="fa fa-spin fa-spinner"></i>');
                button.hide();

                let ajaxRemoveHolYear = $.ajax({
                    'url': $('#holiYear').data('removehol'),
                    'method': 'post',
                    'data': {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        holidayId: button.parents('tr').data('leavetype')
                    }
                })

                ajaxRemoveHolYear.done(function(res){
                    if(res.delete == 1)
                        button.parents('tr').remove();
                    
                    alert('success');
                    button.show();
                    button.siblings('i').remove();
                });

                ajaxRemoveHolYear.fail(function(res){
                    button.show();
                    button.siblings('i').remove();
                });
            }
        })

        $('.remove-HolidayType').on('click', function(e){
            e.preventDefault()
            if(confirm('Are you sure want to remove this holiday type ?')){
                var button = $(this);
                button.after('<i class="fa fa-spin fa-spinner"></i>');
                button.hide();
                
                let ajaxRemoveHolYear = $.ajax({
                    'url': $('#holiType').data('removehol'),
                    'method': 'post',
                    'data': {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        holidayTypeId: button.parents('tr').data('holidaytype')
                    }
                })

                ajaxRemoveHolYear.done(function(res){
                    if(res.delete == 1)
                        button.parents('tr').remove();
                    
                    alert('success');
                    button.show();
                    button.siblings('i').remove();
                });

                ajaxRemoveHolYear.fail(function(res){
                    button.show();
                    button.siblings('i').remove();
                });
            } 
        })
        
    })()

</script>
@endsection