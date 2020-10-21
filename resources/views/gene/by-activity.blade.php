@extends('layouts.app')

@section('content-heading')
<h1 class="h2 mb-0">{{ $record->label }}

			</h1>
			<strong>GENE ID: {{ $record->hgnc_id }}</strong>
			<a class="pl-2 pr-2 pt-1 pb-1" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
				<span class="text-sm text-light"><i class="far fa-caret-square-down"></i> More gene facts</span>
			</a>

			@include("_partials.facts.gene-panel")

			<ul class="list-inline mt-3 mb-4" style="">
				@isset($record->curations_by_activity->gene_validity)
				<li class="mr-0">
					<a href="#link-gene-validity" class="text-light"><span class="h2 font-weight-light text-light">{{ count((array)$record->curations_by_activity->gene_validity) }}</span></a>
				</li>
				<li class="mr-5">
					<a href="#link-gene-validity" class="text-light">
					<div class="text-xs small text-light">Gene-Disease Validity<br />Classifications <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->dosage_curation)
				<li class="mr-0">
					<a href="#link-dosage-curation" class="text-light"><span class="h2 font-weight-light text-light">{{ count((array)$record->curations_by_activity->dosage_curation) }}</span></a>
				</li>
				<li class="mr-5">
					<a href="#link-dosage-curation" class="text-light">
					<div class="text-xs small text-light">Dosage Sensitivity<br />Classifications <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				@isset($record->curations_by_activity->actionability)
				<li class="mr-0">
					<a href="#link-actionability" class="text-light"><span class="h2 font-weight-light text-light">{{ count((array)$record->curations_by_activity->actionability) }}</span></a>
				</li>
				<li class="mr-5">
					<a href="#link-actionability" class="text-light">
					<div class="text-xs small text-light">Clinical Actionability<br />Assertions <i class="fas fa-arrow-down"></i></div></a>
				</li>
				@endif
				</ul>

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

			<ul class="nav nav-tabs mt-3" style="border-bottom: none;">
          <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="pt-2 pb-2 bg-primary text-white mr-1">
              Curations By Disease
            </a>
					</li>
					<li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-by-activity', $record->hgnc_id) }}" class="pt-2 pb-2  bg-white text-primary mr-1">
              Curations By Activity
            </a>
          </li>
          <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-external', $record->hgnc_id) }}" class="pt-2 pb-2  bg-primary text-white mr-1">External Genomic Resources </a>
          </li>
          <li class="" style="margin-bottom: 0px;">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D"  class="pt-2 pb-2  bg-primary text-white mr-1" target="clinvar">ClinVar Variants  <i class="glyphicon glyphicon-new-window text-xs" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">

		@isset($record->curations_by_activity->gene_validity)
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
		@forelse ($record->curations_by_activity->gene_validity as $items)
						<!-- Gene-Disease Validity				-->
						@foreach($items as $item)
								<tr>
									<td class="">
										{{ $record->label }}
									</td>

									<td class="">
										{{ $item->disease->label }}
									</td>



									<td class="">
										{{ \App\GeneLib::validityMoiString($item->curation->mode_of_inheritance->label) }}
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($item->curation->mode_of_inheritance->label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
									</td>

									<td class="text-center">
										<div class="mx-4">
										<a class="btn btn-xs btn-outline-primary mb-1 btn-block text-capitalize" href="/gene-validity/{{ $item->curation->curie }}">
										{{ $item->curation->classification->label }}
										</a>
										</div>
									</td>

									<td class="text-center">{{ $record->displayDate($item->curation->report_date ?? '') }}</td>

									<td class="text-center"><a class="btn btn-xs btn-success btn-block" href="/gene-validity/{{ $item->curation->curie }}">View</a></td>
								</tr>
						@endforeach

						<!-- Actionability					-->



						<!-- Gene Dosage						-->


		@empty
		@endforelse
					</tbody>
        		</table>
			</div>
		</div>
		@endisset



		<!-- Gene Dosage Catchall -->
		@isset($record->curations_by_activity->dosage_curation)
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

		@forelse ($record->curations_by_activity->dosage_curation as $items)
						<!-- Gene-Disease Validity				-->
						@foreach($items as $item)
								<tr>
									<td class="">
										{{ $record->label }}
									</td>

									<td class="">
											{{ $item->disease->label ?? ""}}
									</td>

									<td class="">

									</td>

									<td class="text-center">

											@isset($item->curation->haploinsufficiency_assertion->dosage_classification->ordinal)
										<a class="btn btn-xs btn-outline-primary mb-1 btn-block" href="{{ $item->curation->curie ?? '#' }}">
											{{ \App\GeneLib::haploAssertionString($item->curation->haploinsufficiency_assertion->dosage_classification->ordinal ?? null) }}
											</a>
											@endif
											@isset($item->curation->triplosensitivity_assertion->dosage_classification->ordinal)
										<a class="btn btn-xs btn-outline-primary mb-1 btn-block " href="{{ $item->curation->curie ?? '#' }}">
											{{ \App\GeneLib::triploAssertionString($item->curation->triplosensitivity_assertion->dosage_classification->ordinal ?? null) }}
											</a>
											@endif

									</td>

									<td class="text-center">
										{{ $record->displayDate($item->curation->report_date ?? '') }}
									</td>

									<td class="text-center">
										<a class="btn btn-xs btn-success btn-block" href="{{ $item->curation->curie ?? '#' }}">View</a>
									</td>
								</tr>
						@endforeach

						<!-- Actionability					-->



						<!-- Gene Dosage						-->


		@empty
		@endforelse
					</tbody>
        		</table>
			</div>
		</div>
		@endisset

		@isset($record->curations_by_activity->actionability)
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

		@forelse ($record->curations_by_activity->actionability as $items)
						<!-- Gene-Disease Validity				-->
						@foreach($items as $item)
								<tr>
									<td class="">
										{{ $record->label }}
									</td>

									<td class="">
										{{ $item->disease->label }}
									</td>

									<td class="">
									</td>

									<td class=""><div class="mx-4">
										<a class="btn btn-xs btn-outline-primary mb-1 btn-block" href="{{ $item->curation->source }}">
											{{ $record->displayActionType($item->curation->source) }} Scoring Report
										</a>
										</div>
									</td>

									<td class="text-center">
										{{ $record->displayDate($item->curation->report_date ?? '') }}
									</td>


									<td class="text-center">
										<a class="btn btn-xs btn-success btn-block" href="{{ $item->curation->source }}">View</a>
									</td>
								</tr>
						@endforeach

						<!-- Actionability					-->



						<!-- Gene Dosage						-->


		@empty
		@endforelse
					</tbody>
        		</table>
			</div>
		</div>
		@endisset

    @if(empty($record->curations_by_activity ))
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
