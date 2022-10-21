@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@stop

@section('footer')
<script  type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script>
    $(function () {
    $("#fromDate").datepicker({
    changeMonth: true,
            changeYear: true,
           
    });
    });
    $(function () {
    $("#toDate").datepicker({
    changeMonth: true,
            changeYear: true,
            
    });
    });
</script>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 panel panel-warning">
            <div class="project-create-wrapper">
                <form action="{{route('subcategory.store')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="project_id" value="{{$project->id}}">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name"> Sub Category Name :</label>
                        <input  type="text" class="form-control" name="name" id="name" value="{{ old('name')}}" required="required">
                        @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('incharge') ? ' has-error' : '' }}">
                        <label for="incharge"> Subcategory Incharge : </label>
                        <select class="form-control"  id="incharge" name="incharge" required="required">
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} </option>  
                            @endforeach
                        </select>
                        @if ($errors->has('incharge'))
                        <span class="help-block">
                            <strong>{{ $errors->first('incharge') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('fromDate') ? ' has-error' : '' }}">
                                <label for="fromDate"> From Date :</label>
                                <input type="text" id="fromDate" name="fromDate" value="{{ old('fromDate')}}">
                                @if ($errors->has('fromDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fromDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('toDate') ? ' has-error' : '' }}">
                                <label for="toDate"> To Date  :</label>
                                <input type="text" id="toDate" name="toDate" value="{{ old('toDate')}}"> 
                                @if ($errors->has('toDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('toDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}"">
                        <label for="description"> Description:</label>
                        <textarea class="form-control" name="description" id="description" required="required">{{ old('description')}}"</textarea>
                        @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('subCategory_status') ? ' has-error' : '' }}"">
                        <label for=subCategory_status">Sub Category  Status</label>
                        <select class="form-control"  id="subCategory_status" name="subCategory_status" required="required">
                            @for($i=0; $i<count($subCategoryStatus); $i++)
                                <option>{{ $subCategoryStatus[$i] }} </option>  
                                @endfor
                        </select>
                        @if ($errors->has('subCategory_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('subCategory_status') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Save SubCategory" class="btn btn-block btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
