@extends('layouts.app')

@section('content')
<div class="container">

    <div class="justify-content-start mb-3">
        <a href="{{ route('contents.create') }}" class="btn btn-primary mr-2">Add</a>
        <a href="{{ route('contents.index') }}" class="btn btn-success">Edit</a>
    </div>
    
</div>
@endsection
