@extends('layouts.app')
@php ($currations_set = false) @endphp

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"><img src="/images/disease.png" width="40" height="40"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">{{ displayMondoLabel($disease->label) }}</h1> {!! displayMondoObsolete($disease->label) !!}
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
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">{{ $record->naction ?? '0' }}</span><br /> Clinical Actionability<br />Assertions</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">{{ $record->nvariant ?? '0' }}</span><br /> Variant Pathogenicity<br />Assertions</li>
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
            <a href="{{ route('condition-groups', \App\Disease::normal_base($record->iri)) }}" class="">Status and Future Work <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $total_panels ?? 0 }}</span></a>
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
  <a  href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}" class="btn btn-primary active">Group By Activity</a>
  <a  href="{{ route('disease-by-gene', $record->getMondoString($record->iri, true)) }}" class="btn btn-default">Group By Gene-Disease Pair</a>
</div>


			@endif

			@php global $currations_set; $currations_set = false; @endphp

			@include('condition.includes.validity')


				@php ($header_dos = true) @endphp
				@forelse ($record->genetic_conditions as $disease)
				@if(count($disease->gene_dosage_assertions))
				@php ($currations_set = true) @endphp
				@if($header_dos == true)
					<h3 id="link-dosage-curation" class="mb-0"><img style="margin-top:-4px" src="/images/dosageSensitivity-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Dosage Sensitivity</h3>
					<div class="card mb-3">
						<div class="card-body p-0 m-0">

						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-3 text-left"> Disease</th>
                                <th class="col-sm-1 text-center"></th>
								<th class="col-sm-2 text-left">Working Group</th>
								<th class="col-sm-2">HI Score &amp; TS Score</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>

							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@forelse($disease->gene_dosage_assertions as $i => $dosage)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												<a href="{{ route('gene-show', $disease->gene->hgnc_id) }}">{{ $disease->gene->label }}</a>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                                {{ displayMondoLabel($record->label) }}
                                                {!! displayMondoObsolete($record->label) !!}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

                                            <td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                                @if($first == true)
                                                <a href="https://clinicalgenome.org/working-groups/dosage-sensitivity-curation/" >
                                                    Dosage Sensitivity WG
                                                    <i class="fas fa-external-link-alt ml-1"></i></a>
                                                    @endif
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-default btn-block text-left  mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
													@if ($dosage->assertion_type  != "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-default btn-block text-left   mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($dosage->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@empty

								@php ($first = true) @endphp
								@foreach($record->dosage_curation_map as $key => $value)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												<a href="{{ route('gene-show', $disease->gene->hgnc_id) }}">{{ $disease->gene->label }}</a>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">

											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if ($key == "haploinsufficiency_assertion")
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@else
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@endforelse
								@php ($header_dos = false) @endphp

				@endisset
				@empty
				@endforelse
				{{-- CHeck if no diseases for dosage and loop through --}}
					@if(!empty($record->dosage_curation ) && !empty($record->dosage_curation_map))
					@if($header_dos == true)
					@php ($currations_set = true) @endphp
					<h3 id="link-dosage-curation" class="mb-0"><img style="margin-top:-4px" src="/images/dosageSensitivity-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Dosage Sensitivity</h3>
					<div class="card mb-3">
						<div class="card-body p-0 m-0">

						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> </th>
								<th class="col-sm-2 text-center"></th>
								<th class="col-sm-2">HI Score &amp; TS Score</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>

							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@foreach($record->dosage_curation_map as $key => $value)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($loop->first)
												<a href="{{ route('gene-show', $disease->gene->hgnc_id) }}">{{ $disease->gene->label }}</a>
												@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">

											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if ($key == "haploinsufficiency_assertion")
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@else
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $disease->gene->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@php ($header_dos = false) @endphp
								@endisset


				@if($header_dos == false)
							</tbody>
						</table>
					</div>
				</div>
				@endisset

				@php ($header_aci = true) @endphp
				@forelse ($record->genetic_conditions as $key => $disease)
					@if(count($disease->actionability_assertions))
				  @if($header_aci == true)
					@php ($currations_set = true) @endphp
					<h3 id="link-actionability" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Clinical Actionability</h3>
					<div class="card mb-3">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> Disease</th>
								<th class="col-sm-2">Working Group</th>
								<th class="col-sm-2">Assertions</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>

							<tbody class="">
					@endif
								@php ($first = true) @endphp
								@foreach($disease->actionability_assertions as $i => $actionability)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($first == true) <a href="{{ route('gene-show', $disease->gene->hgnc_id) }}">{{ $disease->gene->label }}</a> @endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($first == true) {{ displayMondoLabel($record->label) }}
                                                {!! displayMondoObsolete($record->label) !!} 
												<div class="text-muted small">{{ $record->getMondoString($record->iri, true) }}</div>
					
												@php
												$i = strpos($actionability->source, '?doc=');
												$k = substr($actionability->source, $i + 5);
												$j = ($actionability->attributed_to->label == "Adult Actionability Working Group" ? 'adult' : 'ped');
												//dd($record);
												@endphp
												@if($record->getMondoString($record->iri, true) != $actionability_reports[$disease->gene->hgnc_id][$k][$j])
												<div class="">
													The preferred disease is {{ $actionability_reports[$disease->gene->hgnc_id][$k][$j] }}
												</div>
												@endif
												@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                                @if ($actionability->attributed_to->label == "Adult Actionability Working Group")
                                                    <a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
                                                        <i class="fas fa-external-link-alt ml-1"></i></a>
                                                @else
                                                    <a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
                                                        <i class="fas fa-external-link-alt"></i></a>
                                                @endif

											</td>

											<td class="  @if($first != true) border-0  pt-0 @else pb-0 @endif text-center">
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->source }}">
													<div class="text-muted small">{{ $record->displayActionType($actionability->source, true) }}</div> {{ App\Genelib::actionabilityAssertionString($actionability->classification->label) }}
													@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($actionability->classification->label)))
													</a>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif  text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ $actionability->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($actionability->report_date) }}</a></td>
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

				{{-- @if (!empty($record->pharma))
				@php ($currations_set = true) @endphp
				<div class="row justify-content-center">
					<div class="col-md-12">
					  <h3 id="link-gene-validity" class=" mt-3 mb-0"><img
						  src="/images/Pharmacogenomics-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Pharmacogenetics - <a href="https://cpicpgx.org/"><img src="/images/cpic-200.png" class="mb-2" height="30"></a></h3>
					  <div class="card mb-4">
						<div class="card-body p-0 m-0">
						  <table class="panel-body table mb-0">
							<thead class="thead-labels">
							  <tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 ">Drug</th>
								<th class="col-sm-2">CPIC Level</th>
								<th class="col-sm-2">Date</th>
								<th class="col-sm-1 text-center">CPIC Clinical Guidelines</th>
							  </tr>
							</thead>
							<tbody class="">
							  @foreach($record->pharma as $idx => $entry)
								@php $border = ($idx > 0 && $entry['guideline'] == $record->pharma[$idx - 1]['guideline'] ? "border-0" : ""); @endphp
								<tr>
									<td class="{{ $border ?? '' }}">{{ isset($border) && $border == "" ? $entry['gene'] : ''  }}</td>
								<td class="{{ $border ?? '' }}">{{  $entry['drug'] }}</td>
								<td class="{{ $border ?? '' }}">
									@if (empty($entry['guideline']))
											<a href="https://cpicpgx.org/genes-drugs/">Level {{ $entry['cpic_level'] }}</a>
									@else
									<a href="https://cpicpgx.org/genes-drugs/">Level {{ $entry['cpic_level'] }}</a>
									@endif
								</td>
								<td class="{{ $border ?? '' }}">{{ isset($border) && $border == "" ? '10/14/2020' : ''  }}</td>
								@if (isset($border) && $border == "")
									@if (empty($entry['guideline']))
									<td class=" text-center {{ $border ?? '' }}"><a class="btn btn-xs btn-success" target="_pharma" href="https://cpicpgx.org/genes-drugs">  <span class="pl-3 pr-3"><i class="glyphicon glyphicon-file"></i>  None</span></a></td>
									@else
									<td class=" text-center {{ $border ?? '' }}"><a class="btn btn-xs btn-success" target="_pharma" href="{{ $entry['guideline'] }}"><span class="pl-3 pr-3"><i class="glyphicon glyphicon-file"></i>  Guideline</span></a></td>
									@endif
								@else
								<td class="{{ $border ?? '' }}"></td>
								@endif
							  </tr>
							  @endforeach
							</tbody>
						  </table>
						</div>
					  </div>
					</div>
				  </div>
				@endif --}}

				{{-- @if (!empty($record->pharma))
				@php ($currations_set = true) @endphp
				<div class="row justify-content-center">
					<div class="col-md-12">
					  <h3 id="link-gene-validity" class=" mt-3 mb-0"><img
						  src="/images/Pharmacogenomics-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Pharmacogenetics  - <a href="https://www.pharmgkb.org/"><img src="/images/pharmgkb.png" height="25"></a> </h3>
					  <div class="card mb-4">
						<div class="card-body p-0 m-0">
						  <table class="panel-body table mb-0">
							<thead class="thead-labels">
							  <tr>
								<th class="col-sm-1 ml-3 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 ">Drug</th>
								<th class="col-sm-2">Highest Level of Evidence</th>
								<th class="col-sm-2">Date</th>
								<th class="col-sm-1 text-center">Information</th>
							  </tr>
							</thead>
							<tbody class="">
							  @foreach($record->pharma as $idx => $entry)
							  @php $border = (isset($first) && $first  ? "border-0" : ""); @endphp
							  @if ($entry['pharmgkb_level_of_evidence'] == null)
								@continue;
							  @else
								@php $first = true; @endphp
							  @endif
							  <tr>
								<td class="border-0">{{ isset($border) && $border == "" ? $entry['gene'] : ''  }}</td>
								<td class="border-0"><a href="https://www.pharmgkb.org/chemical/{{ $entry['pa_id_drug'] }}">{{ $entry['drug'] }}</a></td>
								<td class="border-0">
									<a href="https://www.pharmgkb.org/page/clinAnnLevels">Level {{ $entry['pharmgkb_level_of_evidence'] }}</a></td>
									<td class="{{ $border ?? '' }}">{{ isset($border) && $border == "" ? '10/14/2020' : ''  }}</td>
								@if (isset($border) && $border == "")
									<td class="border-0 text-center"><a class="btn btn-xs btn-success" target="_pharma" href="https://www.pharmgkb.org/gene/{{ $entry['pa_id'] }}/clinicalAnnotation"><span class="pl-3 pr-3"><i class="glyphicon glyphicon-file"></i>  View</span></a></td>
								@else
								<td class="{{ $border ?? '' }}"></td>
								@endif
							  </tr>
							  @endforeach
							</tbody>
						  </table>
						</div>
					  </div>
					</div>
				  </div>
					@endif --}}

                @include('condition.includes.variant')

				{{-- Check to see if curations are showing --}}
				@if($currations_set == false)
						<br clear="both" />
						<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet curated {{ displayMondoLabel($record->label) }} {!! displayMondoObsolete($record->label) !!}.</strong> <br />View <a href="{{ route('condition-external', $record->getMondoString($record->iri, true)) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->getMondoString($record->iri, true) }}">ClinVar</a>.</div>
				@endif

@endsection

@section('heading')
<div class="content ">
	<div class="section-heading-content">
	</div>
</div>
@endsection

@section('script_js')
<script>

$(function() {

    $('.action-expand-curation').on('click', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').next('tr');
        row.toggle();

        var chk = $(this).find('small');
        if (chk.html() == "show more  ")
            chk.html('show less  ');
        else
            chk.html('show more  ');

        chk = $(this).find('i.fas');
        if (chk.hasClass('fa-caret-down'))
            chk.removeClass('fa-caret-down').addClass('fa-caret-up');
        else
        chk.removeClass('fa-caret-up').addClass('fa-caret-down');
    });

});

</script>

@endsection
