@extends('layouts.app')

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
		<table class="mt-3 mb-4">
            <tr>
            <td class="valign-top"><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></td>
            <td class="pl-2">
                            <h1 class="h2 p-0 m-0">{{ displayMondoLabel($disease->label) }} {!! displayMondoObsolete($disease->label) !!}</h1>
                            <a class="btn btn-facts btn-outline-primary " role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                <i class="far fa-caret-square-down"></i> View Disease Facts
                            </a>
            </td>
            </tr>
        </table>

			</h1>
			{{-- <strong></strong> --}}

    </div>

	<div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">


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
            <a href="{{ route('condition-show', $record->getMondoString($record->iri, true)) }}">
                <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
          </li>
          <li class="" style="">
            <a href="{{ route('condition-groups', $record->getMondoString($record->iri, true)) }}" class="">Status and Future Work </a>
          </li>
          <li class="active" style="">
            <a href="{{ route('condition-external', $record->getMondoString($record->iri, true)) }}" class=" bg-primary text-white"><span class='hidden-sm hidden-xs'>External Genomic </span>Resources </a>
          </li>
          <li class="" style="">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->symbol }}%5Bgene%5D" class="" target="clinvar">ClinVar Variants  <i class="glyphicon glyphicon-new-window text-xs" id="external_clinvar_gene_variants"></i></a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="panel">
          <div id="results_curation_summary_details" class="panel-body results_curation_summary_details">





            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/medgen.png') }}" alt="Medgen">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a id="external_gene_medgen_genetics_summary" class="externalresource" title="MedGen: Genetics Summary" href="https://www.ncbi.nlm.nih.gov/medgen/?term=&quot;medgen+gtr+tests+clinical&quot;[Filter]+{{ $record->symbol }}#Additional_description" target="_blank">MedGen: Genetics Summary</a>
                  </h4>
                <div class="text-sm text-muted">
                  Organizes information related to human medical genetics, such as attributes of conditions with a genetic contribution.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_medgen_genetics_summary" class="btn btn-default btn-xs externalresource" title="MedGen: Genetics Summary" href="https://www.ncbi.nlm.nih.gov/medgen/?term=&quot;medgen+gtr+tests+clinical&quot;[Filter]+{{ $record->symbol }}#Additional_description" target="_blank">MedGen: Genetics Summary</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/genetic_practice_guidelines.png') }}" alt="Genetic practice guidelines">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a id="external_gene_genetic_practice_guidelines" class="externalresource" title="Genetic Practice Guidelines: Gene" href="https://www.ncbi.nlm.nih.gov/medgen?term=(&quot;has guideline&quot;%5BProperties%5D) AND {{ $record->symbol }}#Professional_guidelines" target="_blank">Genetic Practice Guidelines: Gene</a>
                  </h4>
                <div class="text-sm text-muted">
                  As guidelines are identified that relate to a disorder, gene, or variation, staff at NCBI connect them to the appropriate records. This page provides an alphabetical list of the professional practice guidelines, position statements, and recommendations that have been identified.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_genetic_practice_guidelines" class="btn btn-default btn-xs externalresource" title="Genetic Practice Guidelines: Gene" href="https://www.ncbi.nlm.nih.gov/medgen?term=(&quot;has guideline&quot;%5BProperties%5D) AND {{ $record->symbol }}#Professional_guidelines" target="_blank">Genetic Practice Guidelines: Gene</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/gtr.png') }}" alt="Gtr">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="GTR: Gene Tests" id="external_gene_gtr_gene_tests" href="https://www.ncbi.nlm.nih.gov/gtr/tests/?term={{ $record->symbol }}&amp;test_type=Clinical&amp;certificate=CLIA Certified" target="_blank">GTR: Gene Tests</a>
                  </h4>
                <div class="text-sm text-muted">
                  A voluntary registry of genetic tests and laboratories, with detailed information about the tests such as what is measured and analytic and clinical validity.  GTR also is a nexus for information about genetic conditions and provides context-specific links to a variety of resources, including practice guidelines, published literature, and genetic data/information. The scope of GTR includes single gene tests for Mendelian disorders, somatic/cancer tests and pharmacogenetic tests including complex arrays, panels.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_gtr_gene_tests" class="btn btn-default btn-xs externalresource" title="GTR: Gene Tests" href="https://www.ncbi.nlm.nih.gov/gtr/tests/?term={{ $record->symbol }}&amp;test_type=Clinical&amp;certificate=CLIA Certified" target="_blank">GTR: Gene Tests</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/cpic.png') }}" alt="Cpic">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="CPIC Pharmacogenomics Prescribing Guidelines" id="external_gene_cpic_pharmacogenomics_guidelines" href="https://cpicpgx.org/?s={{ $record->symbol }}" target="_blank">CPIC Pharmacogenomics Prescribing Guidelines</a>
                  </h4>
                <div class="text-sm text-muted">
                  The Clinical Pharmacogenetics Implementation Consortium (CPIC) was formed as a shared project between PharmGKB and the Pharmacogenomics Research Network (PGRN).
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_cpic_pharmacogenomics_guidelines" class="btn btn-default btn-xs externalresource" title="CPIC Pharmacogenomics Prescribing Guidelines" href="https://cpicpgx.org/?s={{ $record->symbol }}" target="_blank">CPIC Pharmacogenomics Prescribing Guidelines</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/pharmgkb.png') }}" alt="Pharmgkb">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="PharmGKB: Gene" id="external_gene_pharmgkb_gene" href="https://www.pharmgkb.org/hgnc/{{ $record->symbol }}" target="_blank">PharmGKB: Gene</a>
                  </h4>
                <div class="text-sm text-muted">
                  PharmGKB is a comprehensive resource that curates knowledge about the impact of genetic variation on drug response for clinicians and researchers.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_pharmgkb_gene" class="btn btn-default btn-xs externalresource" title="PharmGKB: Gene" href="https://www.pharmgkb.org/hgnc/{{ $record->symbol }}" target="_blank">PharmGKB: Gene</a>
                </div>
              </div>
            </div>



            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/omim.png') }}" alt="Omim">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="OMIM: Gene" id="external_gene_omim" href="http://omim.org/search?index=entry&amp;start=1&amp;limit=10&amp;search=approved_gene_symbol%3A{{ $record->symbol }}+AND+gene_id_exists&amp;sort=score+desc%2C+prefix_sort+desc" target="_blank">OMIM: Gene</a>
                  </h4>
                <div class="text-sm text-muted">
                  An Online Catalog of Human Genes and Genetic Disorders.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_omim" class="btn btn-default btn-xs externalresource" title="OMIM: Gene" href="http://omim.org/search?index=entry&amp;start=1&amp;limit=10&amp;search=approved_gene_symbol%3A{{ $record->symbol }}+AND+gene_id_exists&amp;sort=score+desc%2C+prefix_sort+desc" target="_blank">OMIM: Gene</a>
                </div>
              </div>
            </div>

            {{-- <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/genetics_home_reference.png') }}" alt="Genetics home reference">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="Genetics Home Reference" id="external_gene_genetics_home_reference" href="https://ghr.nlm.nih.gov/search?query={{ $record->symbol }}" target="_blank">Genetics Home Reference</a>
                  </h4>
                <div class="text-sm text-muted">
                  Genetics Home Reference provides consumer-friendly information about the effects of genetic variation on human health.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_genetics_home_reference" class="btn btn-default btn-xs externalresource" title="Genetics Home Reference" href="https://ghr.nlm.nih.gov/search?query={{ $record->symbol }}" target="_blank">Genetics Home Reference</a>
                </div>
              </div>
            </div> --}}

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/gene_reviews.png') }}" alt="Gene reviews">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="Gene Reviews" id="external_gene_gene_reviews" href="https://www.ncbi.nlm.nih.gov/books/NBK1116/?term={{ $record->symbol }}%5BGeneSymbol%5D" target="_blank">Gene Reviews</a>
                  </h4>
                <div class="text-sm text-muted">
                  An international point-of-care resource for busy clinicians, provides clinically relevant and medically actionable information for inherited conditions in a standardized journal-style format, covering diagnosis, management, and genetic counseling for patients and their families.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_gene_reviews" class="btn btn-default btn-xs externalresource" title="Gene Reviews" href="https://www.ncbi.nlm.nih.gov/books/NBK1116/?term={{ $record->symbol }}%5BGeneSymbol%5D" target="_blank">Gene Reviews</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/clinvar.png') }}" alt="Clinvar">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="ClinVar - Gene" id="external_gene_clinvar" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22{{ $record->symbol }}%22" target="_blank">ClinVar - Gene</a>
                  </h4>
                <div class="text-sm text-muted">
                  ClinGen and ClinVar are close partners and have established a collaborative working relationship. ClinVar is a critical resource for ClinGen. ClinVar aggregates information about genomic variation and its relationship to human health.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_clinvar" class="btn btn-default btn-xs externalresource" title="ClinVar - Gene" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22{{ $record->symbol }}%22" target="_blank">ClinVar - Gene</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/1000_genomes.png') }}" alt="1000 genomes">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="1000 Genomes" id="external_gene_1000_genomes" href="http://browser.1000genomes.org/Homo_sapiens/Search/Results?site=ensembl&amp;q=%22{{ $record->symbol }}%22" target="_blank">1000 Genomes</a>
                  </h4>
                <div class="text-sm text-muted">
                  An interactive graphical viewer that allows users to explore variant calls, genotype calls and supporting evidence (such as aligned sequence reads) that have been produced by the 1000 Genomes Project.
 View Information
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_1000_genomes" class="btn btn-default btn-xs externalresource" title="1000 Genomes" href="http://browser.1000genomes.org/Homo_sapiens/Search/Results?site=ensembl&amp;q=%22{{ $record->symbol }}%22" target="_blank">1000 Genomes</a>
                </div>
              </div>
            </div>

            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/ncbi.png') }}" alt="Ncbi">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="NCBI Browser" id="external_gene_ncbi_browser" href="https://www.ncbi.nlm.nih.gov/variation/tools/1000genomes/?q={{ $record->symbol }}%5bgene%5d" target="_blank">NCBI Browser</a>
                  </h4>
                <div class="text-sm text-muted">
                  The 1000 Genomes Browser allows users to explore variant calls, genotype calls and supporting sequence read alignments that have been produced by the 1000 Genomes project.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_gene_ncbi_browser" class="btn btn-default btn-xs externalresource" title="NCBI Browser" href="https://www.ncbi.nlm.nih.gov/variation/tools/1000genomes/?q={{ $record->symbol }}%5bgene%5d" target="_blank">NCBI Browser</a>
                </div>
              </div>
            </div>


          </div>
        </div>
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
