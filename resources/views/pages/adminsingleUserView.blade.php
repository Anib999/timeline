@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="document-wrapper">
                <h2> Employee Documents</h2>
                @foreach($user->documents as $document)
                <div class="col-md-3">
                    <img src="{{asset('/').$document->file_path}}" alt="{{ $user->name}} document" class="img-responsive">
                    <a href="{{asset('/').$document->file_path}}" download="{{$user->file_name}}"> download</a>
                </div>
                @endforeach
            </div>
        </div>
        @if($projects->count() > 0)
        <div class="col-md-3">
            <div class="panel panel-info bg-danger">
                <div class="panel-heading">
                    <h3 class="text-center" > Assigned Project</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr> 
                                <th> Project name</th>
                                <th  > Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->name }}</td>
                                @if($project->supervisor == $user->id)
                                <td>supervisor</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($subCategories->count() > 0)
        <div class="col-md-4">
            <div class="panel panel-info bg-danger">
                <div class="panel-heading">
                    <h3 class="text-center" > Assigned SubCategory</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr> 
                                <th> Sub Category name</th>
                                <th > Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subCategories as $subCategory)
                            <tr>
                                <td>{{ $subCategory->name }}</td>
                                @if($subCategory->incharge == $user->id)
                                <td> Incharge</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
    </div>
</div>
@endsection
