@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@stop

@section('content')

@section('footer')
<script  type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/user.js')}}"></script>
<script>
    $(function () {
        $("#dateOfJoin").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>
@stop

<div class="container">
    <!-- <div class="row"> -->
        <div class="col-md-12">
            <h2 class="col-md-3">
                Register User
            </h2>

            <!-- <div class="col-md-2 col-md-offset-7">
                <a href="{{url('register')}}" class="btn btn-primary btn-block"> Create new User</a>
            </div>
            <div class="col-md-3 col-md-offset-7">
                <a href="{{ route('position.create')}}" class="btn btn-danger btn-block"> View/Create User Position</a>
            </div> -->
        </div>
    
    <div class="clearfix"></div>
    <div class="col-md-12">
        <!-- registration form -->
        <script type="text/javascript" src="{{ asset('js/registers.js') }}"></script>
        <div class="col-md-6 register-wrapper">
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
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required tabindex="-1">

                        @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-2 control-label">E-Mail</label>

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
                    <label for="address" class="col-md-2 control-label">Address</label>
                    <div class="col-md-10">
                        <input id="address" row="3" class="form-control" name="address" required value="{{ old('address') }}">
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

                <!-- new added -->
                <div class="form-group{{ $errors->has('dateOfJoin') ? ' has-error' : '' }}">
                    <label for="dateOfJoin" class="col-md-2 control-label">Date of Joining</label>
                    <div class="col-md-10">
                        <input id="dateOfJoin" type="text" class="form-control" name="dateOfJoin" value="{{ old('dateOfJoin') }}" required >
                        @if ($errors->has('dateOfJoin'))
                        <span class="help-block">
                            <strong>{{ $errors->first('dateOfJoin') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <!-- new added -->

                <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                    <label for="position" class="col-md-2 control-label">Position</label>
                    <div class="col-md-5">
                        <select class="form-control" id="position" name="position" required="required">
                        <option value="">Select Position</option>
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
                    <!-- <div class="col-md-5"><a href="{{ route('position.create')}}" class="btn btn-danger" target="_blank"> View/Create User Position</a></div> -->
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
                    <div class="col-md-6 col-md-offset-9">
                        <button type="submit" class="btn btn-primary">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /registration form -->
    <!-- </div><br> -->
    <!-- <div class="row"> -->
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="well-lg bg-success"> Users List</div>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Position</th>
                            <th>Document Upload</th>
                            <th>Edit</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        @if($user->isSuperAdmin == false && !$user->roles()->where('name', 'CEOAdmin')->first())
                        <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->positions->first()->name}}</td>
                            <td> <a href="{{ route('userDocument.show',$user->id)}}"><i class="fa fa-upload fa-2x" aria-hidden="true"></i></a></td>
                            <td><a href="{{ route('user.edit',$user->id)}}"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a></td>
                            <!-- <td><a href="{{ route('user.destroy',['user_id'=>$user->id])}}" ><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td> -->
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table> 
            </div>
        </div>

    </div> 
    <!-- </div> -->
</div>
@endsection
