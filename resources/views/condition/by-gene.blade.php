@extends('layouts.app')
@php ($currations_set = false) @endphp

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"><img src="/images/disease.png" width="40" height="40"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">{{ $record->label }}</h1>
						<a class="btn btn-facts btn-outline-primary " role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
							<i class="far fa-caret-square-down"></i> View Disease Facts
						</a>
          </td>
        </tr>
      </table>

			</h1>

</div>

	<div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">
		  <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px">{{ $record->nvalid ?? '0' }}</span><br />Gene-Disease Validity<br />Classifications</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px">{{ $record->ndosage ?? '0' }}</span><br />Dosage Sensitivity<br />Classifications</li>
			laravel-2021-01-22.log<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">{{ $record->naction ?? '0' }}</span><br /> Clinical Actionability<br />Assertions</li>
			</ul>

</div>
			@include("_partials.facts.condition-panel")

			</div>
			<ul class="nav nav-tabs mt-1" style="">
          {{-- <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="pt-2 pb-2 text-primary">
              Curations By Disease
            </a>
					</li> --}}
					<li class="active" style="">
            <a href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}" class="">
              <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
          </li>
          <li class="" style="">
            <a href="{{ route('condition-external', $record->getMondoString($record->iri, true)) }}" class=""><span class='hidden-sm hidden-xs'>External Genomic </span>Resources </a>
          </li>
          <li class="" style="">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}"  class="" target="clinvar">ClinVar <span class='hidden-sm hidden-xs'>Variants  </span><i class="glyphicon glyphicon-new-window small" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">

@if((!empty($record->dosage_curation ) && !empty($record->dosage_curation_map)) OR !empty($record->genetic_conditions))
<div class="btn-group  btn-group-xs float-right" role="group" aria-label="...">
  <a  href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}" class="btn btn-default ">Group By Activity</a>
  <a  href="{{ route('disease-by-gene', $record->getMondoString($record->iri, true)) }}" class="btn btn-primary active">Group By Gene-Disease Pair</a>
</div>

@endif

			@forelse ($record->genetic_conditions as $disease)



				<h3  id="link-gene-validity" style="" class="h3 mt-4 mb-0"><i><a class="text-dark" href="{{ route('gene-show', $disease->gene->hgnc_id) }}" >{{ $disease->gene->label }}</a></i> -
					{{ $record->label }}</h3>
					<div class="card mb-5">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-3 th-curation-group text-left">Activity</th>
								<th class="col-sm-2 text-left"></th>
								<th class="col-sm-2  ">Classification</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>
							<tbody class="">


						<!-- Gene-Disease Validity				-->
						@foreach($disease->gene_validity_assertions as $validity)
								@php ($first = true) @endphp
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?"> <img style="width:20px" src="/images/clinicalValidity-on.png" alt="Clinicalvalidity on"> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>




									<td class=" @if(!$loop->first) border-0 @endif ">{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }}
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
									</td>

									<td class=" @if(!$loop->first) border-0 @endif ">
										<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="/kb/gene-validity/{{ $validity->curie }}">{{ \App\GeneLib::validityClassificationString($validity->classification->label) }}</a>
									</td>


									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->report_date) }}</a></td>
								</tr>
								@php ($first = false) @endphp
						@endforeach

						<!-- Actionability					-->
						@foreach($disease->actionability_curations as $key => $actionability)
								@php ($first = true) @endphp
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/clinical-actionability/" data-content="How does this genetic diagnosis impact medical management?"> <img style="width:20px" src="/images/clinicalActionability-on.png" alt="Clinicalactionability on"> Clinical Actionability <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>


									<td class=" @if(!$loop->first) border-0 @endif "></td>

									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->source }}">{{ $record->displayActionType($actionability->source) }} View Report </a></td>


									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="{{ $actionability->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($actionability->report_date) }}</a></td>
								</tr>
								@php ($first = false) @endphp
						@endforeach


						<!-- Gene Dosage						-->
						@foreach($disease->gene_dosage_assertions as $key => $dosage)
								@php ($first = true) @endphp
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif "><a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?"> <img style="width:20px" src="/images/dosageSensitivity-on.png" alt="Dosagesensitivity on"> Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a></td>
									<td class=" @if(!$loop->first) border-0 @endif "></td>
									<td class=" @if(!$loop->first) border-0 @endif ">
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content="Dosage Sensitivity rating system">
											@if ($key == "haploinsufficiency_assertion")
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">
												{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
											</a>
											@else
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">
												{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
											</a>
											@endif
										</a>
									</td>
									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($dosage->report_date) }}</a></td>
								</tr>
								@php ($first = false) @endphp
						@endforeach

					</tbody>
        		</table>
			</div>
		</div>
		@empty
		@endforelse

		<!-- Gene Dosage Catchall -->
		@if(!empty($record->dosage_curation ) && !empty($record->dosage_curation_map))
		<h3  id="link-gene-validity" style="" class="h3 mt-3 mb-0"><i>{{ $record->symbol }}</i></h3>
					<div class="card mb-6 ">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-3 th-curation-group text-left">Activity</th>
								<th class="col-sm-2 text-left"></th>
								<th class="col-sm-2  ">Classification</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>
							<tbody class="">

					@foreach($record->dosage_curation_map as $key => $value)
								@php ($first = true) @endphp
						<tr>
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?"> <img style="width:20px" src="/images/dosageSensitivity-on.png" alt="Dosagesensitivity on"> Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>
									<td class=" @if(!$loop->first) border-0 @endif "></td>
									<td class=" @if(!$loop->first) border-0 @endif ">
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content="Dosage Sensitivity rating system">
											@if ($key == "haploinsufficiency_assertion")
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">
												{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
											</a>
											@else
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">
												{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
											</a>
											@endif


									</td>
									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
								</tr>
								@php ($first = false) @endphp
					@endforeach
					</tbody>
				</table>

			</div>
		</div>

		@endif

				@if(empty($record->dosage_curation ) && empty($record->genetic_conditions ))
				<br clear="both" />
			<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet curated {{ $record->hgnc_id }}.</strong> <br />View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.</div>

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
