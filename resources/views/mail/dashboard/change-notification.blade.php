@extends('mail.layout.default')

@section('headling')

	<h4>Changes have occured on the following genes:</h4>
	@foreach ($genes as $gene)
	<p>{{ $gene }}</p>
	@endforeach

@endsection

@section('content')
	<h4>To view the entire report, click on the link below.</h4>
	<a href="https://search.clingen.info/reports/view/{{ $report }}">View Report</a>
@endsection

@section('boilerplate')
	content {{$content ?? "it's me!"}}
@endsection
