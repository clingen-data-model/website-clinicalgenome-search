@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">

    <div class="col-md-12 curated-genes-table">
      <h1>{{ $title ?? '' }}</h1>

    </div>

		<div class="col-md-12">


      <div class="alert alert-warning" role="alert">
        <p>{{ $message ?? '' }}</p>
      </div>
    </div>
    
    <div class="col-md-12">
      <a href="{{ $back ?? '/' }}">Return to previous page</a>
    </div>
	</div>
</div>

@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('script_js')

@endsection
