@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{asset('css/jquery-ui.min.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 panel ">
            <div class="pannel-header">
                <h3>Add Work Detail</h3>
            </div>

            <form action="{{route('WorkDetails.create')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="project_id" value="{{ $project_id }}">
                <input type="hidden" name="subcategory_id" value="{{ $subcategory_id }}">

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name"> Work Detail :</label>
                    <input  type="text" class="form-control" name="name" value="{{ old('name')}}" id="name" required="required">
                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description"> Description :</label>
                    <textarea class="form-control" id="description" name="description" required="required">{{ old('name')}}</textarea>
                    @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="isUserAccessable"> User Accessable :</label>
                    <input style="width:26px;" type="checkbox" checked="checked" value="1" name="isUserAccessable" id="isUserAccessable" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-success pull-right">Submit</button>
                </div>

            </form>
        </div>  
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/date.js') }}"></script>
@endsection