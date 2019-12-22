WIP

@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
		  <h1 class=" display-4 ">{{-- $record->symbol --}}Disease name
				@include("_partials.facts.gene-button")
		  </h1>
		</div>
		<div class="col-md-12">

			@include("_partials.facts.gene-panel")

			<h2 class="h3 mb-0">ClinGen's Curations Summary Report</h2>

			@forelse ($record->diseases as $disease)
			<div class="card">
				<div class="card-header text-white bg-info">
					{{ $record->symbol }} - {{ $disease['label'] }} | {{ $disease['id'] }}
				</div>
				<div class="card-body">

					<ul class="list-group list-group-flush">

						<!-- Gene Disease Validity				-->
						@foreach($record->findValidity($disease['id']) as $validity)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">G - Gene-Disease Validity</td>
									<td class="col-sm-6">{{ $validity['classification'] }}</td>
									<td class="col-sm-2">{{ $record->displayDate($validity['date']) }} </td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $validity['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach

						<!-- Gene Dosage						-->
						@foreach($record->findDosage($disease['id']) as $dosage)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">D - Dosage</td>
									<td class="col-sm-6">{{ $dosage['classification'] }}</td>
									<td class="col-sm-2">{{ $record->displayDate($dosage['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $dosage['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach

						<!-- Actionability					-->
						@foreach($record->findActionability($disease['id']) as $actionability)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">A - Actionability</td>
									<td class="col-sm-6">{{ $actionability['type'] }} - View Report For Scoring Details</td>
									<td class="col-sm-2">{{ $record->displayDate($actionability['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $actionability['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach
					</ul>

				</div>
			</div>
			@empty
			THIS GENE HAS NOT BEEN CURATED
			@endforelse
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
