@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@stop

@section('footer')
<script  type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
<script>
    $(function () {
    $("#fromDate").datepicker();
    });
    $(function () {
    $("#toDate").datepicker();
    });
</script>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 panel panel-danger">
            <div class="project-create-wrapper">
                <form action="{{route('job.update',$job->id)}}" method="POST">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}">
                    <input type="hidden" name="project_id" id="project_id" value="{{$job->project_id}}">
                    <input type="hidden" name="sub_category_id" id="sub_category_id" value="{{$job->sub_category_id }}">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name"> Job Name :</label>
                        <input  type="text" class="form-control" name="name" value="{{$job->name}}" id="name" required="required">
                        @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group {{ $errors->has('fromDate') ? ' has-error' : '' }}">
                                <label for="fromDate"> From Date :</label>
                                <input type="text" id="fromDate" name="fromDate" value="{{$job->fromDate}}">
                                @if ($errors->has('fromDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fromDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('toDate') ? ' has-error' : '' }}">
                                <label for="toDate"> To Date  :</label>
                                <input type="text" id="toDate" name="toDate" value="{{$job->toDate}}"> 
                                @if ($errors->has('toDate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('toDate') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group{{ $errors->has('hourTime') ? ' has-error' : '' }}">
                                <label for="hourTime"> Hour: </label>
                                <input  type="text" maxlength="8" size="8"  name="hourTime"  value="{{$job->hourTime }}" id="hourTime" required="required">
                                @if ($errors->has('hourTime'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('hourTime') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <label for="description"> Description:</label>
                        <textarea class="form-control" name="description" id="description" required="required"> {{ $job->description }}</textarea>
                        @if ($errors->has('description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                        <label for="detail"> Job Detail</label>
                        <textarea class="form-control" name="detail" id="detail" required="required"> {{ $job->detail }}</textarea>
                        @if ($errors->has('detail'))
                        <span class="help-block">
                            <strong>{{ $errors->first('detail') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group{{ $errors->has('incharge') ? ' has-error' : '' }}">
                                <label for="incharge"> Job Incharge : </label>
                                <select class="form-control"  id="incharge" name="incharge" required="required">
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ $job->incharge == $user->id ? "selected" : "" }}>{{$user->name}} </option>  
                                    @endforeach
                                </select>
                                @if ($errors->has('incharge'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('incharge') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group{{ $errors->has('assignedto') ? ' has-error' : '' }}">
                                <label for="assignedto"> Job Assign To : </label>
                                <select class="form-control"  id="assignedto" name="assignedto" required="required">
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ $job->assignedto == $user->id ? "selected" : "" }}>{{$user->name}} </option>  
                                    @endforeach
                                </select>
                                @if ($errors->has('assignedto'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('assignedto') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group{{ $errors->has('job_status') ? ' has-error' : '' }}">
                                <label for=job_status">Job  Status</label>
                                <select class="form-control"  id="job_status" name="job_status" required="required">
                                    @for($i=0; $i<count($jobStatus); $i++)
                                        <option>{{ $jobStatus[$i] }} </option>  
                                        @endfor
                                </select>
                                @if ($errors->has('job_status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('job_status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update Job" class="btn btn-block btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

