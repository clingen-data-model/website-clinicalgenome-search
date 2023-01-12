@extends('layouts.app')

@section('content-heading')

	@include('gene.includes.follow')

	<div class="row mb-1 mt-1">

        <div class="col-md-9">
            <table class="mt-3 mb-4">
                <tr>
                    <td class="valign-top"><img src="/images/clinicalValidity-on.png" width="40" height="40"></td>
                    <td class="pl-2"><h1 class="h2 p-0 m-0">Gene-Disease Validity Details</h1></td>
                </tr>
            </table>
        </div>

		<!--<div class="col-md-6 col-xs-3 col-sm-4 mt-2 stats-banner">
			<div class="pb-0 mb-0 small float-right">
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countCurations text-18px">{{ $record->nvalid }}</span><br />Gene-Disease Validity Classifications</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countGenes text-18px">{{ $record->ndosage }}</span><br />Dosage Sensitivity Classifications</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->ncpc }} / {{ $record->npharmgkb }}</span><br />CPIC / PharmGKB High Level Records</div>

            </div>
		</div>-->

        <div class="col-md-3 mt-2 stats-banner">
			<div class="pb-0 mb-0 small float-right">
                <div class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('gene-show', $record->gene->hgnc_id) }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Gene</a></div>
			</div>
		</div>

	</div>

    <hr />

    <div class="row pb-2 pt-3">
        <div class="col-md-8">
            <div class="row">
                <dt class="col-sm-3">Gene:
                <dd class="col-sm-9 mb-2">
                    {{ $record->gene->label }}
                    <div class="text-muted small">{{ $record->gene->hgnc_id }}</div>
                </dd>
                <dt class="col-sm-3">Disease:
                <dd class="col-sm-9 mb-2">
                    {{ $record->disease->label }}
                    <div class="text-muted small">{{ $record->disease->curie }}</div>
                </dd>
                <!--<dt class="col-sm-3">Mode of Inheritance:
                <dd class="col-sm-9">
                    {{ $record->mode_of_inheritance->label ?? '-' }}
                    <div class="text-muted small">{{ $record->mode_of_inheritance->curie ?? '' }}</div>
                </dd>-->
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div class='badge badge-primary' style="font-size: 20px; padding:15px;">
                <a tabindex="0" class="text-white" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" role="button" data-title="Learn more about classifications" href="https://www.clinicalgenome.org/docs/gene-disease-validity-classification-information/" target="_info" data-content="Click here to learn more about Gene-Disease Validity classification and scoring.">{{ App\GeneLib::validityClassificationString($record->classification->label ?? null) }}  <i class="glyphicon glyphicon-info-sign text-white"></i></a>
            </div>
            <div>Classification - {{ displayDate($record->report_date ?? null) }}</div>
            @if ($record->animalmode)
            <div class='badge badge-warning mt-1 p-2'>
                Animal Model Only
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                <dt class="col-sm-4">Mode of Inheritance:
                <dd class="col-sm-8">
                    {{ $record->mode_of_inheritance->label ?? '-' }}
                    <div class="text-muted small">{{ $record->mode_of_inheritance->curie ?? '' }}</div>
                </dd>
            </div>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-5">
            <div class="row">
                <dt class="col-sm-2">SOP:
                <dd class="col-sm-10">
                    {{ $record->specified_by->label ?? 'Not Specified' }}
                    <a href="{{ App\Validity::locationSOP($record->specified_by->label ?? '#') }}" target="_sop"><i class="fas fa-external-link-alt ml-1"></i></a>
                    <div class="text-muted small">&nbsp;</div>
                </dd>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <dt class="col-sm-4">Expert Panel:
                <dd class="col-sm-8">
                    {{ $record->sop7_affiliation_name ?? '' }}
                </dd>
            </div>
        </div>
        <div class="col-md-1">
            &nbsp;
        </div>
        <div class="col-md-5">
            <div class="row">
                @if ($record->sop7_contributors ?? null)
                <dt class="col-sm-3">Contributors:
                <dd class="col-sm-9">
                        @foreach ($record->sop7_contributors as $contributor)
                            {{ $contributor->name ?? null }}
                            <div class="text-muted small">({{ ucwords($contributor->role) ?? null }})</div>
                        @endforeach
                </dd>
                @else
                &nbsp;
                @endif
            </div>
        </div>
        <div class="col-md-6 mt-2">
            <div class="row">
                <dt class="col-sm-4">Replication over time:
                <dd class="col-sm-8">
                    {{ $record->sop7_replication_over_time ?? '' }}
                </dd>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <dt class="col-sm-5">Contradictory Evidence:
                <dd class="col-sm-7">
                    {{ $record->sop7_valid_contradictory_evidence }}
                </dd>
            </div>
        </div>
        @if ($gcilink !== null)
        <div class="col-md-12 mt-4">
            <div class="row">
                <dt class="col-sm-4">GCI LINK:
                    <dd class="col-sm-8"><a href="{{ $gcilink }}" target="_gci">{{ $gcilink }}</a>
                    </dd>
            </div>
        </div>
        @endif
        @if(isset($extrecord))
        <div class="col-md-12 mt-4">
            <div class="row">
                <div class="alert alert-warning" style="box-shadow: 0 0 10px red;" role="alert">
                    <img src="/images/beta.png" height="45" class="pull-right">
                    <h4>Gene-Disease Validity Evidence Display (Beta Release)</h4>
                    New to this release is the more granular display of individual pieces of evidence supporting the gene-disease classification for curations.
                    Newly available evidence includes Genetic Evidence, Experimental Evidence, and Non-Scorable Evidence. Information is viewable by selecting the appropriate tab below.
                    The “Summary” tab remains unchanged from previous releases.  For some curations, particularly those completed prior to 2018, evidence detail may not currently be available and only the summary tab will be shown.
                    <div class="collapse" id="show-beta-more">
                        <p class="mt-2">
                            This feature has been extensively tested internally. However, due to the volume of data presented, it is possible some early deployment issues may still be present.
                            Please note, information in free text fields was not originally intended for public display, therefore typographical or grammatical errors may be present.
                        </p>
                        <p>
                            This new, more detailed information is offered to ClinGen users as a Beta feature.
                            We welcome any feedback from the community related to display errors or suspected content inaccuracy.
                            To send feedback, click on the "Evidence Feedback" button and complete the form.
                        </p>
                    </div>
                    <span class="text-primary" data-toggle="collapse" href="#show-beta-more" role="button" aria-expanded="false" aria-controls="show-beta-more">
                        <i class="action-read-more read-more-text ml-2">Read more...</i>
                    </span>

                    <div class="text-center mt-2"><button type="button" class="btn btn-success action-beta-form">Evidence Feedback</button></div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <hr />

	<!-- tab headers -->
	<ul class="nav nav-tabs mt-1" style="">
		<li role="presentation" class="active" style="">
            <a href="#gdvt1" aria-controls="gdvt1" role="tab" data-toggle="tab">
              <span class='hidden-sm hidden-xs'><i class="fas fa-file-alt mr-1"></i>Summary</span>
            </a>
        </li>
        @if ($extrecord !== null)
          <li role="presentation" class="" style="">
            <a href="#gdvt2" aria-controls="gdvt2" role="tab" data-toggle="tab">
                <span class='hidden-sm hidden-xs'><i class="fas fa-dna mr-1"></i>Genetic Evidence</span>
                @if ($ge_count !== null || $cc_count !== null)
                    <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-title="Total Capped Points" data-content="The maximum number of genetic evidence points is 12.  Additional points may be documented, but will not be tallied beyond the maximum.  The presence of an * indicates that the scored evidence exceeded the maximum and was capped at 12. ">{{ number_format(min(($ge_count ?? 0) + ($cc_count ?? 0) + ($cls_pt_count ?? 0), 12.00), 2) }} {!! ($ge_count ?? 0) + ($cc_count ?? 0) + ($cls_pt_count ?? 0) > 12 ? '<i class="fa fa-asterisk"></i>' : '' !!}</span>
                @endif
            </a>
          </li>
		<li role="presentation" class="" style="">
			<a href="#gdvt4" aria-controls="gdvt4" role="tab" data-toggle="tab">
                <span class='hidden-sm hidden-xs'><i class="fas fa-microscope mr-1"></i>Experimental Evidence</span>
                @if ($exp_count !== null)
                    <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1" data-container="body" data-toggle="popover" data-placement="top" data-trigger="hover" data-title="Total Capped Points" data-content="The maximum number of experimental evidence points is 6.  Additional points may be documented, but will not be tallied beyond the maximum.  The presence of an * indicates that the scored evidence exceeded the maximum and was capped at 6.">{{ number_format(min($exp_count ?? 0, 6.00), 2) }} {!! ($exp_count ?? 0) > 6 ? '<i class="fa fa-asterisk"></i>' : ''  !!}</span>
                @endif
            </a>
		</li>
        <li role="presentation" class="" style="">
			<a href="#gdvt6" aria-controls="gdvt6" role="tab" data-toggle="tab">
                <span class='hidden-sm hidden-xs'><i class="far fa-clipboard mr-1"></i>Non-Scorable Evidence</span>
            </a>
		</li>
        <li role="presentation" class="" style="">
			<a href="#gdvt5" aria-controls="gdvt5" role="tab" data-toggle="tab">
                <span class='hidden-sm hidden-xs'><i class="fas fa-random fa-sm mr-1"></i>Lumping & Splitting </span></a>
		</li>
        <li role="presentation" class="" style="">
			<a href="#gdvt7" aria-controls="gdvt7" role="tab" data-toggle="tab">
                <span class='hidden-sm hidden-xs'><i class="fas fa-asterisk mr-1"></i>References</span>
            </a>
		</li>
        @else
        <span class="pull-right mt-2 mr-5 text-danger"><b><i>Additional evidence details have not been made available for this particular Gene-Disease assertion </i></b></span>
        @endif
	</ul>

@endsection


@section('content-full-width')
<section id='validity_supporting_data' class="container-fluid">


@if(isset($extrecord))
<div class="row">
    <div class="col-sm-12">
       <!-- <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#gdvt1" aria-controls="gdvt1" role="tab" data-toggle="tab">Summary</a></li>
            <li role="presentation">
                <a href="#gdvt2" aria-controls="gdvt2" role="tab" data-toggle="tab">Genetic Evidence
                    @if ($ge_count !== null)
                    <span class="badge ml-2">{{ $ge_count }}</span>
                    @endif
                </a>
            </li>
            <li role="presentation">
                <a href="#gdvt4" aria-controls="gdvt4" role="tab" data-toggle="tab">Experimental Evidence
                    @if ($exp_count !== null)
                    <span class="badge ml-2">{{ $exp_count }}</span>
                    @endif
                </a>
            </li>
        </ul>-->

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="gdvt1">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="panel-heading" style="height:71px;">
                        <h4>Summary</h4>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="gene_validity_show" class="container">
                                <div class="row geneValidityScoresWrapper">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if($record->json_message_version == "GCI.8.1" || strpos($record->specified_by->label,"SOP9"))
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop8-1')
                                            @elseif(strpos($record->specified_by->label,"SOP8"))
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop7')
                                            @elseif(strpos($record->specified_by->label,"SOP7"))
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop7')
                                            @elseif (strpos($record->specified_by->label,"SOP6"))
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop6')
                                            @elseif (strpos($record->specified_by->label,"SOP5") && (isset($record->json->sopVersion) && $record->json->sopVersion != "5") )
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop7')
                                            @elseif (strpos($record->specified_by->label,"SOP5") && $record->origin == true)
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.sop5-legacy')
                                            @elseif (strpos($record->specified_by->label,"SOP5"))
                                                @include('gene-validity.partial.report-heading')
                                                @include('gene-validity.partial.rich-sop5')
                                            @elseif (strpos($record->specified_by->label,"SOP4"))
                                                @include('gene-validity.partial.sop4-legacy')
                                            @else
                                                ERROR - NO SOP SET
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt2">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="pull-right"><img src="/images/beta.png" height="60"></div>
                    <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab2-1" data-toggle="tab"><i class="fas {{ empty($extrecord->caselevel) ?  'fa-times' : 'fa-check-circle' }} mr-2"></i>Case Level Variants
                                    @if ($ge_count !== null)
                                        <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ number_format(min($ge_count ?? 0, 12.00), 2) }}</span>
                                    @endif
                                </a></li>
                                @if ($clfs)
                                <li class="ml-2 "><a href="#tab2-2" data-toggle="tab"><i class="fas fa-check-circle mr-2"></i>Case Level Segregation
                                    @if ($cls_pt_count !== null)
                                        <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ number_format($cls_pt_count ?? 0, 2) }}</span>
                                    @endif
                                </a></li>
                                @else
                                <li class="ml-2 disabled" data-container="body" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-title="" data-content="No Case Level Segregation evidence was provided."><a href="#tab2-2" data-toggle="notab"><i class="fas fa-times mr-2"></i>Case Level Segregation
                                </a></li>
                                @endif
                                @if ($clfswopb)
                                <li class="ml-2"><a href="#tab2-3" data-toggle="tab"><i class="fas fa-check-circle mr-2"></i>Case Level Family Segregation w/o a Scored Proband
                                    @if ($clfs_count !== null)
                                    <!--<span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ $clfs_count ?? 0 }}</span>-->
                                @endif
                                </a></li>
                                @else
                                <li class="ml-2 disabled" data-container="body" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-title="" data-content="No Case Level Family Segregation evidence was provided."><a href="#tab2-3" data-toggle="notab"><i class="fas fa-times mr-2"></i>Case Level Family Segregation w/o a Scored Proband</a></li>
                                @endif
                                @if (!empty($extrecord->casecontrol))
                                <li class="ml-2"><a href="#tab2-4" data-toggle="tab"><i class="fas fa-check-circle mr-2"></i>Case-Control
                                    @if ($cc_count !== null)
                                        <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ number_format(min($cc_count ?? 0, 12.00), 2) }}</span>
                                    @endif
                                </a></li>
                                @else
                                <li class="ml-2 disabled" data-container="body" data-toggle="popover" data-placement="bottom" data-trigger="hover" data-title="" data-content="No Case Control evidence was provided."
                                ><a href="#tab2-4" data-toggle="notab"><i class="fas fa-times mr-2"></i>Case-Control
                                    @if ($cc_count !== null)
                                        <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ number_format(min($cc_count ?? 0, 12.00), 2) }}</span>
                                    @endif
                                </a></li>
                                @endif
                            </ul>
                    </div>
                    <div class="panel-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab2-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if ($extrecord !== null)
                                                @include('gene-validity.partial.rich-ge-caselevel')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2-2">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if ($extrecord !== null)
                                                @include('gene-validity.partial.rich-ge-casesegregation')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2-3">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if ($extrecord !== null)
                                                @include('gene-validity.partial.rich-ge-casewoproband')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab2-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if ($extrecord !== null)
                                                @include('gene-validity.partial.rich-ge-casecontrol')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt4">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="pull-right"><img src="/images/beta.png" height="60"></div>
                    <div class="panel-heading">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab4-1" data-toggle="tab"><i class="fas fa-check-circle mr-2"></i>  Experimental Evidence  <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ml-1">{{ $exp_count ?? 0 }}</span></a></li>
                            </ul>
                    </div>
                    <div class="panel-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab4-1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="content-space content-border">
                                            @if ($extrecord !== null)
                                                @include('gene-validity.partial.rich-ge-expevidence')
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt6">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="pull-right"><img src="/images/beta.png" height="60"></div>
                    <div class="panel-heading" style="height:71px;">
                        <h4>Non-Scorable Evidence</h4>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="gene_nonscrorable" class="container">
                                <div class="row geneValidityScoresWrapper">
                                    <div class="col-sm-12">
                                        @forelse ($extrecord->nonscorable as $evidence)
                                        @foreach ($evidence->evidence as $source)
                                        <p>
                                            <a href="{{ $source->source->iri }}" target="_pubmed">
                                                <strong>{{ $source->source->curie ?? '' }}</strong>
                                                <i class="glyphicon glyphicon-new-window"></i>
                                            </a>
                                            @if (in_array($source->source->curie, $extrecord->eas))
                                            <span class="ml-1" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="The article is selected as earliest report of a variant in the gene causing the disease of interest in a human"><i class="fas fa-check-square text-success"></i></span>
                                            @endif
                                            <br>
                                            {{ $source->source->first_author ?? ''}}, et. al., {{ $source->source->label ?? '' }} <strong>{{ $source->source->year_published ?? ''}}</strong><br>
                                            <br>
                                            @if (empty($evidence->description))
                                            <strong>Explanation:  </strong>None
                                            @else
                                            <strong>Explanation:  </strong>@markdown{{ $evidence->description }}@endmarkdown
                                            @endif
                                        </p>
                                        <hr>
                                        @endforeach
                                        @empty
                                        No non-scorable evidence was found.
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt5">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="panel-heading" style="height:71px;">
                        <h4>Lumping & Splitting</h4>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="gene_validity_lumping" class="container">
                                <div class="row geneValidityScoresWrapper">
                                    <div class="col-sm-12">
                                        <div class="bg-white border border-2 border-warning mr-3 p-2 mt-1 mb-3 rounded">
                                            Lumping and Splitting is the process by which ClinGen curation groups determine which disease entity they will use for evaluation.
                                            Groups review current disease and/or phenotype assertions (e.g. OMIM MIM phenotypes) and select the included and excluded phenotypes according to <a href="https://www.clinicalgenome.org/working-groups/lumping-and-splitting/" target="_doc">current guidelines</a>.
                                            MIM phenotypes represented below are those that were available on the stated evaluation date
                                        </div>
                                        @if (!empty($record->las_included))
                                            <dl class="row mb-2">
                                                <dt class="col-sm-3 text-right">Included MIM Phenotypes
                                                    <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are part of the disease entity used for curation."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                    :</dt>
                                                <dd class="col-sm-9">
                                                    @foreach ($record->las_included as $mim)
                                                        <div class="mb-1">
                                                            <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] }}</a>
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
                                        @if (!empty($record->las_excluded))
                                            <dl class="row mb-2">
                                                <dt class="col-sm-3 text-right">Excluded MIM Phenotypes
                                                    <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are not part of the disease entity used for curation.  This does not mean that these are not valid assertions, and could be curated separately."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                    :</dt>
                                                <dd class="col-sm-9">
                                                    @foreach ($record->las_excluded as $mim)
                                                        <div class="mb-1">
                                                            <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] }}</a>
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
                                                @if (!empty($record->las_date))
                                                        {{ date('m/d/Y', strtotime($record->las_date)) }}
                                                @else
                                                    <i class="text-muted">No Date was specified</i>
                                                @endif
                                                </div>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Curation Type  :</dt>
                                            <dd class="col-sm-9">
                                                <div class="mb-1">
                                                @if (!empty($record->las_curation))
                                                        {{ $record->las_curation }}
                                                @else
                                                    <i class="text-muted">No curation type was specified</i>
                                                @endif
                                                <a href="https://www.clinicalgenome.org/docs/lumping-and-splitting" class="ml-2 small" target="_doc">(Read more about curation type)</a>
                                                </div>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Rationales  :</dt>
                                            <dd class="col-sm-9">
                                                <div class="mb-1">
                                                @if (!empty($record->las_rationale['rationales']))
                                                        {{ implode(', ', $record->las_rationale['rationales']) }}
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
                                                @if (!empty($record->las_rationale['pmids']))
                                                    @foreach ($record->las_rationale['pmids'] as $pmid)
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
                                                @if (!empty($record->las_rationale['notes']))
                                                            {{ $record->las_rationale['notes'] }}
                                                @else
                                                    <i class="text-muted">No Notes were specified</i>
                                                @endif
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt7">
                <div class="panel panel-primary with-nav-tabs">
                    <div class="pull-right"><img src="/images/beta.png" height="60"></div>
                    <div class="panel-heading" style="height:71px;">
                        <h4>References</h4>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div id="gene_nonscrorable" class="container">
                                <div class="row geneValidityScoresWrapper">
                                    <div class="col-sm-12">
                                        @foreach ($extrecord->pmids as $pmid)
                                        <p>{!! displayCitation($pmid, true) !!}</p>
                                        <hr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div id="gene_validity_show" class="container">
    <div class="row geneValidityScoresWrapper">
        <div class="col-sm-12">
            <div class="content-space content-border">
                @if($record->json_message_version == "GCI.8.1" || strpos($record->specified_by->label,"SOP9"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop8-1')
                @elseif(strpos($record->specified_by->label,"SOP8"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop7')
                @elseif(strpos($record->specified_by->label,"SOP7"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop7')
                @elseif (strpos($record->specified_by->label,"SOP6"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop6')
                @elseif (strpos($record->specified_by->label,"SOP5") && (isset($record->json->sopVersion) && $record->json->sopVersion != "5") )
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop7')
                @elseif (strpos($record->specified_by->label,"SOP5") && $record->origin == true)
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop5-legacy')
                @elseif (strpos($record->specified_by->label,"SOP5"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop5')
                @elseif (strpos($record->specified_by->label,"SOP4"))
                    @include('gene-validity.partial.report-heading')
                    @include('gene-validity.partial.sop4-legacy')
                @else
                    ERROR - NO SOP SET
                @endif

                {{-- @if (!empty($score_string))
                    @if ($assertion->jsonMessageVersion == "GCI.6")
                        @include('validity.gci6')
                    @else
                        @include('validity.gci')
                    @endif
                @elseif (!empty($score_string_sop5))
                    @include('validity.sop5')
                @else
                    @include('validity.sop4')
                @endif --}}
            </div>
        </div>
    </div>
</div>
@endif
</section>
@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('modals')

@include('modals.beta')

@endsection

@section('script_css')
<style>
.panel-tabs {
    position: relative;
    bottom: 30px;
    clear:both;
    border-bottom: 1px solid transparent;
}

.panel-tabs > li {
    float: left;
    margin-bottom: -1px;
}

.panel-tabs > li > a {
    margin-right: 2px;
    margin-top: 4px;
    line-height: .85;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    color: #ffffff;
}

.panel-tabs > li > a:hover {
    border-color: transparent;
    color: #ffffff;
    background-color: transparent;
}

.panel-tabs > li.active > a,
.panel-tabs > li.active > a:hover,
.panel-tabs > li.active > a:focus {
    color: #fff;
    cursor: default;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    background-color: rgba(255,255,255, .23);
    border-bottom-color: transparent;
}
/*
.wrapper1 {width: 100%; border: none 0px RED;
overflow-x: scroll; overflow-y:hidden;}
.wrapper1{height: 20px; }
.div1 {width:1000px; height: 20px; }
*/
</style>
    <!-- link href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css" rel="stylesheet" -->
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
    <link rel="stylesheet" href="/css/bootstrap-table-sticky-header.css">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<!-- script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script -->

<script src="/js/bootstrap-table.min.js"></script>

<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>


<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>

<script src="/js/bootstrap-table-multiple-sort.min.js"></script>

<!-- script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script -->


<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>
<script src="/js/bookmark.js"></script>
<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>


<script>

  /**
	**
	**		Globals
	**
	*/

  var $table = $('#table');
  var bookmarksonly = false;
  window.scrid = {{ $display_tabs['scrid'] }};
  window.token = "{{ csrf_token() }}";

  window.ajaxOptions = {
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

  function responseHandler(res) {

    $('.countCurations').html(res.total);
    $('.countGenes').html(res.ngenes);
    $('.countEps').html(res.npanels);

    return res
  }


  function inittable() {
    $('#geclv').bootstrapTable();
    $('#gecls').bootstrapTable();
    $('#geclfs').bootstrapTable();
    $('#gecc').bootstrapTable();
    $table.bootstrapTable();


    $table.on('load-error.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");
      swal({
            title: "Load Error",
            text: "The system could not retrieve data from GeneGraph",
            icon: "error"
      });
	  })

    $table.on('load-success.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");
      window.update_addr();

      if (name.hasOwnProperty('error'))
      {
        swal({
            title: "Load Error",
            text: name.error,
            icon: "error"
        });
      }
    })

    $table.on('post-body.bs.table', function (e, name, args) {

			$('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
		})

    $('#geclv').on('post-body.bs.table', function (e, name, args) {

        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

    });

    $('#gecls').on('post-body.bs.table', function (e, name, args) {

        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    })

    $('#geclfs').on('post-body.bs.table', function (e, name, args) {

        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    })

    $('#gecc').on('post-body.bs.table', function (e, name, args) {

        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    })
  }

  function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find('i.fas')
        .toggleClass('fa-compress-arrows-alt fa-expand-arrows-alt');
}

$(function() {

  // Set cursor to busy prior to table init
  //$("body").css("cursor", "progress");

  // initialize the table and load the data
  inittable();

  // make some mods to the search input field
  var search = $('.fixed-table-toolbar .search input');
  search.attr('placeholder', 'Search in table');

  $( ".fixed-table-toolbar" ).show();
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

  $("button[name='filterControlSwitch']").attr('title', 'Column Search');
	$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

  $('#tag_genetic_evidence_case_level_with_proband').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_segregation').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_case_level_without_proband').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_case_control').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_experimental_evidence').on('hide.bs.collapse show.bs.collapse', toggleChevron);

  $('.action-read-more').on('click', function() {

    var text = $(".read-more-text").html();
     if (text == "Read more...")
        $(".read-more-text").html("Read less...");
     else
        $(".read-more-text").html("Read more...");

  });

 /* $(".wrapper1").scroll(function(){
        $("#geclv")
            .scrollLeft($(".wrapper1").scrollLeft());
    });

    $("#geclv").scroll(function(){
        alert("a");
        $(".wrapper1")
            .scrollLeft($("#geclv").scrollLeft());
    }); */

    $('.action-beta-form').on('click', function(){
        $('#beta-form')[0].reset();
        $('#modalBeta').modal('show');
    });

    $( '#beta-form' ).validate( {
        submitHandler: function(form) {
            $.ajaxSetup({
                cache: true,
                contentType: "application/x-www-form-urlencoded",
                processData: true,
                headers:{
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN' : window.token
                }
            });

            var url = "/kb/gene-validity/feedback";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {

                    $('#modalBeta').modal('hide');
                    swal("Feedback Sent", "", "success");

            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while submitting the email.  Please try again later.",
                    icon: "error",
                });
            });

            $('#modalBeta').modal('hide');
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
