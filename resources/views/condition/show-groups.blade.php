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
					<li class="" style="">
            <a href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}" class="">
              <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
          </li>
          <li class="active" style="">
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
		<div class="col-md-12 mt-3">
            <p>Other ClinGen expert panels and/or other curation working groups may be in the process of evaluating <strong><i>{{  $disease->label }}</i></strong> in addition to the completed curations available on the “Curation Summaries” tab.  See below for a listing of these other groups and an indication of their evaluation status. Evaluation statuses include:<p>
                <div class="row mb-2">
                    <div class="col-md-offset-1 col-md-2 under-review-color text-center p-1 img-rounded">
                        <span class=""><strong>Under review</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group is actively evaluating the disease.  Once this evaluation is complete, it will appear on the “Curation Summaries” tab.
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-offset-1 col-md-2 precuration-color text-center p-1 img-rounded">
                        <span class=""><strong>Precuration</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group is considering which genes(s) they will evaluate as part of the curation.  This is often the first step in the curation process.
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-offset-1 col-md-2 in-scope-color text-center p-1 img-rounded">
                        <span class=""><strong>In Scope</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group has indicated that this disease is of potential interest for future evaluation, but active curation has not yet begun.
                    </div>
                </div>
            <p class="mb-5"><a href='https://clinicalgenome.org/affiliation/'>Click here to learn more about all the ClinGen Expert Panels and Working Groups.</a>
            </p>

            {{-- @foreach($gceps as $gcep)
                @include('gene.includes.ep')
            @endforeach

            @include('gene.includes.dswg')

            @include('gene.includes.cawg')

            @foreach($vceps as $vcep)
                @include('gene.includes.vcep')
            @endforeach --}}

            @foreach($pregceps as $gcep)
                @include('condition.includes.pregcep')
            @endforeach

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
