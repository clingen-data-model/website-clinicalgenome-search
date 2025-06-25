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
					<li class="" style="">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="">
              <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
          </li>
          <li class="active" style="">
            <a href="{{ route('gene-groups', $record->hgnc_id) }}" class="">Status and Future Work <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $total_panels }}</span></a>
          </li>
          @if ($gc !== null && $gc->variant_count > 0)
		<li class="" style="">
			<a href="{{ route('gene-genomeconnect', $record->hgnc_id) }}" class="">GenomeConnect <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $gc->variant_count }}</span></a>
		</li>
		@endif
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
		<div class="col-md-12 mt-3">
            <p>Other ClinGen expert panels and/or other curation working groups may be in the process of evaluating <strong><i>{{  $record->label }}</i></strong> in addition to the completed curations available on the “Curation Summaries” tab.  See below for a listing of these other groups and an indication of their evaluation status. Evaluation statuses include:<p>
                <div class="row mb-2">
                    <div class="col-md-offset-1 col-md-2 under-review-color text-center p-1 img-rounded">
                        <span class=""><strong>Under review</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group is actively evaluating the gene.  Once this evaluation is complete, it will appear on the “Curation Summaries” tab.
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-offset-1 col-md-2 precuration-color text-center p-1 img-rounded">
                        <span class=""><strong>Precuration</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group is considering which disease(s) they will evaluate as part of the curation.  This is often the first step in the curation process.
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-offset-1 col-md-2 in-scope-color text-center p-1 img-rounded">
                        <span class=""><strong>In Scope</strong></span>
                    </div>
                    <div class="col-md-9">
                        The group has indicated that this gene is of potential interest for future evaluation, but active curation has not yet begun.
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
                @include('gene.includes.pregcep')
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
