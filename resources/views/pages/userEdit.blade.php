@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@stop
@section('footer')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/registers.js') }}"></script>
<script>
    $(function() {
        $("#dateOfJoin").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default register-wrapper">
                <div class="panel-heading text-center"> Employee Register Form </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user.update',$user->id) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input name="_method" type="hidden" value="PATCH">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Name</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-2 control-label">E-Mail Address</label>

                            <div class="col-md-10">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-2 control-label">Password</label>
                            <div class="col-md-10">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
                            <div class="col-md-10">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div> -->
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-2 control-label">Full Address</label>
                            <div class="col-md-10">
                                <textarea id="address" row="3" class="form-control" name="address" required>{{ $user->address }}</textarea>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for=phone" class="col-md-2 control-label">Mobile Number</label>
                            <div class="col-md-10">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('contactPerson') ? ' has-error' : '' }}">
                            <label for=contactPerson" class="col-md-2 control-label">Contact Person</label>
                            <div class="col-md-10">
                                <input id="contactPerson" type="text" class="form-control" name="contactPerson" value="{{ $user->contactPerson }}" required>
                                @if ($errors->has('contactPerson'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contactPerson') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=position" class="col-md-2 control-label">Position</label>
                            <div class="col-md-10">
                                <select class="form-control" id="position" name="position">
                                    @foreach($positions as $position )
                                    <option value="{{$position->id}}" {{ $user->positions->first()->id == $position->id ? "selected" : "" }}>{{ $position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- new added -->
                        <div class="form-group{{ $errors->has('dateOfJoin') ? ' has-error' : '' }}">
                            <label for="dateOfJoin" class="col-md-2 control-label">Date of Joining</label>
                            <div class="col-md-10">
                                <input id="dateOfJoin" type="text" class="form-control" name="dateOfJoin" value="{{ explode(' ', $user->date_of_join)[0] }}" required>
                                @if ($errors->has('dateOfJoin'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('dateOfJoin') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <!-- new added -->
                        <div class="form-group{{ $errors->has('isAdmin') ? ' has-error' : '' }}">
                            <label for=isAdmin" class="col-md-2 control-label">Is Admin</label>
                            <div class="col-md-10">
                                <input type="checkbox" value="1" name="isAdmin" id="isAdmin" {{$user->isAdmin == 1 ?'checked':'' }}>
                                @if ($errors->has('isAdmin'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('isAdmin') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group role-group" style="display: none;">
                            <label class="col-md-2 control-label"> Assign Role</label>
                            <div class="col-md-10">
                                @foreach($roles as $role)
                                <div class="form-group">
                                    <div class="col-md-1">
                                        <input type="checkbox" name="roles[]" value="{{$role->id}}" {{$user->hasRole($role->name)?'checked':'' }}>
                                    </div>
                                    <label class="col-md-4">{{$role->name}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group role-group">
                            <label class="col-md-2 control-label"> </label>
                            <div class="col-md-10">
                                <a href="{{ route('userDocument.show',$user->id)}}"><i class="fa fa-upload fa-2x" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update User
                                </button>

                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#changePasswordModal">
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- alll required modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <form class="form-horizontal" method="POST" id="changePasswordForm" action="{{ route('changePassword',$user->id) }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
                                <div class="col-md-10">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Change</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- alll required modal -->
@endsection