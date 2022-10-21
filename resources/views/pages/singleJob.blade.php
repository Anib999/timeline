@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2> Job  Information </h2>
                </div>
                <div class="col-md-4">
                    <h3>Job Name :</h3>
                    <p>{{$job->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Project name :</h3>
                    <p>{{$job->project->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>SubCategory Name:</h3>
                    <p>{{$job->subcategory->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Job Incharge :</h3>
                    <p>{{$job->user_incharge->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Job Assign To :</h3>
                    <p>{{$job->user_assignedto->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Job From:</h3>
                    <p>{{$job->fromDate}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Job To :</h3>
                    <p>{{$job->toDate}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Job (in Hour) :</h3>
                    <p>{{$job->hourTime}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Job Status:</h3>
                    <p>{{$job->job_status}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Job Created :</h3>
                    <p>{{$job->created_at}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Job Updated :</h3>
                    <p>{{$job->updated_at}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


