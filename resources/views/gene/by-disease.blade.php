@extends('layouts.app')

@section('content-heading')

@include('gene.includes.follow')

<div class="row mb-1 mt-1">

    @include('gene.includes.facts')

    <div class="col-md-9 col-xs-3 col-sm-4 mt-2 stats-banner">
        <div class="pb-0 mb-0 small float-right">
            <div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countCurations text-18px">{{ $record->nvalid }}</span><br />Gene-Disease Validity Classifications</div>
            <div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countGenes text-18px">{{ $record->ndosage }}</span><br />Dosage Sensitivity Classifications</div>
            <div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->naction }}</span><br />Clinical Actionability Assertions</div>
            <div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->nvariant }}</span><br />Variant Pathogenicity Assertions</div>
            <div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->ncpc }} / {{ $record->npharmgkb }}</span><br />CPIC / PharmGKB High Level Records</div>
            @if ($follow)
            <div class="text-stats line-tight col-md-2 text-center px-1"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:green"></i></span><br /> Follow Gene</div>
            @else
            <div class="text-stats line-tight col-md-2 text-center px-1"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:lightgray"></i></span><br /> Follow Gene</div>
            @endif
        </div>
    </div>

		@include("_partials.facts.gene-panel")
</div>

			<ul class="nav nav-tabs mt-1" style="">
          {{-- <li class="" style="margin-bottom: 0px;">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="pt-2 pb-2 text-primary">
              Curations By Disease
            </a>
					</li> --}}
					<li class="active" style="">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="">
              <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
          </li>
          <li class="" style="">
            <a href="{{ route('gene-groups', $record->hgnc_id) }}" class="">Status and Future Work <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $total_panels }}</span></a>
          </li>
          <li class="" style="">
            <a href="{{ route('gene-external', $record->hgnc_id) }}" class=""><span class='hidden-sm hidden-xs'>External Genomic </span>Resources </a>
          </li>
          <li class="" style="">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D"  class="" target="clinvar">ClinVar <span class='hidden-sm hidden-xs'>Variants  </span><i class="glyphicon glyphicon-new-window small" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">

@if((!empty($record->dosage_curation ) && !empty($record->dosage_curation_map)) OR (!empty($record->genetic_conditions))  OR (!empty($record->pharma)))
				<div class="btn-group  btn-group-xs float-right" role="group" aria-label="...">
					<a  href="{{ route('gene-show', $record->hgnc_id) }}" class="btn btn-default">Group By Activity</a>
					<a  href="{{ route('gene-by-disease', $record->hgnc_id) }}" class="btn btn-primary active">Group By Gene-Disease Pair</a>
				</div>
			@endif

		@forelse ($record->genetic_conditions as $disease)



				<h3  id="link-gene-validity" style="" class="h3 mt-4 mb-0"><i>{{ $record->symbol }}</i> -
					<a class="text-dark" href="{{ route('condition-show', $record->getMondoString($disease->disease->iri, true)) }}" >{{ displayMondoLabel($disease->disease->label) }} <span class="text-muted small">({{ $record->getMondoString($disease->disease->iri, true) }}) {!! displayMondoObsolete($disease->disease->label) !!}</span></a></h3>
					<div class="card mb-5">
						<div class="card-body p-0 m-0">
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-3 th-curation-group text-left">Activity</th>
								<th class="col-sm-2 text-left">MOI</th>
                                <th class="col-sm-2 text-left">Expert Panel / Working Group</th>
								<th class="col-sm-2  ">Classification</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>
							<tbody class="">


						<!-- Gene-Disease Validity -->
						@if (isset($disease_collection->where('disease', $disease->disease->label)->first()->validity))
							@foreach($disease_collection->where('disease', $disease->disease->label)->first()->validity as $validity)
									@php ($first = true) @endphp
									<tr>
										<td class=" @if(!$loop->first) border-0 @endif ">
											@if($loop->first)
											<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?"> <img style="width:20px" src="/images/clinicalValidity-on.png" alt="Clinicalvalidity on"> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>
                                            @endif
										</td>
										<td class=" @if(!$loop->first) border-0 @endif ">{{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }}
											<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
										</td>

                                        <td>
                                            <a class="" href="https://clinicalgenome.org/affiliation/{{ App\Panel::gg_map_to_panel($validity->assertion->attributed_to->curie, true) }}">
                                                {{ $validity->assertion->attributed_to->label }} GCEP
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                            <div class="action-expand-curation" data-uuid="{{ $validity->assertion->curie }}" data-toggle="tooltip" data-placement="top" title="Click to view additional information" ><span class="text-muted"><i><small>show more  </small></i><i class="fas fa-caret-down text-muted"></i></span></div>
                                        </td>

										<td class=" @if(!$loop->first) border-0 @endif ">
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="/kb/gene-validity/{{ $validity->assertion->curie }}">{{ \App\GeneLib::validityClassificationString($validity->assertion->classification->label) }}</a>
										</td>


										<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->assertion->report_date) }}</a></td>
									</tr>
                                    <tr class="hide-element">
                                        <td colspan="6" class="no-row-border">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <table class="table-sm m-0">
                                                        <tr class="noborder no-row-border">
                                                            <td valign="top" class=" small text-muted pr-2">Secondary Contributors: </td>
                                                            <td class="small">{{ App\Validity::secondaryContributor($validity->assertion) }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
									@php ($first = false) @endphp
							@endforeach
						@endif

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
										@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($actionability->classification->label)))
									</a>
									</td>


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
										<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://dosage.clinicalgenome.org/help.shtml#review" data-content=" Dosage Sensitivity rating system">
											@if ($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
											</a>
											@else
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">
												{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
											</a>
											@endif
										</a>
									</td>
									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $record->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($dosage->report_date) }}</a></td>
								</tr>
								@php ($first = false) @endphp
						@endforeach

                        <!-- Variant Pathogenicity -->
                        @if (isset($variant_collection[$disease->disease->label]))
                        @php $variant_key = 0 @endphp
                        @foreach($variant_collection[$disease->disease->label]['classifications'] as $variant => $variant_count)
                        @if ($variant_count == 0)
                        @continue
                        @endif
                        <tr class="">
                            <td class="@if($variant_key != 0) border-0 pt-0 @endif pb-1 ">@if($variant_key == 0)<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/variant_pathogenicity/" data-content=""> <img style="width:20px" src="/images/variantPathogenicity-on.png" alt="VariantPathogenicity on"> Variant Pathogenicity <i class="glyphicon glyphicon-question-sign text-muted"></i></a></td> @endif</td>
                            <td class="@if($variant_key != 0) border-0 pt-0   @endif pb-1 "></td>
                            <td class="@if($variant_key != 0) border-0 pt-0  @endif pb-1 ">@if($variant_key == 0)<a href="https://clinicalgenome.org/affiliation/{{ \App\Panel::erepo_map_to_panel($variant_collection[$disease->disease->label]['panels'][0]['id']) }}">{{  implode(', ', array_column($variant_collection[$disease->disease->label]['panels'], 'affiliation')) }} <i class="fas fa-external-link-alt ml-1"></a>@endif</td>
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
                        @php unset($variant_collection[$disease->disease->label]); @endphp
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
					<div class="card mb-5">
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
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">
												{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
											</a>
											@else
											<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">
												{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
											</a>
											@endif


									</td>
									<td class=" @if(!$loop->first) border-0 @endif "><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $record->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
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
                    <h3  id="link-variant=path" style="" class="h3 mt-3 mb-0"><i>{{ $record->symbol }}</i> -
                    <a class="text-dark" href="{{ route('condition-show', $classes['id']) }}" >{{ $condition }} <span class="text-muted small">({{ $classes['id'] }})</span></a></h3>

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

				@if(empty($record->dosage_curation ) && empty($record->genetic_conditions ) && empty($variant_collection))
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

@section('modals')

	@include('modals.followgene', ['gene' => $record->hgnc_id])
	@include('modals.unfollowgene', ['gene' => $record->hgnc_id])

@endsection

@section('script_js')
<script>
	window.token = "{{ csrf_token() }}";
	window.bearer_token = Cookies.get('clingen_dash_token');
</script>

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

<script>

$(function() {
	window.auth = {{ Auth::guard('api')->check() ? 1 : 0 }};
	var context = false;
	var gene = "{{ $record->hgnc_id ?? ''}}";

	$('.action-follow-gene').on('click', function() {

		var color = $(this).find('.fa-star').css('color');

		if (color == "rgb(0, 128, 0)"){
			if (window.auth)
			{
				// TODO:  create fake form and post it
				$('#unfollow_form').submit();
				$(this).find('.fa-star').css('color', 'lightgray');
				return;
			}
			$(this).find('.fa-star').css('color', 'lightgray');
		}
		else
		{
			if (window.auth)
			{
				// TODO:  create fake form and post it
				$('#follow_form').submit();
				$(this).find('.fa-star').css('color', 'green');
				return;
			}
			context = true;

			$('#login-context-value').val(gene);
			$('#register-context-value').val(gene);
			$('#follow-gene-id').collapse("show");
		}
	});


	$('.action-follow-cancel').on('click', function() {
		context = false;
		$('#follow-gene-email').val('');
		$('#login-context-value').val('');
		$('#register-context-value').val('');
		$('#follow-gene-id').collapse("hide");
	});


	$( '#follow_form' ).validate( {
		submitHandler: function(form) {

			$.ajaxSetup({
				cache: true,
				contentType: "application/x-www-form-urlencoded",
				processData: true,
				headers:{
					'X-Requested-With': 'XMLHttpRequest',
    				'X-CSRF-TOKEN' : window.token,
    				'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
   				}
			});

			var url = "/api/genes/follow";

			var formData = $(form).serialize();

			//submits to the form's action URL
			$.post(url, formData, function(response)
			{
				//alert(JSON.stringify(response));

				/*if (response['message'])
				{
					swal("Done!", response['message'], "success")
						.then((answer2) => {
							if (answer2){*/
								$('#follow-gene-id').collapse("hide");
								$('#follow-gene-email').val('');
								$('.action-follow-gene').find('.fa-star').css('color', 'green');

							/*}
					});
				}*/
			}).fail(function(response)
			{
				//handle failed validation
				alert("Error following gene.  Bad email address?");
			});

		},
		rules: {
			email: {
				required: true,
				email: true,
				maxlength: 80
			}
		},
		messages: {
			email:  {
				required: "Please enter your email address",
				email: "Please enter a valid email address",
				maxlength: "Section names must be less than 80 characters"
			},
		},
		errorElement: 'em',
		errorClass: 'invalid-feedback',
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "invalid-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
		}
	});


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


	$( '#unfollow_form' ).validate( {
		submitHandler: function(form) {

			$.ajaxSetup({
				cache: true,
				contentType: "application/x-www-form-urlencoded",
				processData: true,
				headers:{
					'X-Requested-With': 'XMLHttpRequest',
    				'X-CSRF-TOKEN' : window.token,
    				'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
   				}
			});

			var url = "/api/genes/unfollow";

			var formData = $(form).serialize();

			//submits to the form's action URL
			$.post(url, formData, function(response)
			{
				//alert(JSON.stringify(response));

				/*if (response['message'])
				{
					swal("Done!", response['message'], "success")
						.then((answer2) => {
							if (answer2){*/
								$('.action-follow-gene').find('.fa-star').css('color', 'lightgray');
							/*}
					});
				}*/
			}).fail(function(response)
			{
				//handle failed validation
				alert("Error following gene");
			});

			$('#modalUnFollowGene').modal('hide');
		},
		rules: {
			email: {
				required: true,
				email: true,
				maxlength: 80
			}
		},
		messages: {
			email:  {
				required: "Please enter your email address",
				email: "Please enter a valid email address",
				maxlength: "Section names must be less than 80 characters"
			},
		},
		errorElement: 'em',
		errorClass: 'invalid-feedback',
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "invalid-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
		}
	});

});
</script>

@endsection
