<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <script src="https://use.fontawesome.com/a6c9eed133.js"></script> -->
        <title> Timeline </title>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css" >

        <link href="{{ asset('datatable/css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" >
        <link href="{{ asset('select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" >

        <link rel="stylesheet" type="text/css" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('nprogress/nprogress.css') }}">

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }} "></script>
        <script type="text/javascript" src="{{ asset('datatable/js/jquery.dataTables.js') }} "></script>
        <script type="text/javascript" src="{{ asset('nprogress/nprogress.js') }} "></script>
        <script type="text/javascript" src="{{ asset('select2/js/select2.full.min.js') }} "></script>

        @yield('head')
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top" style="min-height:88px;">
                <div class="container-fluid">
                    <div class="navbar-header" style="float:left;">
                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false" style="margin-top:27px;">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ url('/home') }}">
                        <img src="{{ asset('images/reportico100.png') }}" class="img-responsive pull-left smallLogo">
                            
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="app-navbar-collapse" style="margin-top:20px; float:right;">
                        <!-- Left Side Of Navbar -->
                        @include('layouts.topnav')

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            @if(Auth::check())
                            <!-- Notifications  -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="position:relative; padding-left:50px;">
                                    <i class="fa fa-bell" aria-hidden="true"></i> Notifications <span class="badge " style="background-color: #b94a48;" id="notification_count"> {{ count($notifications)}}</span>
                                </a>
                                
                                <ul class="dropdown-menu notifications" id="messages" readStatus-route="{{ route('changeNotificationStatus') }}">
                                    
                                    @foreach ($notifications as $notification)
                                        <li notification-id="{{ $notification->id }}" class="list-group-item {{ ($notification->status == 1)?'notifi-success':'notifi-danger' }}">
                                            <small class="clearfix">
                                                @if($notification->messageType == 1)
                                                    Check In
                                                
                                                @elseif($notification->messageType == 2)
                                                    Check Out
                                                
                                                @elseif($notification->messageType == 3)
                                                    Check In/Out
                                            
                                                @elseif($notification->messageType == 4)
                                                    Leave
                                                @endif
                                            </small>
                                            <span class="notofication-msg">
                                                {!! $notification->message !!}
                                            </span>
                                            <div class="notification-actions">
                                                <small class="clearfix pull-right"><a class="markReadStatus" status="1" href="#">Mark as Read</a></small>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item">
                                        <a href="{{ route('viewAllNotification') }}" class="text-center">View All Notifications</a>
                                    </li>
                                </ul>
                                
                            </li>
                            @endif
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="position:relative; padding-left:50px;">
                                    <img src="{{ asset('/photo/').'/'. Auth::user()->profile}}" style="width:32px; height:32px; position:absolute; top:2px; left:10px; border-radius:50%">

                                    {{ Auth::user()->name }} 

                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li> <a href="{{route('profile.index')}}"> <i class="fa fa-btn fa-user"></i> Profile </a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            <i class="fa fa-btn fa-sign-out"></i> Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            @yield('content')
        </div>
        <footer class="footer-wrapper">
            <div class="container">
                <div class="col-md-12">
                    <p class="text-center"> &COPY; {{ date('Y') }} - Urja Infra and Construction</p>
                </div>
            </div>  
        </footer>
        <!-- Scripts -->

        
        @yield('footer')

        <script type="text/javascript">
             //$(document).load(function(){
                 NProgress.start();
             //});

            $(document).ready(function(){
                $('input.hasDatepicker').attr('autocomplete','off');
                //$('select').select2();
                $('[data-toggle="tooltip"]').tooltip();
                NProgress.done();
                
                var markRead_route = $('#messages').attr('readStatus-route');
                $('.notifications').on('click','a.markReadStatus',function(e){
                    e.preventDefault();//console.log(e);
                    var actionBtn = $(this);
                    var NotificationStatus = actionBtn.attr('status');
                    var data = {
                        'notificationID': actionBtn.parents('li').attr('notification-id'),
                        'viewStatus': NotificationStatus,
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    }

                    var status = $.ajax({
                        url: markRead_route,
                        method: 'post',
                        data: data,
                        dataType: 'json'
                    });

                    /**
                    * Select _ mark as read/unread _ action anchor on "view all notification page"
                    */
                    if($('#notifications-container').length == 1){
                        actionBtn = $('ul.notifications').find('li[notification-id="'+data.notificationID+'"] a.markReadStatus');
                    }

                    status.done(function(res){
                        if(res.updateStatus == 1 && NotificationStatus == 1){
                            actionBtn.attr('status',0).text('Mark as unread');
                            $('#notification_count').text( parseInt($('#notification_count').text())-1 );
                        }
                        else if(res.updateStatus == 1 && NotificationStatus == 0){
                            actionBtn.attr('status',1).text('Mark as Read');
                            $('#notification_count').text( parseInt($('#notification_count').text())+1 );
                        }
                    });

                    status.fail(function(res){
                        console.log(res);
                    });

                });
                
                $('#reportico_container').on('click','.swMenuItemLink',function(){
                    //let reporticoContainer = $(this);
                    let csvBtn = '<input type="submit" class="btn prepareAjaxExecute swCSVBox" title="Generate CSV Report" id="prepareAjaxExecute" name="submitPrepare" value="">';
                    let findToolbarTimeout = setInterval(function(){
                        let toolbarContainer = $('#reportico_container').find('.swPrpToolbarPane');
                        if(toolbarContainer.length == 1){
                            clearInterval(findToolbarTimeout);
                            toolbarContainer.prepend(csvBtn);
                        }
                    },1000);

                })
            });
        </script>
    </body>
</html>
