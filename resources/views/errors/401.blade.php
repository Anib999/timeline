
@extends('layouts.app')
@section('content')
<div id="app" style="margin-top: 18%;">
    <h2 class="text-center">{{ $exception->getMessage() }}</h2>
    <h1 class="text-center"> <a href="{{ URL::previous() }}"> Go Back</a> or <a href="{{ url('/home') }}">Home Page</a> </h1>
</div>
@endsection