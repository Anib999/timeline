<ul class="nav navbar-nav navbar-left">

    @if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('HRAdmin'))
    <!-- Leave -->
    <li>
        <a href="{{route('leave.index')}}">Leave</a>
        <div class="bar"></div>
    </li>
    <!-- Leave -->

    @if(!Auth::user()->hasRole('HRAdmin'))
    <!-- Holiday -->
    <li>
        <a href="{{route('holiday.index')}}">Holiday</a>
        <div class="bar"></div>
    </li>
    <!-- Holiday -->
    @endif

    <!-- Position -->
    <li>
        <a href="{{route('position.create')}}">Position</a>
        <div class="bar"></div>
    </li>
    <!-- Position -->

    <!-- User -->
    <li>
        <a href="{{route('user.index')}}">User</a>
        <div class="bar"></div>
    </li>
    <!-- User -->
    @endif

    <!-- Project -->
    <li>
        <a href="{{route('project.index')}}">Project</a>
        <div class="bar"></div>
    </li>
    <!-- Project -->

    @if(!Auth::user()->hasRole('SuperAdmin') && !Auth::user()->hasRole('CEOAdmin'))
    <!-- Own Leave -->
    <li>
        <a href="{{route('userLeave.index')}}">Leave Req</a>
        <div class="bar"></div>
    </li>
    <!-- Own Leave -->

    <!-- Own Attendance -->
    <li>
        <a href="{{route('attendence.index')}}">Attendance</a>
        <div class="bar"></div>
    </li>
    <!-- Own Attendance -->
    @endif

    <!-- Own Day Work -->
    <li>
        <a href="{{route('dayWorkHour.index')}}">Day Work</a>
        <div class="bar"></div>
    </li>
    <!-- Own Day Work -->

    <!-- Own Field Visit -->
    <li>
        <a href="{{route('addFieldVisit')}}">Field Visit</a>
        <div class="bar"></div>
    </li>
    <!-- Own Field Visit -->

    @if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('ViewEmployee'))
    <!-- All Employee -->
    <li>
        <a href="{{route('AdminUserView.index')}}">All Employee</a>
        <div class="bar"></div>
    </li>
    <!-- All Employee -->
    @endif

    @if(Auth::user()->hasRole('Supervisor') || Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('CEOAdmin') || Auth::user()->hasRole('ViewReport'))
    <!-- View Report -->
    <li>
        <a href="{{route('viewReporting')}}">All Report</a>
        <div class="bar"></div>
    </li>
    <!-- View Report -->
    @endif

    @if(!Auth::user()->hasRole('SuperAdmin') && !Auth::user()->hasRole('CEOAdmin'))
    <!-- Own Reporting -->
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Own Reporting <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
        <ul class="dropdown-menu">
            <li> <a href="{{route('employeeAttendance')}}">Attendance Report </a> </li>
            <li> <a href="{{route('employeeLeave')}}">Leave Report </a> </li>
            <li> <a href="{{route('employeeDayWorkReport')}}">Day Work Report </a> </li>
        </ul>
    </li>
    <!-- Own Reporting -->
    @endif

</ul>