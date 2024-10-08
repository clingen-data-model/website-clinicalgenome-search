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

    @if ($show_clingen_comment)
		<div class="col-md-12">
			<h4 class="border-bottom-1">ClinGen Variant Classification Guidance  
				<!--<i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="ClinGen comments are comments from ClinGen."></i>-->
			</h4>
				<p>
					{{ $record->notes }}
				</p>
		</div>
		@endif

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
          <li class="" style="">
            <a href="{{ route('gene-groups', $record->hgnc_id) }}" class="">Status and Future Work <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $total_panels }}</span></a>
          </li>
          @if ($gc !== null && $gc->variant_count > 0)
		<li class="active" style="">
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
    <div class="ml-3 mt-3">
      <h3>GenomeConnect</h3>
      <p>
        GenomeConnect partners with individual patients and gene/condition specific registries to share genetic and health data with ClinVar. GenomeConnect participants have the option to connect with other participants, learn about research opportunities, and receive updates about their genetic test results.
      </p>
        <p>View the <a href="https://clinicalgenome.org/genomeconnect">GenomeConnect Page</a> for more information.</p>
    </div>
  </div>
	<div class="row justify-content-center">
        <div class="col-md-3 text-center mt-1 pt-4">
            <img src="/images/genomeconnect-logo-final.600x600.png" alt="GeneConnect" width="240" height="240">
        </div>
		<div class="col-md-9 mb-2">
            <!--<p>
                GenomeConnect is the ClinGen Patient Registry where individuals can securely share their genetic and health information with databases such as ClinVar. 
            </p>-->
            <!--<p>
              GenomeConnect partners with individual patients and gene/condition specific registries to share genetic and health data with ClinVar. GenomeConnect participants have the option to connect with other participants, learn about research opportunities, and receive updates about their genetic test results.
                View the <a href="https://clinicalgenome.org/genomeconnect">GenomeConnect Page</a> for more information.
            </p>-->
            <h4>ClinVar Submissions</h4>
            <p>
            <div class="alert alert-light blueblur mt-3" role="alert">
                <span class="font-weight-bold font-italic">There are {{ $gc->variant_count }} GenomeConnect ClinVar submission(s) for the gene {{ $record->symbol }}.
                    <i class="fas fa-angle-double-right ml-3"></i><a class="ml-1" href='https://www.ncbi.nlm.nih.gov/clinvar/?term=(("genomeconnect"%5BSubmitter%5D)+OR+"genomeconnect%2C+clingen"%5BSubmitter%5D)+AND+"{{ $record->label }}"%5BGene+Name%5D'  class="" target="clinvar">Click <u>here</u> to view</a></span>
                </div>
            </p>
            <p>
                GenomeConnect submits participant genetic and health information to ClinVar as “Phenotyping Only” submissions. These submissions provide additional case-level details and do not count towards the aggregate ClinVar classification. GenomeConnect does not independently classify variants, but shares variant information as it appears on participant reports including the testing laboratory, laboratory classification, and classification date. The submissions also include health information from participant surveys. 
                These details can be viewed by clicking on the down arrow in the ‘More Information’ column of the ‘Submissions-Germline section’ for the selected ClinVar variant.  Note that GenomeConnect submissions can typically be found near the bottom of the Submissions-Germline list.
            </p>
          </div>
      </div>
      <div class="row justify-content-center">
        <div class="ml-3">
          <h4>Questions?</h4>
          <p>
            Email <b>info@genomeconnect.org</b> with questions about GenomeConnect or specific GenomeConnect ClinVar submissions.  Additional patient-provided information may be available in the registry. GenomeConnect also has the ability to re-contact participants to request more information.
          </p>
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
