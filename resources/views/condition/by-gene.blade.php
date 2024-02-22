@extends('layouts.app')
@php ($currations_set = false) @endphp

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"><img src="/images/disease.png" width="40" height="40"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">{{ displayMondoLabel($record->label) }}</h1>
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
  <a  href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}" class="btn btn-default ">Group By Activity</a>
  <a  href="{{ route('disease-by-gene', $record->getMondoString($record->iri, true)) }}" class="btn btn-primary active">Group By Gene-Disease Pair</a>
</div>

@endif

			@forelse ($record->genetic_conditions as $disease)



				<h3  id="link-gene-validity" style="" class="h3 mt-4 mb-0"><i><a class="text-dark" href="{{ route('gene-show', $disease->gene->hgnc_id) }}" >{{ $disease->gene->label }}</a></i> -
					{{ displayMondoLabel($record->label) }} {!! displayMondoObsolete($record->label) !!}</h3>
					<div class="card mb-5">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-3 th-curation-group text-left">Activity</th>
								<th class="col-sm-2 text-left">MOI</th>
								<th class="col-sm-2 text-left">Expert Panel / Working Group</th>
                                <th class="col-sm-2">Classification</th>
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
										<div>
                                            <a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?"> <img style="width:20px" src="/images/clinicalValidity-on.png" alt="Clinicalvalidity on"> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
                                        </div>
                                        @if (App\Validity::hasLumpingContent($validity) || App\Validity::secondaryContributor($validity) != "NONE")
                                            <div class="ml-4 badge badge-pill badge-light border-1 border-secondary action-expand-curation" data-uuid="{{ $validity->curie }}">
                                            @if (App\Validity::hasLumpingContent($validity))
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Lumping & Splitting"><i class="fas fa-random fa-sm mr-1"></i></span>
                                            @endif
                                            @if (App\Validity::secondaryContributor($validity) != "NONE")
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Secondary Contributor"><i class="fas fa-users fa-sm mr-1"></i></span>
                                            @endif
                                            <i class="fas fa-caret-down text-muted"></i>
                                            </div>
                                        @endif
                                        @endif
									</td>

									<td class=" @if(!$loop->first) border-0 @endif ">{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }}
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
									</td>

                                    <td>
                                        <a class="" href="https://clinicalgenome.org/affiliation/{{ App\Panel::gg_map_to_panel($validity->attributed_to->curie, true) }}">
                                            {{ $validity->attributed_to->label }} GCEP
                                            <i class="fas fa-external-link-alt ml-1"></i>
                                        </a>
                                        <!--<div class="action-expand-curation" data-uuid="{{ $validity->curie }}" data-toggle="tooltip" data-placement="top" title="Click to view additional information" ><span class="text-muted"><i><small>show more  </small></i><i class="fas fa-caret-down text-muted"></i></span></div>
                                        -->
                                        </td>

									<td class=" @if(!$loop->first) border-0 @endif ">
										<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="/kb/gene-validity/{{ $validity->curie }}">{{ \App\GeneLib::validityClassificationString($validity->classification->label) }}</a>
									</td>


									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->report_date) }}</a></td>
								</tr>
                                <!--<tr class="hide-element">
                                    <td colspan="6" class="no-row-border">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <table class="table-sm m-0">
                                                    <tr class="noborder no-row-border">
                                                        <td valign="top" class=" small text-muted pr-2">Secondary Contributors: </td>
                                                        <td class="small">{{ App\Validity::secondaryContributor($validity) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>-->
                                @if ($mimflag && (in_array($mimflag, $validity->las_included) || in_array($mimflag, $validity->las_excluded)))
                    <tr>
                    @else
                    <tr class="hide-element">
                    @endif
                        <td colspan="6" class="no-row-border">
                            <div class="ml-2 mr-2 shadow-none bg-lumping rounded">
                            <ul class="nav nav-pills border-bottom">
                                <li role="presentation" class="active">
                                    <a href="#las-{{ $validity->curie }}" aria-controls="las-{{ $validity->curie }}" role="tab" data-toggle="pill"><i class="fas fa-random mr-2"></i>Lumping & Splitting</a>
                                </li>
                                <li role="presentation">
                                    <a href="#sec-{{ $validity->curie }}" aria-controls="sec-{{ $validity->curie }}" role="tab" data-toggle="pill"><i class="fas fa-users mr-2"></i>Secondary Contributors</a>
                                </li>
                               <!-- <li role="presentation">
                                    <a href="#history-{{ $validity->curie }}" aria-controls="history-{{ $validity->curie }}" role="tab" data-toggle="pill"><i class="fas fa-history mr-2"></i>History</a>
                                </li>
                                <li role="presentation">
                                    <a href="#three-{{ $validity->curie }}" aria-controls="three-{{ $validity->curie }}" role="tab" data-toggle="pill"><i class="fas fa-disease mr-2"></i>Other Stuff</a>
                                </li>-->
                            </ul>
                            <div class=" ml-2 mr-2 mb-2 tab-content">
                                <div role="tabpanel" class="pt-3 pl-3 pb-2 tab-pane fade in active" id="las-{{ $validity->curie }}">
                                    <div class="bg-white border border-2 border-warning mr-3 p-2 mt-1 mb-3 rounded">
                                        Lumping and Splitting is the process by which ClinGen curation groups determine which disease entity they will use for evaluation.
                                        Groups review current disease and/or phenotype assertions (e.g. OMIM MIM phenotypes) and select the included and excluded phenotypes according to <a href="https://www.clinicalgenome.org/working-groups/lumping-and-splitting/" target="_doc">current guidelines</a>.
                                        MIM phenotypes represented below are those that were available on the stated evaluation date
                                    </div>
                                    @if (!empty($validity->las_included))
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Included MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are part of the disease entity used for curation."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->las_included as $mim)
                                                    <div class="mb-1">
                                                        @if ($mimflag == $mim)
                                                        <a class="highlight" href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @else
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @endif
                                                    <div>
                                                @endforeach
                                            <dd>
                                        </dl>
                                    @else
                                    <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Included MIM Phenotypes
                                            <span class="cursor-pointer" style="white-space: nowrap;" data-toggle="tooltip" data-placement="top" title="These phenotypes are part of the disease entity used for curation."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1 text-muted">
                                                <i>No Included MIM Phenotypes were specified</i>
                                            <div>
                                        <dd>
                                    </dl>
                                    @endif
                                    @if (!empty($validity->las_excluded))
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Excluded MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are not part of the disease entity used for curation.  This does not mean that these are not valid assertions, and could be curated separately."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->las_excluded as $mim)
                                                    <div class="mb-1">
                                                        @if ($mimflag == $mim)
                                                        <a class="highlight" href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @else
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </dd>
                                         </dl>
                                    @else
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Excluded MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are not part of the disease entity used for curation.  This does not mean that these are not valid assertions, and could be curated separately."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                <div class="mb-1 text-muted">
                                                    <i>No Excluded MIM Phenotypes were specified</i>
                                                <div>
                                            <dd>
                                        </dl>
                                     @endif
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Evaluation Date
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="The date Lumping and Splitting assessment was performed."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->las_date))
                                                    {{ date('m/d/Y', strtotime($validity->las_date)) }}
                                            @else
                                                <i class="text-muted">No Date was specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Curation Type
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->las_curation))
                                                    {{ $validity->las_curation }}
                                            @else
                                                <i class="text-muted">No curation type was specified</i>
                                            @endif
                                            <a href="https://www.clinicalgenome.org/docs/lumping-and-splitting" class="ml-2 small" target="_doc">(Read more about curation type)</a>
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Rationales
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->las_rationale['rationales']))
                                                    {{ implode(', ', $validity->las_rationale['rationales']) }}
                                            @else
                                                <i class="text-muted">No rationales were specified</i>
                                            @endif
                                            <a href="https://www.clinicalgenome.org/docs/lumping-and-splitting" class="ml-2 small" target="_doc">(Read more about curation type)</a>
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">PMIDs
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Literature supporting the Lumping and Splitting decisions."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->las_rationale['pmids']))
                                                @foreach ($validity->las_rationale['pmids'] as $pmid)
                                                @if (isset($pmids[$pmid]))
                                                    <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $pmid }}" target="_pmid" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="{{ $pmids[$pmid]['title'] }}">{{ $pmid }}</a>@if(!$loop->last), @endif
                                                @else
                                                    <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $pmid }}" target="_pmid">{{ $pmid }}</a>@if(!$loop->last), @endif
                                               @endif
                                                    @endforeach
                                            @else
                                                <i class="text-muted">No PMIDs were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Notes
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Optional free text explanation of the Lumping and Splitting decision."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->las_rationale['notes']))
                                                     {{ $validity->las_rationale['notes'] }}
                                            @else
                                                <i class="text-muted">No Notes were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 pb-2 tab-pane" id="sec-{{ $validity->curie }}">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3 text-right">Expert Panel:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                @if (App\Validity::secondaryContributor($validity) == "NONE")
                                                    <i class="text-muted">No Secondary Contributors were specified</i>
                                                @else
                                                    {{ App\Validity::secondaryContributor($validity) }}
                                                @endif
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane" id="history-{{ $validity->curie }}">
                                    <dl class="row">
                                        <dt class="col-sm-3">History:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                None
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane" id="three-{{ $validity->curie }}">
                                    <dl class="row">
                                        <dt class="col-sm-3">Other Stuff:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                Lorem Epsum
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                            </div>
                            </div>
                        </td>
                    </tr>
								@php ($first = false) @endphp
						@endforeach

						<!-- Actionability					-->
						@foreach($disease->actionability_assertions as $key => $actionability)
								@php ($first = true) @endphp
								<tr>
									<td class=" @if(!$loop->first) border-0 @endif ">
										@if($loop->first)
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/clinical-actionability/" data-content="How does this genetic diagnosis impact medical management?"> <img style="width:20px" src="/images/clinicalActionability-on.png" alt="Clinicalactionability on"> Clinical Actionability <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
										@endif
									</td>


									<td class=" @if(!$loop->first) border-0 @endif "></td>

                                    <td class=" @if(!$loop->first) border-0 @endif ">
                                        @if ($actionability->attributed_to->label == "Adult Actionability Working Group")
                                        <a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
                                            <i class="fas fa-external-link-alt ml-1"></i></a>
                                        @else
                                        <a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
                                            <i class="fas fa-external-link-alt"></i></a>
                                        @endif
                                    </td>

									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->source }}"><div class="text-muted small">{{ $record->displayActionType($actionability->source, true) }}</div> {{ App\Genelib::actionabilityAssertionString($actionability->classification->label) }}
													@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($actionability->classification->label)))</a></td>


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
                                        @if($first == true)
                                            <a href="https://clinicalgenome.org/working-groups/dosage-sensitivity-curation/" >
                                                Dosage Sensitivity WG
                                                <i class="fas fa-external-link-alt ml-1"></i></a>
                                        @endif
                                    </td>
									<td class=" @if(!$loop->first) border-0 @endif ">
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content="Dosage Sensitivity rating system">
											@if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
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

                        <!-- Variant Pathogenicity -->
                        @if (isset($variant_collection[$disease->gene->label]))
                        @php $variant_key = 0 @endphp
                        @foreach($variant_collection[$disease->gene->label]['classifications'] as $variant => $variant_count)
                        @if ($variant_count == 0)
                        @continue
                        @endif
                        <tr class="">
                            <td class="@if($variant_key != 0) border-0 pt-0 @endif pb-1 ">@if($variant_key == 0)<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/variant_pathogenicity/" data-content=""> <img style="width:20px" src="/images/variantPathogenicity-on.png" alt="VariantPathogenicity on"> Variant Pathogenicity <i class="glyphicon glyphicon-question-sign text-muted"></i></a></td> @endif</td>
                            <td class="@if($variant_key != 0) border-0 pt-0   @endif pb-1 "></td>
                            <td class="@if($variant_key != 0) border-0 pt-0  @endif pb-1 ">@if($variant_key == 0)<a href="https://clinicalgenome.org/affiliation/{{ \App\Panel::erepo_map_to_panel($variant_collection[$disease->gene->label]['panels'][0]['id']) }}">{{  implode(', ', array_column($variant_collection[$disease->gene->label]['panels'], 'affiliation')) }} <i class="fas fa-external-link-alt ml-1"></a>@endif</td>
                            <td class="text-center @if($variant_key != 0) border-0 pt-0 @endif pb-1 ">
                                    <div class="mb-0"><a class="btn btn-default btn-block text-left pt-1 btn-classification" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                                        {{ $variant }}  <span class="badge pull-right"><small>{{ $variant_count }}</small></span><br>
                                    </a>
                                    </div>
                            </td>
                            <td class=" text-center @if($variant_key != 0) border-0 pt-0  @endif  pb-1 ">
                                    <a class="btn btn-xs btn-success btn-block" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                                        <span class=""><i class="glyphicon glyphicon-file"></i>  Evidence</span>
                                    </a>

                            </td>

                        </tr>
                        @php $variant_key = 1 @endphp
                        @endforeach
                        @php unset($variant_collection[$disease->gene->label]); @endphp
                        @endif

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
                                <th class="col-sm-2 text-left">Expert Panel / Working Group</th>
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
                                        @if($first == true)
                                            <a href="https://clinicalgenome.org/working-groups/dosage-sensitivity-curation/" >
                                                Dosage Sensitivity WG
                                                <i class="fas fa-external-link-alt ml-1"></i></a>
                                        @endif
                                    </td>
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

        <!-- any items that are only variant pathogenicity -->
        @foreach ($variant_collection as $condition => $classes)
        <h3  id="link-variant=path" style="" class="h3 mt-3 mb-0"><i><a class="text-dark" href="{{ route('gene-show', 'ENTREZ:' . $classes['id']) }}" >{{ $condition }}</a></i> - {{ $record->symbol }}</h3>

        <div class="card mb-5 ">
            <div class="card-body p-0 m-0">
            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                    <th class="col-sm-3 th-curation-group text-left">Activity</th>
                    <th class="col-sm-2 text-left"></th>
                    <th class="col-sm-2 text-left">Expert Panel</th>
                    <th class="col-sm-2  ">Classification</th>
                    <th class="col-sm-1 text-center">Report &amp; Date</th>
                    </tr>
                </thead>
                <tbody class="">
        @php $variant_key = 0 @endphp
        @foreach($classes['classifications'] as $variant => $variant_count)
        @if ($variant_count == 0)
        @continue
        @endif
        <tr class="">
            <td class="@if($variant_key != 0) border-0 pt-0 @endif pb-1 ">@if($variant_key == 0)<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/variant_pathogenicity/" data-content=""> <img style="width:20px" src="/images/variantPathogenicity-on.png" alt="VariantPathogenicity on"> Variant Pathogenicity <i class="glyphicon glyphicon-question-sign text-muted"></i></a></td> @endif</td>
            <td class="@if($variant_key != 0) border-0 pt-0   @endif pb-1 "></td>
            <td class="@if($variant_key != 0) border-0 pt-0  @endif pb-1 ">@if($variant_key == 0)<a href="https://clinicalgenome.org/affiliation/{{ \App\Panel::erepo_map_to_panel($classes['panels'][0]['id']) }}">{{  implode(', ', array_column($classes['panels'], 'affiliation')) }} <i class="fas fa-external-link-alt ml-1"></a>@endif</td>
            <td class="text-center @if($variant_key != 0) border-0 pt-0 @endif pb-1 ">
                    <div class="mb-0"><a class="btn btn-default btn-block text-left pt-1 btn-classification" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                        {{ $variant }}  <span class="badge pull-right"><small>{{ $variant_count }}</small></span><br>
                    </a>
                    </div>
            </td>
            <td class=" text-center @if($variant_key != 0) border-0 pt-0  @endif  pb-1 ">
                    <a class="btn btn-xs btn-success btn-block" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                        <span class=""><i class="glyphicon glyphicon-file"></i>  Evidence</span>
                    </a>

            </td>

        </tr>
        @php $variant_key = 1 @endphp
        @endforeach
        </tbody>
    </table>
</div>
</div>
    @endforeach

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
