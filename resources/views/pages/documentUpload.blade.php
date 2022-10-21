@extends('layouts.app')

@section('head')
<link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet" type="text/css">
@stop

@section('footer')
<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form action="{{route('document.store')}}" method="POST" class="dropzone" enctype="multipart/form-data" id="addImages">
                {{ csrf_field() }}
                <div class="document-upload">

                    <div class="dz-message">

                    </div>

                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>

                    <div class="dropzone-previews" id="dropzonePreview"></div>

                    <input type="hidden" name="project_id" value="{{$findproduct->id}}">
                    <h4 style="text-align: center;color:#428bca;">Drop or Select File/Image <br> <span class="glyphicon glyphicon-hand-up"></span></h4>
                </div>

                <div class="col-md-12 form-inline">
                    <label for="file_title">Report Type</label>
                    <select class="form-control" name="file_title" id="file_title">
                        <option value="drp">DRP</option>
                        <option value="others">Others</option>
                    </select>
                </div>
            </form>

        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-8">
            <div class="go-next">
                <a href="{{route('project.index')}}" class="btn btn-default btn-block"> Go Next</a>
            </div>
        </div>
    </div>
</div>

@endsection