@extends('layouts.app')

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<h1 class="h2 mb-0">{{ $record->label }}

			</h1>
			<strong>GENE ID: {{ $record->hgnc_id }}</strong>
			<a class="btn btn-outline-primary bg-white btn-xs text-dark" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				<i class="far fa-caret-square-down"></i> More gene facts
			</a>
</div>

	<div class="col-md-7 text-right">
			<ul class="list-inline line-tight float-right mt-4 stat-wrapper p-1" style="">
				@isset($record->curations_by_activity->gene_validity)
				<li class="mr-0 pr-0">
					<a href="#link-gene-validity" class="text-dark"><span class="h2 font-weight-light text-dark">{{ count((array)$record->curations_by_activity->gene_validity) }}</span></a>
				</li>
				<li class="mr-4 text-left pl-0">
					<a href="#link-gene-validity" class="text-dark">
					<div class="text-xs small text-dark">Gene-Disease Validity<br />Classifications <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->dosage_curation)
				<li class="mr-0 pr-0">
					<a href="#link-dosage-curation" class="text-dark"><span class="h2 font-weight-light text-dark">{{ count((array)$record->curations_by_activity->dosage_curation) }}</span></a>
				</li>
				<li class="mr-4 text-left pl-0">
					<a href="#link-dosage-curation" class="text-dark">
					<div class="text-xs small text-dark">Dosage Sensitivity<br />Classifications <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->actionability)
				<li class="mr-0 pr-0">
					<a href="#link-actionability" class="text-dark"><span class="h2 font-weight-light text-dark">{{ count((array)$record->curations_by_activity->actionability) }}</span></a>
				</li>
				<li class="mr-4 text-left pl-0">
					<a href="#link-actionability" class="text-dark">
					<div class="text-xs small text-dark">Clinical Actionability<br />Assertions <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				</ul>
</div>
			@include("_partials.facts.gene-panel")



			{{-- <ul class="list-inline mt-3 mb-4" style="">
				@isset($record->curations_by_activity->gene_validity)
				<li class="mr-5"><a href="#link-gene-validity" class="text-light"><span class="h3 font-weight-light text-light">{{ count((array)$record->curations_by_activity->gene_validity) }}<img src="/images/clinicalValidity-dark.png" style="margin-top:-4px" width="30" height="30"></span>
					<div class="text-xs small text-light">Gene-Disease Validity<br />Classifications</div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->dosage_curation)
				<li class="mr-5"><a href="#link-dosage-curation" class="text-light"><span class="h3 font-weight-light text-light">{{ count((array)$record->curations_by_activity->dosage_curation) }}<img src="/images/dosageSensitivity-dark.png" style="margin-top:-4px" width="30" height="30"></span>
					<div class="text-xs small text-light">Dosage Sensitivity<br />Classifications</div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->actionability)
				<li class="mr-5"><a href="#link-actionability" class="text-light"><span class="h3 font-weight-light text-light">{{ count((array)$record->curations_by_activity->actionability) }}<img src="/images/clinicalActionability-dark.png" style="margin-top:-4px" width="30" height="30"></span>
					<div class="text-xs small text-light">Clinical Actionability<br />Assertions</div></a>
				</li>
				@endif
				</ul> --}}
			</div>
			<ul class="nav nav-tabs mt-1" style="border-bottom: none;">
          {{-- <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="pt-2 pb-2 text-primary">
              Curations By Disease
            </a>
					</li> --}}
					<li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-by-activity', $record->hgnc_id) }}" class="pt-2 pb-2  bg-white text-primary">
              ClinGen's Curations
            </a>
          </li>
          <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-external', $record->hgnc_id) }}" class="pt-2 pb-2 text-primary">External Genomic Resources </a>
          </li>
          <li class="" style="margin-bottom: 0px;">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D"  class="pt-2 pb-2 text-primary" target="clinvar">ClinVar Variants  <i class="glyphicon glyphicon-new-window small" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">


			@php ($header_val = true) @endphp
			@forelse ($record->genetic_conditions as $key => $disease)
				@if(count($disease->gene_validity_assertions))
				@if($header_val == true)
					<h3  id="link-gene-validity" style="margin-left: -45px " class=" mt-3 mb-0"><img src="/images/clinicalValidity-on.png" width="40" height="40" style="margin-top:-4px"> Gene-Disease Validity</h3>
					<div class="card mb-4">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> Disease</th>
								<th class="col-sm-2 text-left">MOI</th>
								<th class="col-sm-3 text-center">Classification</th>
								<th class="col-sm-1 text-center">Date</th>
								<th class="col-sm-1 text-center">Report</th>
								</tr>
							</thead>
							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@foreach($disease->gene_validity_assertions as $i => $validity)
										<tr>
											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $record->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $disease->disease->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												<div class="text-overflow">
												{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->label) }}
												<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
												</div>
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
												<div class="mx-4">
												<a class="btn btn-xs btn-outline-primary mb-1 btn-block text-capitalize" href="/gene-validity/{{ $validity->curie }}">
												{{ \App\GeneLib::validityClassificationString($validity->classification->label) }}
												</a>
												</div>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">{{ $record->displayDate($validity->report_date) }}</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block" href="/gene-validity/{{ $validity->curie }}">View</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@php ($header_val = false) @endphp
				@endisset
				@empty
				@endforelse
				@if($header_val == false)
							</tbody>
						</table>
					</div>
				</div>
				@endisset


				@php ($header_dos = true) @endphp
				@forelse ($record->genetic_conditions as $disease)
				@if(count($disease->gene_dosage_assertions))
				@if($header_dos == true)
					<h3 id="link-dosage-curation" style="margin-left: -45px" class="mb-0"><img style="margin-top:-4px" src="/images/dosageSensitivity-on.png" width="40" height="40"> Dosage Sensitivity</h3>
					<div class="card mb-4">
						<div class="card-body p-0 m-0">

						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> Disease</th>
								<th class="col-sm-2 text-center"></th>
								<th class="col-sm-3 text-center">Haploinsufficiency &amp; Triplosensitivity</th>
								<th class="col-sm-1 text-center">Date</th>
								<th class="col-sm-1 text-center">Report</th>
								</tr>
							</thead>

							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@foreach($disease->gene_dosage_assertions as $i => $dosage)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $record->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $disease->disease->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
												<div class="mx-4">
													@if($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-xs btn-outline-primary mb-1 btn-block " href="{{ env('CG_URL_CURATIONS_DOSAGE', '#') }}{{ $record->symbol }}&subject=">
													{{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }}
													</a>
													@endif
													@if($dosage->assertion_type != "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-xs btn-outline-primary mb-1 btn-block " href="{{ env('CG_URL_CURATIONS_DOSAGE', '#') }}{{ $record->symbol }}&subject=">
													{{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }}
													</a>
													@endif
												</div>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">{{ $record->displayDate($dosage->report_date) }}</td>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block" href="{{ env('CG_URL_CURATIONS_DOSAGE', '#') }}{{ $record->symbol }}&subject=">View</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@php ($header_dos = false) @endphp
				@endisset
				@empty
				@endforelse
				@if($header_dos == false)
							</tbody>
						</table>
					</div>
				</div>
				@endisset

				@php ($header_aci = true) @endphp
				@forelse ($record->genetic_conditions as $key => $disease)
					@if(count($disease->actionability_curations))
				  @if($header_aci == true)
					<h3 id="link-actionability" style="margin-left: -45px" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40"> Actionability</h3>
					<div class="card mb-4">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> Disease</th>
								<th class="col-sm-2 text-center"></th>
								<th class="col-sm-3 text-center">Report</th>
								<th class="col-sm-1 text-center">Date</th>
								<th class="col-sm-1 text-center">Report</th>
								</tr>
							</thead>

							<tbody class="">
					@endif
								@php ($first = true) @endphp
								@foreach($disease->actionability_curations as $i => $actionability)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($first == true) {{ $record->label }} @endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($first == true) {{ $disease->disease->label }} @endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0  pt-0 @else pb-0 @endif text-center">
												<div class="mx-4">
													<a class="btn btn-xs btn-outline-primary mb-1 btn-block " href="{{ $actionability->source }}">
													{{ $record->displayActionType($actionability->source) }}View Details
													</a>
												</div>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif  text-center">{{ $record->displayDate($actionability->report_date) }}</td>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif  text-center"><a class="btn btn-xs btn-success btn-block" href="{{ $actionability->source }}">View</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@php ($header_aci = false) @endphp
				@endisset
				@empty
				@endforelse
				@if($header_aci == false)
							</tbody>
						</table>
					</div>
				</div>
				@endisset


@endsection

@section('heading')
<div class="content ">
	<div class="section-heading-content">
	</div>
</div>
@endsection

@section('script_js')

@endsection
