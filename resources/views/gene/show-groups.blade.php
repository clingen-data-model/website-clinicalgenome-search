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
            <a href="{{ route('gene-groups', $record->hgnc_id) }}" class="">{{ $record->symbol }} Expert Panels &amp; Groups <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">11</span></a>
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
		<div class="col-md-12 ">
            <h2>ClinGen Expert Panels &amp; Groups Reviewing {{ $record->symbol }} </h2>
            <p>ClinGen's curations and assertions are undertaken and maintained by expert panels and groups assembled from experts around the world. The following expert panels and groups are contributing to curations and assertions related to {{ $record->symbol }}. <a href=''>Click here to learn more about our expert panels and their oversight.</a></p>
            <dl class="dl-horizontal mb-0">
              <dt class="text-muted">Gene Curation</dt>
              <dd class="mb-2">
                <a href="#Dilated" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Dilated Cardiomyopathy GCEP <i class="fas fa-arrow-circle-down small"></i></a>
                <a href="#Epilepsy" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Epilepsy GCEP <i class="fas fa-arrow-circle-down small"></i></a>
                <a href="#id" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Intellectual Disability and Autism GCEP <i class="fas fa-arrow-circle-down small"></i></a>
                <a href="#RASopathy" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">RASopathy GCEP <i class="fas fa-arrow-circle-down small"></i></a>
              </dd>
              <dt class="text-muted">Dosage Sensitivity </dt>
              <dd class="mb-2"><a href="#dosage" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Dosage Sensitivity Working Group <i class="fas fa-arrow-circle-down small"></i></a></dd>
              <dt class="text-muted">Clinical Actionability </dt>
              <dd class="mb-2"><a href="#adult" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Adult Actionability Working Group <i class="fas fa-arrow-circle-down small"></i></a><a href="#peds" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">Pediatric Actionability Working Group <i class="fas fa-arrow-circle-down small"></i></a></dd>
              <dt class="text-muted">Variant Curation</dt>
              <dd class="mb-2"><a href="#RASopathy" class="border-1 bg-white badge border-primary text-primary px-1 mr-3">RASopathy VCEP <i class="fas fa-arrow-circle-down small"></i></a></dd>
            </dl>
    </div>
  </div>
</div>
</div>
</div>
<div class="container-fluid">
	<div class="row justify-content-center">
            <hr class="border-top-3">
    <div class="mx-4">
            <h3 class='h4'>Curations &amp; Assertions Related To {{ $record->symbol }}</h3>
            <table class="table table-striped">
              <thead class="bg-dark">
                <tr>
                  <th class="text-white">Expert Panel or Group</th>
                  <th class="text-white">Gene</th>
                  <th class="text-white">Disease</th>
                  <th class="text-white">MOI</th>
                  <th class="text-white">Classification &amp; Assertions</th>
                  <th class="text-white">Last Date</th>
                  <th class="text-white">Activity</th>
                  <th class="text-white">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><a href="#">RASopathy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td>cardiofaciocutaneous syndrome</td>
                  <td>AD</td>
                  <td><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Definitive</a></td>
                  <td>1/12/2017</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span><span  class="border-1 bg-white badge border-info text-info px-1 mr-3">Recuration underway</span> </td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td>Noonan syndrome</td>
                  <td>AD</td>
                  <td><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Definitive</a></td>
                  <td>2/22/2018</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td>Noonan syndrome with multiple lentigines</td>
                  <td>AD</td>
                  <td><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Definitive</a></td>
                  <td>3/13/2019</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td>Costello syndrome</td>
                  <td>AD</td>
                  <td><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Definitive</a></td>
                  <td>3/02/2021</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">Epilepsy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td>3/02/2021</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-muted text-muted px-1 mr-3">Uploaded</span></td>
                </tr>
                <tr>
                  <td><a href="#">Dilated Cardiomyopathy GCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td>3/02/2021</td>
                  <td>Gene Curation</td>
                  <td><span  class="border-1 bg-white badge border-muted text-muted px-1 mr-3">Retired Assignment</span></a></td>
                </tr>
                <tr>
                  <td><a href="#">Dosage Sensitivity Working Group <i class="fas fa-external-link-alt"></i></td>
                  <td>BRAF</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">0 (No Evidence for Triplosensitivity)</a></td>
                  <td>3/02/2021</td>
                  <td>Dosage</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span> <span  class="border-1 bg-white badge border-info text-info px-1 mr-3">Under review</span></td>
                </tr>
                <tr>
                  <td><a href="#">Dosage Sensitivity Working Group <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">1 (Little Evidence for Haploinsufficiency)</a></td>
                  <td>3/02/2021</td>
                  <td>Dosage</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span> <span  class="border-1 bg-white badge border-info text-info px-1 mr-3">Under review</span></td>
                </tr>
                <tr>
                  <td><a href="#">Adult Actionability Working Group <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Assertion Pending</a></td>
                  <td>3/02/2021</td>
                  <td>Actionability</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">Pediatric Actionability Working Group <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Assertion Pending</a></td>
                  <td>3/02/2021</td>
                  <td>Actionability</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">Adult Actionability Working Group <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Assertion Pending</a></td>
                  <td>3/02/2021</td>
                  <td>Actionability</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">Pediatric Actionability Working Group <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">N/A</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">Assertion Pending</a></td>
                  <td>3/02/2021</td>
                  <td>Actionability</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy VCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">AD</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">20 Pathogenic variants</a></td>
                  <td>3/02/2021</td>
                  <td>Variant</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy VCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">AD</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">5 Likely Pathogenic variants</a></td>
                  <td>3/02/2021</td>
                  <td>Variant</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy VCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">AD</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">5 Uncertain Significance variants</a></td>
                  <td>3/02/2021</td>
                  <td>Variant</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy VCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">AD</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">5 Likely Benign variants</a></td>
                  <td>3/02/2021</td>
                  <td>Variant</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
                <tr>
                  <td><a href="#">RASopathy VCEP <i class="fas fa-external-link-alt"></i></a></td>
                  <td>BRAF</td>
                  <td class="text-muted">Noonan syndrome 7</td>
                  <td class="text-muted">AD</td>
                  <td class="text-muted"><a class="btn btn-default btn-block text-left mb-2 btn-classification" href="#">15 Benign variants</a></td>
                  <td>3/02/2021</td>
                  <td>Variant</td>
                  <td><span  class="border-1 bg-white badge border-success text-success px-1 mr-3">Published</span></td>
                </tr>
              </tbody>
            </table>

            </table>

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
