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
                <form action="{{route('project.store')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name"> Project Name :</label>
                        <input  type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required="required">
                        @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('projectClientName') ? ' has-error' : '' }}">
                        <label for="projectClientName"> Project Client Name :</label>
                        <input  type="text" class="form-control" name="projectClientName" id="projectClientName" value="{{ old('projectClientName')}}"  required="required">
                        @if ($errors->has('projectClientName'))
                        <span class="help-block">
                            <strong>{{ $errors->first('projectClientName') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group  {{ $errors->has('projectLocation') ? ' has-error' : '' }}">
                        <label for="projectLocation"> Project Location :</label>
                        <input  type="text" class="form-control" name="projectLocation" id="projectLocation" value="{{ old('projectClientName')}}" required="required">
                        @if ($errors->has('projectLocation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('projectLocation') }}</strong>
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
                    <div class="form-group {{ $errors->has('supervisor') ? ' has-error' : '' }}">
                        <label for="supervisor"> Project Supervisor :</label>
                        <select class="form-control"  id="supervisor" name="supervisor" required="required">
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} </option>  
                            @endforeach
                        </select>
                        @if ($errors->has('supervisor'))
                        <span class="help-block">
                            <strong>{{ $errors->first('supervisor') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('project_status') ? ' has-error' : '' }}">
                        <label for="project_status">Project Status</label>
                        <select class="form-control"  id="project_status" name="project_status" required="required">
                            @for($i=0; $i<count($projectStatus); $i++)
                                <option>{{ $projectStatus[$i] }} </option>  
                                @endfor
                        </select>
                        @if ($errors->has('project_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('project_status') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Save Project" class="btn btn-block btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
