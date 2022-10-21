@extends('layouts.app')
@section('footer')
<script type="text/javascript" src="{{ asset('js/registers.js') }}"></script>

@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default register-wrapper">
                <div class="panel-heading text-center"> Employee Register Form </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Name</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-2 control-label">Username</label>

                            <div class="col-md-10">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-2 control-label">E-Mail Address</label>

                            <div class="col-md-10">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
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
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
                            <div class="col-md-10">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-2 control-label">Full Address</label>
                            <div class="col-md-10">
                                <textarea id="address" row="3" class="form-control" name="address" required >{{ old('address') }}</textarea>
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-2 control-label">Mobile Number</label>
                            <div class="col-md-10">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required >
                                @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('contactPerson') ? ' has-error' : '' }}">
                            <label for="contactPerson" class="col-md-2 control-label">Contact Person</label>
                            <div class="col-md-10">
                                <input id="contactPerson" type="text" class="form-control" name="contactPerson" value="{{ old('contactPerson') }}" required >
                                @if ($errors->has('contactPerson'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('contactPerson') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                            <label for="position" class="col-md-2 control-label">Position</label>
                            <div class="col-md-10">
                                <select class="form-control" id="position" name="position">
                                    @foreach($positions as $position )
                                    <option value="{{ $position->id }}" >{{ $position->name}}</option>  
                                    @endforeach
                                </select>
                                @if ($errors->has('position'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('position') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('isAdmin') ? ' has-error' : '' }}">
                            <label for="isAdmin" class="col-md-2 control-label">Is Admin</label>
                            <div class="col-md-10">
                                <input type="checkbox" value="1" name="isAdmin" id="isAdmin">
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
                                        <input type="checkbox" name="roles[]" value="{{$role->id}}">
                                    </div>
                                    <label class="col-md-4">{{$role->name}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
