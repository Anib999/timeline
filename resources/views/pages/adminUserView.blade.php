@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-responsive">
                <thead class="bg-primary">
                    <tr>
                        <th> Employee Id</th>
                        <th> Employee Name</th>
                        <th> Username</th>
                        <th> Employee Position</th>
                        <th> View Role and Documents</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    @if($user->isSuperAdmin == false)
                    <tr>
                        <td>{{ $user->id}}</td>
                        <td>{{ $user->name}}</td>
                        <td>{{ $user->username}}</td>
                        <td>{{$user->roles ? $user->positions->first()->name : 'No role'}}</td>
                        <td> <a href="{{route('AdminUserView.show',$user->id)}}"><i class="fa fa-eye fa-2x" aria-hidden="true"></i></a></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection
