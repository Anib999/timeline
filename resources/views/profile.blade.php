@extends('layouts.app')
@section('content')
@if($user->isSuperAdmin == false)
<div class="container">
    <div class="row">
        <div class="col-md-12">
           
            <div class="panel panel-default profile-header">
                <img src="{{ asset('/photo/').'/'.$user->profile}}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
                <h2>
                    {{ $user->name }}'s Profile 
                    @if(Auth::user()->hasRole('CEOAdmin'))
                        <a href="{{ route('user.edit',$user->id)}}"><i class="fa fa-pencil"></i></a>
                    @endif
                </h2>
                <form enctype="multipart/form-data" action="{{route('profile.store')}}" method="POST">
                    <label>Update Profile Image</label>
                    <input type="file" name="profile">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" class="pull-right btn btn-lg btn-primary">
                </form>
            </div>
        </div>
    </div>

    <div class="detail-information panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2> Update Password </h2>
                </div>
                <div>
                    <form action="{{ route('updatePassword') }}" method="post" class="form-horizontal">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">New Password</label>
                            <div class="col-md-8">
                                <input type="password" required="required" name="password" class="form-control">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Retype Password</label>
                            <div class="col-md-8">
                                <input type="password" required="required" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Old Password</label>
                            <div class="col-md-8">
                                <input type="password" required="required" name="oldPassword" class="form-control">
                                @if ($errors->has('oldPassword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('oldPassword') }}</strong>
                                    </span>
                                @endif

                                @if ($errors->has('UpdateError'))
                                    <span class="help-block alert alert-danger">
                                        <strong>{{ $errors->first('UpdateError') }}</strong>
                                    </span>
                                @endif

                                @if (Session::has('success'))
                                    <span class="help-block alert alert-success">
                                        <strong>{{ Session::get('success') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10">
                                <button class="btn btn-primary pull-right">Update</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="detail-information panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <div class="heading">
                    <h2> Employee Details </h2>
                </div>
                <div class="col-md-4">
                    <h3>Name :</h3>
                    <p>{{Auth::user()->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Email :</h3>
                    <p>{{Auth::user()->email}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Address :</h3>
                    <p>{{Auth::user()->address}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Phone no. :</h3>
                    <p>{{Auth::user()->phone}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Contact Person Details :</h3>
                    <p>{{Auth::user()->contactPerson}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Position :</h3>
                    <p>{{Auth::user()->positions->first()->name}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection