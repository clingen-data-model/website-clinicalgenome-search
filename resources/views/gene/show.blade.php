@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
		  <h1 class=" display-4 ">{{ $record->label }}
				@include("_partials.facts.gene-button")
		  </h1>
		</div>
		<div class="col-md-12">

			@include("_partials.facts.gene-panel")

			<h2 class="h3 mb-0">ClinGen's Curations Summary Report</h2>
			<ul class="nav nav-tabs">
          <li class="active">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class=" bg-primary text-white">
              ClinGen's Curation Summaries
            </a>
          </li>
          <li class="">
            <a href="{{ route('gene-external', $record->hgnc_id) }}">External Genomic Resources </a>
          </li>
          <li class="">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D" class="" target="clinvar">ClinVar Variants  <i class="glyphicon glyphicon-new-window text-xs" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>
		
		@forelse ($record->genetic_conditions as $disease)
			<div class="card">
				<div class="card-header text-white bg-primary">
					<h3 class="text-white h5 p-0 m-0">{{ $record->symbol }} - 
					<a href="/conditions/{{ $record->getMondoString($disease->disease->iri) }}" >{{ $disease->disease->label }} <small class="text-white">| {{ $record->getMondoString($disease->disease->iri, true) }}</small></a></h3>
				</div>
				<div class="card-body p-0 m-0">

				<table class="panel-body table table-hover">
					<thead class="thead-labels">
						<tr>
						<th class="col-sm-3 th-curation-group text-left">Curated by</th>
						<th class="col-sm-4 text-left"> Classification</th>
						<th class="col-sm-2 text-left"> </th>
						<th class="col-sm-2 text-center">Date</th>
						<th class="col-sm-1 text-center">Report</th>
						</tr>
					</thead>

					<tbody class="">

						<!-- Gene Disease Validity				-->
						@foreach($disease->gene_validity_assertions as $validity)
								<tr>
									<td class="col-sm-3">G - Gene-Disease Validity</td>
									
									<td class="col-sm-6">{{ \App\GeneLib::validityAssertionString($validity->classification) }}</td>
									
									<td class="col-sm-2"><span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $validity->mode_of_inheritance }}"><i class="fas fa-info-circle text-muted"></i></span>{{ \App\GeneLib::validityAssertionString($validity->mode_of_inheritance) }}</td>
									
									<td class="col-sm-2">{{ $record->displayDate($validity->report_date) }} </td>
									
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="/gene-validity/{{ \App\GeneLib::validityAssertionID($validity->curie) }}">View report</a></td>
								</tr>
						@endforeach

						<!-- Actionability					-->
						@foreach($disease->actionability_curations as $actionability)
								<tr>
									<td class="col-sm-3">A - Actionability</td>
									
									<td class="col-sm-6">View Report For Scoring Details</td>
									
									<td class="col-sm-2"></td>
									
									<td class="col-sm-2">{{ $record->displayDate($actionability->report_date) }}</td>
									
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $actionability->source }}">View report</a></td>
								</tr>
						@endforeach


						<!-- Gene Dosage						-->
						@foreach($disease->gene_dosage_assertions as $dosage)
								<tr>
									<td class="col-sm-3">D - Dosage</td>
									<td class="col-sm-6">{{ $dosage->score }}</td>
									<td class="col-sm-2"></td>
									<td class="col-sm-2">{{ $record->displayDate($dosage->report_date) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $validity->curie }}">View report</a></td>
								</tr>
						@endforeach

					</tbody>
        		</table>
			</div>
		</div>
		@empty
		@endforelse

		<!-- Gene Dosage Catchall -->
		@if(!empty($record->dosage_curation ))
		<div class="card">
			<div class="card-header text-white bg-primary">
				<h3 class="text-white h5 p-0 m-0">{{ $record->symbol ?? '' }}</h3>
			</div>
			<div class="card-body p-0 m-0">

				<table class="panel-body table table-hover">
					<thead class="thead-labels">
						<tr>
							<th class="col-sm-3 th-curation-group text-left">Curated by</th>
							<th class="col-sm-4 text-left"> Classification</th>
							<th class="col-sm-2 text-left"> </th>
							<th class="col-sm-2 text-center">Date</th>
							<th class="col-sm-1 text-center">Report</th>
						</tr>
					</thead>

					<tbody class="">

					@foreach($record->dosage_curation_map as $key => $value)
						<tr>
							<td class="col-sm-3">D - Dosage</td>
							<td class="col-sm-6">{{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->score ?? null) }}</td>
							<td class="col-sm-2"></td>
							<td class="col-sm-2">{{ $record->displayDate($record->dosage_curation->report_date) }}</td>
							<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ env('CG_URL_CURATIONS_DOSAGE', '#') }}{{ $record->symbol }}&subject=">View report</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>

			</div>
		</div>
		@endif

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
