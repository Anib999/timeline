@extends('pdf.layout.template')
@section ('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-defaut">
               <div class="panel panel-defaut">
                <div class="panel-body">
                    <h3 class="text-center"> {{Auth::user()->name}}'s leave Report</h3>
                </div>
            </div>
            </div>
            <table class="table table-responsive table-condensed table-hover" >
                <thead>
                    <tr>
                        <th>Request Day</th>
                        <th> No of Day</th>
                        <th> Start Date</th>
                        <th> End Date</th>
                        <th> Approve By</th>
                        <th> Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user_leaves as $user_leave)
                    <tr>
                        <td>{{$user_leave->request_date}}</td>
                        <td>{{$user_leave->no_of_days}}</td>
                        <td>{{$user_leave->from_date}}</td>
                        <td>{{$user_leave->to_date}}</td>
                        <td>{{$user_leave->aprove_by}}</td>
                        <td>{{$user_leave->ap_remarks}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>  
        </div>
    </div>
</div>
@endsection