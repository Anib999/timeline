@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@endsection

@section('content')

<div class="container" id="notifications-container">
    <div class="panel panel-default">
    <div class="panel-heading">
        <h3>Notifications</h3>
        
    </div>
        <div class="panel-body">
        <ul class="list-group notifications">
        @foreach ($notifications_v as $notification_v)
            <li class="list-group-item {{-- ($notification_v->status == 1)?'notifi-success':'notifi-danger' --}}" notification-id="{{ $notification_v->id }}">
                <small class="clearfix">
                    @if($notification_v->messageType == 1)
                        Check In
                    
                    @elseif($notification_v->messageType == 2)
                        Check Out
                    
                    @elseif($notification_v->messageType == 3)
                        Check In/Out
                
                    @elseif($notification_v->messageType == 4)
                        Leave
                    @endif
                </small>
                <span class="notofication-msg">
                    {!! $notification_v->message !!}
                </span>
                <div class="notification-actions">
                    @if($notification_v->viewStatus == 1)
                        <small class="clearfix pull-right"><a class="markReadStatus" status="0" href="#">Mark as unread</a></small>
                    @else
                        <small class="clearfix pull-right"><a class="markReadStatus" status="1" href="#">Mark as Read</a></small>
                    @endif
                </div>
            </li>
        @endforeach
        </ul>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
@endsection