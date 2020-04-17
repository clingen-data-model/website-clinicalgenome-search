@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
		  <h1 class=" display-4 ">{{ $record->label }}
			</h1>
			<div class='mb-4 text-muted'>RXNORM:{{ $record->curie }}</div>
		</div>
		<div class="col-md-12">
			<ul class="nav nav-tabs">
          <li class="active">
            <a href="{{ route('drug-show', $record->curie) }}" class=" bg-primary text-white">External Genomic Resources </a>
          </li>
        </ul>


				<div class="panel panel-default">
          <div class="panel-heading" id="results_curation_summary_heading">
            <div class="row">
              <div class="col-sm-12">
                External Resources
              </div>
            </div>
          </div>
          <div id="results_curation_summary_details" class="panel-body results_curation_summary_details">


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/pharmgkb.png') }}" alt="Pharmgkb">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="PharmGKB - PGx knowledge" id="external_drug_pharmgkb_dosing_guidelines" href="https://www.pharmgkb.org/rxnorm/{{ $record->curie }}" target="_blank">PharmGKB - PGx knowledge</a>
                  </h4>
                <div class="text-sm text-muted">
                  PharmGKB is a comprehensive resource that curates knowledge about the impact of genetic variation on drug response for clinicians and researchers.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_pharmgkb_dosing_guidelines" class="btn btn-default btn-xs externalresource" title="PharmGKB - PGx knowledge" href="https://www.pharmgkb.org/rxnorm/{{ $record->curie }}" target="_blank">PharmGKB - PGx knowledge</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/cpic.png') }}" alt="Cpic">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="CPIC Pharmacogenomics Prescribing Guidelines" id="external_drug_cpic_pharmacogenomics_guidelines" href="https://cpicpgx.org/?s={{ $record->label }}" target="_blank">CPIC Pharmacogenomics Prescribing Guidelines</a>
                  </h4>
                <div class="text-sm text-muted">
                  The Clinical Pharmacogenetics Implementation Consortium (CPIC) was formed as a shared project between PharmGKB and the Pharmacogenomics Research Network (PGRN).
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_cpic_pharmacogenomics_guidelines" class="btn btn-default btn-xs externalresource" title="CPIC Pharmacogenomics Prescribing Guidelines" href="https://cpicpgx.org/?s={{ $record->label }}" target="_blank">CPIC Pharmacogenomics Prescribing Guidelines</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/gtr.png') }}" alt="Gtr">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="GTR - CLIA certified Genetic Tests" id="external_drug_gtr_clia" href="https://www.ncbi.nlm.nih.gov/gtr/tests/?term={{ $record->label }}&amp;test_type=Clinical&amp;certificate=CLIA%20Certified" target="_blank">GTR - CLIA certified Genetic Tests</a>
                  </h4>
                <div class="text-sm text-muted">
                  The Genetic Testing Registry (GTR®) provides a central location for voluntary submission of genetic test information by providers.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_gtr_clia" class="btn btn-default btn-xs externalresource" title="GTR - CLIA certified Genetic Tests" href="https://www.ncbi.nlm.nih.gov/gtr/tests/?term={{ $record->label }}&amp;test_type=Clinical&amp;certificate=CLIA%20Certified" target="_blank">GTR - CLIA certified Genetic Tests</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/medgen.png') }}" alt="Medgen">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="MedGen - Drug/Gene Summary" id="external_drug_medgen_drug_gene_summary" href="https://www.ncbi.nlm.nih.gov/medgen/?term=%22medgen+gtr+tests+clinical%22[Filter]+{{ $record->label }}#Additional_description" target="_blank">MedGen - Drug/Gene Summary</a>
                  </h4>
                <div class="text-sm text-muted">
                  Organizes information related to human medical genetics, such as attributes of conditions with a genetic contribution.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_medgen_drug_gene_summary" class="btn btn-default btn-xs externalresource" title="MedGen - Drug/Gene Summary" href="https://www.ncbi.nlm.nih.gov/medgen/?term=%22medgen+gtr+tests+clinical%22[Filter]+{{ $record->label }}#Additional_description" target="_blank">MedGen - Drug/Gene Summary</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/genetic_practice_guidelines.png') }}" alt="Genetic practice guidelines">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="Genetic Practice Guidelines - Drug/Gene Summary" id="external_drug_genetic_practice_guidelines" href="https://www.ncbi.nlm.nih.gov/medgen?term=(%22has%20guideline%22%5BProperties%5D)%20AND%20{{ $record->label }}#Professional_guidelines" target="_blank">Genetic Practice Guidelines - Drug/Gene Summary</a>
                  </h4>
                <div class="text-sm text-muted">
                  As guidelines are identified that relate to a disorder, gene, or variation, staff at NCBI connect them to the appropriate records. This page provides an alphabetical list of the professional practice guidelines, position statements, and recommendations that have been identified.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_genetic_practice_guidelines" class="btn btn-default btn-xs externalresource" title="Genetic Practice Guidelines - Drug/Gene Summary" href="https://www.ncbi.nlm.nih.gov/medgen?term=(%22has%20guideline%22%5BProperties%5D)%20AND%20{{ $record->label }}#Professional_guidelines" target="_blank">Genetic Practice Guidelines - Drug/Gene Summary</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/omim.png') }}" alt="Omim">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="OMIM - Drug/Gene Summary" id="external_drug_omim" href="http://omim.org/search?index=entry&amp;start=1&amp;limit=10&amp;search={{ $record->label }}&amp;sort=score+desc%2C+prefix_sort+desc" target="_blank">OMIM - Drug/Gene Summary</a>
                  </h4>
                <div class="text-sm text-muted">
                  An Online Catalog of Human Genes and Genetic Disorders.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_omim" class="btn btn-default btn-xs externalresource" title="OMIM - Drug/Gene Summary" href="http://omim.org/search?index=entry&amp;start=1&amp;limit=10&amp;search={{ $record->label }}&amp;sort=score+desc%2C+prefix_sort+desc" target="_blank">OMIM - Drug/Gene Summary</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/clinvar.png') }}" alt="Clinvar">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="ClinVar - Variants annotated with Drug Response" id="external_drug_clinvar_variants_with_drug_response" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22clinsig+drug+response%22%5BProperties%5D+%22{{ $record->label }}%22" target="_blank">ClinVar - Variants annotated with Drug Response</a>
                  </h4>
                <div class="text-sm text-muted">
                  ClinGen and ClinVar are close partners and have established a collaborative working relationship. ClinVar is a critical resource for ClinGen. ClinVar aggregates information about genomic variation and its relationship to human health.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_clinvar_variants_with_drug_response" class="btn btn-default btn-xs externalresource" title="ClinVar - Variants annotated with Drug Response" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22clinsig+drug+response%22%5BProperties%5D+%22{{ $record->label }}%22" target="_blank">ClinVar - Variants annotated with Drug Response</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/clinvar.png') }}" alt="Clinvar">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="ClinVar - Medication main ingredient Search" id="external_drug_clinvar_medication_ingredient" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22{{ $record->label }}%22" target="_blank">ClinVar - Medication main ingredient Search</a>
                  </h4>
                <div class="text-sm text-muted">
                  ClinGen and ClinVar are close partners and have established a collaborative working relationship. ClinVar is a critical resource for ClinGen. ClinVar aggregates information about genomic variation and its relationship to human health.
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_clinvar_medication_ingredient" class="btn btn-default btn-xs externalresource" title="ClinVar - Medication main ingredient Search" href="http://www.ncbi.nlm.nih.gov/clinvar/?term=%22{{ $record->label }}%22" target="_blank">ClinVar - Medication main ingredient Search</a>
                </div>
              </div>
            </div>


            <div class="row  padding-bottom-lg">
              <div class="col-sm-1 hidden-xs clear-left">
              <img class="margin-left-lg img img-responsive img-rounded" src="{{ asset('/external-resources/1000_genomes.png') }}" alt="1000 genomes">
              </div>
              <div class="col-sm-11 col-xs-12">
                  <h4 class="padding-none margin-top-none border-bottom">
                    <a class="externalresource" title="1000 Genomes - General Medication" id="external_drug_1000_genomes_general_medication" href="http://browser.1000genomes.org/Homo_sapiens/Search/Results?site=ensembl&amp;q=%22{{ $record->label }}%22" target="_blank">1000 Genomes - General Medication</a>
                  </h4>
                <div class="text-sm text-muted">
                  An interactive graphical viewer that allows users to explore variant calls, genotype calls and supporting evidence (such as aligned sequence reads) that have been produced by the 1000 Genomes Project.
 View Information
                </div>
                <div class="text-sm text-muted margin-top-sm margin-bottom-sm">
                  <a id="external_drug_1000_genomes_general_medication" class="btn btn-default btn-xs externalresource" title="1000 Genomes - General Medication" href="http://browser.1000genomes.org/Homo_sapiens/Search/Results?site=ensembl&amp;q=%22{{ $record->label }}%22" target="_blank">1000 Genomes - General Medication</a>
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
