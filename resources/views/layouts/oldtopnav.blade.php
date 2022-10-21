<ul class="nav navbar-nav navbar-left">
    <li><a href="{{route('project.index')}}"> Project </a>
        <div class="bar"></div>
    </li>
    @if(Auth::user()->isAdmin == true)
    @if(Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('AddUpdateUser'))
    <li><a href="{{route('user.index')}}"> User</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('position.create')}}">Position</a>
        <div class="bar"></div>
    </li>
    @endif


    @if(Auth::user()->hasRole('SuperAdmin'))

    <li><a href="{{route('leave.index')}}"> Leave</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('holiday.index')}}"> Holiday</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('AdminUserView.index')}}">All Employee</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('viewReporting')}}"> All Report </a>
        <div class="bar"></div>
    </li>
    @endif
    @if(Auth::user()->hasRole('ViewEmployee'))
    <li><a href="{{route('AdminUserView.index')}}"> All Employee</a>
        <div class="bar"></div>
    </li>
    @endif
    @if(Auth::user()->hasRole('ViewReport'))
    <li><a href="{{route('viewReporting')}}"> All Report </a>
        <div class="bar"></div>
    </li>
    @endif
    @if(Auth::user()->hasRole('AddFieldVisit'))
    <!-- <li><a href="{{route('dayWorkHour.index')}}">Add Field Visit</a><div class="bar"></div></li> -->
    <li><a href="{{route('dayWorkHour.index')}}">Add Day Work</a>
        <div class="bar"></div>
    </li>
    @endif
    @if(Auth::user()->isSuperAdmin == false)
    <li><a href="{{route('attendence.index')}}"> Attendance</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('dayWorkHour.index')}}">Day Work </a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('userLeave.index')}}">Leave</a>
        <div class="bar"></div>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Own Reporting <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
        <ul class="dropdown-menu">
            <li> <a href="{{route('employeeAttendance')}}">Attendance Report </a> </li>
            <li> <a href="{{route('employeeLeave')}}">Leave Report </a> </li>
            <li> <a href="{{route('employeeDayWorkReport')}}">Day Work Report </a> </li>
        </ul>
    </li>
    @endif
    @else
    <li><a href="{{route('attendence.index')}}"> Attendance</a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('dayWorkHour.index')}}">Day Work </a>
        <div class="bar"></div>
    </li>
    <li><a href="{{route('userLeave.index')}}">Leave</a>
        <div class="bar"></div>
    </li>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Own Reporting <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
        <ul class="dropdown-menu">
            <li> <a href="{{route('employeeAttendance')}}">Attendance Report </a> </li>
            <li> <a href="{{route('employeeLeave')}}">Leave Report </a> </li>
            <li> <a href="{{route('employeeDayWorkReport')}}">Day Work Report </a> </li>
        </ul>
    </li>
    @endif
    <li><a href="{{route('addFieldVisit')}}">Field Visit </a>
        <div class="bar"></div>
    </li>
</ul>