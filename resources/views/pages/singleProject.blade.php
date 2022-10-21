@extends('layouts.app')

@section('content')
<style>
    #list {
        width: 100%;
        overflow: hidden;
        -webkit-column-count: 3;
        -webkit-column-gap: 1.875em;
        -webkit-column-fill: auto;
        -moz-column-count: 3;
        -moz-column-gap: 1.875em;
        -moz-column-fill: auto;
        column-count: 3;
        column-gap: 1.875em;
        column-fill: auto;
    }

    #list img {
        width: 100%;
    }

    .item {
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        -webkit-column-break-inside: avoid;
        -moz-column-break-inside: avoid;
        /* column-break-inside: avoid; */
        break-inside: avoid;
        text-align: center;
    }

    .item span {
        display: block;
    }
</style>
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2> Project Information </h2>
                </div>
                <div class="col-md-4">
                    <h3>Project Name :</h3>
                    <p>{{$project->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Project Supervisor :</h3>
                    <p>{{$project->user->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Project Status:</h3>
                    <p>{{$project->project_status}}</p>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-defaut">
                        <h2 class="text-center">Project Documents</h2>
                    </div>
                    <div class="row">
                        <div class="document-wrapper">
                            <div id="list" class="masonry-container">
                                @foreach($project->documents as $document)
                                <div class="item">
                                    <img src="{{asset('/').$document->file_path}}" alt="{{ $project->name}} {{$document->file_title}} document" class="img-responsive">
                                    <span>{{$document->file_title}}</span>
                                    <a class="btn btn-success btn-xs" href="{{asset('/').$document->file_path}}" download="{{$document->file_name}}"> download</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection