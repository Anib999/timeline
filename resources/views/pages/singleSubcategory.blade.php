@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2> Sub Category  Information </h2>
                </div>
                <div class="col-md-4">
                    <h3>Sub Category Name :</h3>
                    <p>{{ $subcategory->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Project  Name :</h3>
                    <p>{{$subcategory->project->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Sub Category  Incharge :</h3>
                    <p>{{$subcategory->user->name}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Sub Category  Status:</h3>
                    <p>{{$subcategory->subCategory_status}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Sub Category Description :</h3>
                    <p>{{ $subcategory->description}}</p>
                </div>
                <div class="col-md-4">
                    <h3> Starting from :</h3>
                    <p>{{$subcategory->fromDate}}</p>
                </div>
                <div class="col-md-4">
                    <h3>Ending To:</h3>
                    <p>{{$subcategory->toDate}}</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection