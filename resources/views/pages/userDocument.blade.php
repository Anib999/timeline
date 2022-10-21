@extends('layouts.app')

@section('head')
<link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet" type="text/css" >
@stop

@section('footer')
<script type="text/javascript" src="{{ asset('js/dropzone.min.js')}}"></script>

@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="document-upload">
                <form action="{{route('userDocUpload.upload')}}" method="POST" class="dropzone" enctype="multipart/form-data" id="addImages" >
                    {{ csrf_field() }}
                    <div class="dz-message">

                    </div>

                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>

                    <div class="dropzone-previews" id="dropzonePreview"></div>

                    <input type="hidden" name="user_id" value="{{$user->id }}">
                    <h4 style="text-align: center;color:#428bca;">Drop or Select File/Image <br> <span class="glyphicon glyphicon-hand-up"></span></h4>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
