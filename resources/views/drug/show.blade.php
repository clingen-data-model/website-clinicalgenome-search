@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
		  <h1 class=" display-4 ">{{ $record->label }} </h1>
		</div>
		<div class="col-md-12">

			<h3 class="h3 mb-0">Name:  {{ $record->label }}</h2>
			<h3 class="h3 mb-0">Ontological Ref. :  RxCUI:{{ $record->curie }}</h2>
				
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
