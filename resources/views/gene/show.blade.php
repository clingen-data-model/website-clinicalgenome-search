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

			{{-- <h2 class="h3 mb-0">ClinGen's Curations Summary Report</h2> --}}
			<ul class="nav nav-tabs">
          <li class="active">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class=" bg-primary text-white">
              Curations By Disease
            </a>
					</li>
					<li class="">
            <a href="{{ route('gene-by-activity', $record->hgnc_id) }}" class=" ">
              Curations By Activity
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
				<div class="card-header panel-title text-white bg-primary">
					<h3 class="text-white h5 p-0 m-0"><i>{{ $record->symbol }}</i> -
					<a class="text-white" href="/conditions/{{ $record->getMondoString($disease->disease->iri) }}" >{{ $disease->disease->label }} <small class="text-white">| {{ $record->getMondoString($disease->disease->iri, true) }}</small></a></h3>
				</div>
				<div class="card-body p-0 m-0">

				<table class="panel-body table">
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
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?"> <img style="width:20px" src="/images/clinicalValidity-on.png" alt="Clinicalvalidity on"> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>

									<td class=" @if(!$loop->first) border-0 @endif ">
										<a class="text-dark" href="/gene-validity/{{ $validity->curie }}"><strong>{{ \App\GeneLib::validityClassificationString($validity->classification->label) }}</strong></a>
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://www.clinicalgenome.org/site/assets/files/5967/gene-validity_classification.pdf" data-content="Gene-Disease Validity classification and scoring information"><i class="glyphicon glyphicon-question-sign text-muted"></i></a>
									</td>



									<td class=" @if(!$loop->first) border-0 @endif ">{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->label) }}
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
									</td>

									<td class="  @if(!$loop->first) border-0 @endif text-center">{{ $record->displayDate($validity->report_date) }} </td>

									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success" href="/gene-validity/{{ $validity->curie }}">View report</a></td>
								</tr>
						@endforeach

						<!-- Actionability					-->
						@foreach($disease->actionability_curations as $key => $actionability)
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/clinical-actionability/" data-content="How does this genetic diagnosis impact medical management?"> <img style="width:20px" src="/images/clinicalActionability-on.png" alt="Clinicalactionability on"> Clinical Actionability <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>

									<td class=" @if(!$loop->first) border-0 @endif "><strong>{{ $record->displayActionType($actionability->source) }}View Report For Scoring Details </strong></td>

									<td class=" @if(!$loop->first) border-0 @endif "></td>

									<td class=" @if(!$loop->first) border-0 @endif text-center">{{ $record->displayDate($actionability->report_date) }}</td>

									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success" href="{{ $actionability->source }}">View report</a></td>
								</tr>
						@endforeach


						<!-- Gene Dosage						-->
						@foreach($disease->gene_dosage_assertions as $key => $dosage)
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif "><a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?"> <img style="width:20px" src="/images/dosageSensitivity-on.png" alt="Dosagesensitivity on"> Gene Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a></td>
									<td class=" @if(!$loop->first) border-0 @endif ">
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content="Gene Dosage Sensitivity rating system"><strong>
											@if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
											{{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }}
											@else
											{{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }}
											@endif
										</strong>  <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
									</td>
									<td class=" @if(!$loop->first) border-0 @endif "></td>
									<td class=" @if(!$loop->first) border-0 @endif text-center">{{ $record->displayDate($dosage->report_date) }}</td>
									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success" href="{{ $dosage->curie ?? '#' }}">View report</a></td>
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
				<h3 class="text-white h4 panel-title p-0 m-0"><i> {{ $record->symbol ?? '' }} </i></h3>
			</div>
			<div class="card-body p-0 m-0">

				<table class="panel-body table">
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
							<td class=" @if(!$loop->first) border-0 @endif ">
								@if($loop->first)
								<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?"> <img style="width:20px" src="/images/dosageSensitivity-on.png" alt="Dosagesensitivity on"> Gene Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
							@endif
						</td>
							<td class=" @if(!$loop->first) border-0 @endif ">
								<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content="Gene Dosage Sensitivity rating system"><strong>
									@if ($key == "haploinsufficiency_assertion")
									{{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }}
									@else
									{{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }}
									@endif
								</strong>  <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
							</td>
							<td class=" @if(!$loop->first) border-0 @endif "></td>
							<td class=" @if(!$loop->first) border-0 @endif text-center">{{ $record->displayDate($record->dosage_curation->report_date) }}</td>
							<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success" href="{{ env('CG_URL_CURATIONS_DOSAGE', '#') }}{{ $record->symbol }}&subject=">View report</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>

			</div>
		</div>
		@endif
        
        @if(empty($record->dosage_curation ) && empty($record->genetic_conditions ))
			<div class="alert alert-info text-center" role="alert"><strong>ClinGen has not yet curated {{ $record->hgnc_id }}.</strong> <br />View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.</div>

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
