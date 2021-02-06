@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
      <div class="col-md-12 curated-genes-table">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><h1 class="h2 p-0 m-0">ClinGen Summary Statitics</h1>
            <h6><em>Last updated: {{ $metrics->display_date }}</em></h6>
          </td>
        </tr>
      </table>
      <div class="small">
        {{-- <a href="#gene" class="pr-2">Gene Level <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant" class="pr-2">Variant Level <i class="fas fa-arrow-circle-down"></i></a> --}}
        <a href="#summary" class="pr-2">Curation Summary Statistics <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#gene-disease-validity" class="pr-2">Gene-Disease Validity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#dosage-sensitivity" class="pr-2">Dosage Sensitivity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#clinical-actionability" class="pr-2">Clinical Actionability <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant-vathogenicity" class="pr-2">Variant Pathogenicity	<i class="fas fa-arrow-circle-down"></i></a>
        {{-- <a href="#download">DOWNLOAD <i class="fas fa-arrow-circle-down"></i></a> --}}
      </div>
      <hr />
      </div>

      <div class="col-md-12">
        <h2 id="summary" class="text-center h1  font-weight-light">ClinGen Curation Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_CURATED_GENES] ?? '' }}</div>
            <div class=" lineheight-tight">Unique genes  with<br /> at least one curation</div>
          </div>
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNIQUE] ?? '' }}</div>
            <div class=" lineheight-tight">Unique variants  with<br /> at least one curation</div>
          </div>
        </div>
        <div class="row text-center mt-4">
            <div class="col-md-3 col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#gene-disease-validity" class="pr-2 text-dark">
                    <div class="">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1142/untitled-1_icon-gene-interface_color.600x600.png" width="50px" />
                    </div>
                    <strong>Gene-Disease Validity</strong>
                    {{-- <i class="fas fa-arrow-circle-down"></i> --}}
                  </a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-xs-6 lineheight-tight py-3 px-2">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }}</div>
                    {{-- <div class="small lineheight-tight">Total number of curations</div> --}}
                    <div class="small lineheight-tight">Total reports <div class="text-10px">(Number of curations<br /> for this activity)</div></div>

                  </div>
                  <div class="col-xs-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Unique genes <div class="text-10px">(Total genes with at<br /> least one curation)</div></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#dosage-sensitivity" class="pr-2 text-dark">
                    <div class="">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1145/untitled-1_icon-dosage-interface_color.600x600.png" width="50px" />
                    </div>
                    <strong>Dosage Sensitivity</strong>
                    {{-- <i class="fas fa-arrow-circle-down"></i> --}}
                  </a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-xs-6 lineheight-tight py-3 px-2">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }}</div>
                    <div class="small lineheight-tight">Total reports <div class="text-10px">(Number of curations<br /> for this activity)</div></div>
                  </div>
                  <div class="col-xs-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Unique genes <div class="text-10px">(Total genes with at<br /> least one curation)</div></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#clinical-actionability" class="pr-2 text-dark">
                    <div class="">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1144/untitled-1_icon-actionability-interface_color.600x600.png" width="50px" />
                    </div>
                    <strong>Clinical Actionability</strong>
                    {{-- <i class="fas fa-arrow-circle-down"></i> --}}
                  </a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-xs-6 lineheight-tight py-3 px-2">
                    <div class="text-size-md lineheight-tight">
                     {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }}
                    </div>
                    <div class="small lineheight-tight">Total reports <div class="text-10px">(Number of reports<br /> for this activity)</div></div>
                  </div>
                  <div class="col-xs-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">
                      {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}
                    </div>
                    <div class="small lineheight-tight">Unique genes <div class="text-10px">(Total genes with at<br /> least one report)</div></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#dosage-sensitivity" class="pr-2 text-dark ">
                    <div class="">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1143/untitled-1_icon-variant-interface_color.600x600.png" width="50px" />
                    </div>
                    <strong>Variant Pathogenicity</strong>
                    {{-- <i class="fas fa-arrow-circle-down"></i> --}}
                  </a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-xs-6 lineheight-tight py-3 px-2">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_CURATIONS] ?? '' }}</div>
                    <div class="small lineheight-tight">Total reports <div class="text-10px">(Number of curations<br /> for this activity)</div></div>
                  </div>
                  <div class="col-xs-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNIQUE] ?? '' }}</div>
                    <div class="small lineheight-tight">Unique variants <div class="text-10px">(Total variants with at<br /> least one curation)</div></div>
                  </div>
                </div>
              </div>
            </div>
        </div>




<!--

      <div class="col-md-12">
        <h2 id="gene" class="text-center h1  font-weight-light">ClinGen Gene Curation Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_GENE_LEVEL_CURATIONS] ?? '' }}</div>
            <div class=" lineheight-tight">Total gene<br /> curations</div>
          </div>
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_CURATED_GENES] ?? '' }}</div>
            <div class=" lineheight-tight">Total unique genes<br /> with at least one curation</div>
          </div>
        </div>
        <div class="row text-center mt-4">
            <div class="col-sm-4">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#gene-disease-validity" class="pr-2 text-dark">Gene-Disease Validity <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-6 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }}</div>
                    <div class="small lineheight-tight">Total number of curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Total unique genes with at least one curation</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#dosage-sensitivity" class="pr-2 text-dark">Dosage Sensitivity <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-6 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }}</div>
                    <div class="small lineheight-tight">Total number of  curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Total unique genes with at least one curation</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#clinical-actionability" class="pr-2 text-dark">Clinical Actionability <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-6 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }}</div>
                    <div class="small lineheight-tight">Total number of  curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Total unique genes with at least one curation</div>
                  </div>
                </div>
              </div>
            </div>
        </div>



        <div class="col-md-12">
          <hr />
        <h2 id="gene" class="text-center h1  font-weight-light">ClinGen Variant Curation Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">XXX</div>
            <div class=" lineheight-tight">Total variant<br /> curations</div>
          </div>
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">XXX</div>
            <div class=" lineheight-tight">Total unique variants<br /> with at least one curation</div>
          </div>
        </div>
        <div class="row text-center mt-4">
            <div class="col-sm-4 col-sm-offset-2">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#gene-disease-validity" class="pr-2 text-dark">Copy Number Variants (CNVs) <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-6 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">XXXX</div>
                    <div class="small lineheight-tight">Total number of curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">XXX</div>
                    <div class="small lineheight-tight">Total unique variants with at least curation</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#dosage-sensitivity" class="pr-2 text-dark">Variant Pathogenicity <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-6 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">XXX</div>
                    <div class="small lineheight-tight">Total number of  curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-2 border-left-1">
                    <div class="text-size-md">XXX</div>
                    <div class="small lineheight-tight">Total unique variants  with at least curation</div>
                  </div>
                </div>
              </div>
            </div>
        </div>

      -->


<!--
        <hr class="mt-4 pb-4" />
        <h2 id="variant" class="h1 text-center font-weight-light">ClinGen Variant Level Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">XXXX</div>
            <div class=" lineheight-tight">Total unique variants<br /> with any curation</div>
          </div>
          {{-- I think these numbers will be the same... I think... hrmmm --}}
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">XXXX</div>
            <div class=" lineheight-tight">Total variant level<br /> curations</div>
          </div>
        </div>
        <div class="row text-center mt-4">
            <div class="col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#gene-disease-validity" class="pr-2 text-dark">CNVs <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-4 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">XXXX</div>
                    <div class="small lineheight-tight">Total curations</div>
                  </div>
                  <div class="col-sm-4 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">XXXX</div>
                    <div class="small lineheight-tight">CNVs with at least one variant curation</div>
                  </div>
                  <div class="col-sm-4 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">XXXX</div>
                    <div class="small lineheight-tight">CNVs with at least one gene curation</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#variant-pathogenicity" class="pr-2 text-dark">Variant Pathogenicity <i class="fas fa-arrow-circle-down"></i></a>
                </div>
                <div class="panel-body row px-2 py-0">
                  <div class="col-sm-4 lineheight-tight py-3 px-4">
                    <div class="text-size-md lineheight-tight">XXXX</div>
                    <div class="small lineheight-tight">Total curations</div>
                  </div>
                  <div class="col-sm-4 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">XXXX</div>
                    <div class="small lineheight-tight">Variants with at least one variant curation</div>
                  </div>
                  <div class="col-sm-4 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">XXXX</div>
                    <div class="small lineheight-tight">Variants with at least one gene curation</div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      -->

      <div id="gene-disease-validity-wrapper" class="">
        <hr class="mt-4 pb-4" />
        <h2 id="gene-disease-validity"><img src="https://www.clinicalgenome.org/site/assets/files/1142/untitled-1_icon-gene-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  /> Gene-Disease Clinical Validity Statistics</h2>
        <p>The ClinGen Gene-Disease Clinical Validity curation process involves evaluating the strength of evidence supporting or refuting a claim that variation in a particular gene causes a particular disease.</p>
        {{-- <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }} Total Gene-Disease Validity Curations</h4> --}}
        <div class="row mt-4 mb-4">
          <div class="col-sm-5">
            <h4>Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-3 border-0">Definitive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_definitive }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_definitive *1.5 }}%; background-color: #276749">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DEFINITIVE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Strong</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar"role="progressbar" aria-valuenow="{{ $metrics->validity_percent_strong }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_strong *1.5 }}%; background-color: #38a169">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_STRONG] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Moderate</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_moderate }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_moderate *1.5 }}%; background-color: #68d391">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_MODERATE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Limited</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_limited }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_limited *1.5 }}%; background-color: #fc8181">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_LIMITED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Disputed Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_disputed }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_disputed *1.5 }}%; background-color: #e53e3e">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DISPUTED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Refuted Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    {{-- <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_refuted }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_refuted *1.5 }}%;"> --}}
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_refuted }}" aria-valuemin="0" aria-valuemax="100" style="width: 1%; background-color: #9b2c2c">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_REFUTED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Animal Model Only</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; background-color: #276749">
                    </div>
                    <span class="ml-2">0</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0 lineheight-tight">No Known Disease relationship</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mt-2 mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_none }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_none *1.5 }}%; background-color: #718096">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-7 text-center">
            <div class="row">
              <div class="col-sm-6">
                <div style="height:300px; width:300px; margin-left:auto; margin-right:auto; background-image:url('/images/sample-chart.png'); background-size: cover;">
                  <div class="text-size-lg lineheight-tight" style="padding-top: 90px">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }}</div>
                  <div class="">Total Gene-Disease<br>Validity Curations</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div style="height:300px; width:300px; margin-left:auto; margin-right:auto; background-image:url('/images/sample-chart-solid.png'); background-size: cover;">
                  <div class="text-size-lg lineheight-tight" style="padding-top: 90px">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }}</div>
                  <div class="">Total Unique <br>Genes Curated</div>
                </div>
              </div>
            </div>

        </div>

        <div class="row  mt-4">
          <h4 class="col-sm-12">{{ count($metrics->values[App\Metric::KEY_EXPERT_PANELS]) }} ClinGen Gene Curation Expert Panels</h4>

          @php
            $i=1;
            $array = collect($metrics->values[App\Metric::KEY_EXPERT_PANELS])->sortBy('label')->toArray();
          @endphp
          @foreach ($array as $key => $panel)
          @php
            $i++;
            $num = 1;
          @endphp

          @if($i <= 9)
            <div class="col-sm-3 text-center">
              <div class="panel panel-default border-0">
                  <div class="panel-body">
                    <a href="#link-to-ep-page" class="text-dark">
                      {{-- <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                      </div> --}}
                      @if($num == 1)
                      <svg width="50%" height="50%" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Definitive" fill="transparent" stroke="#276749" stroke-width="3" stroke-dasharray="51.7 48.3" stroke-dashoffset="0"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Strong" fill="transparent" stroke="#38a169" stroke-width="3" stroke-dasharray="4.55 95.45" stroke-dashoffset="-51.7"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Moderate" fill="transparent" stroke="#68d391" stroke-width="3" stroke-dasharray="13.64 86.36" stroke-dashoffset="-56.25"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Limited" fill="transparent" stroke="#fc8181" stroke-width="3" stroke-dasharray="21.02 78.98" stroke-dashoffset="-69.89"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Disputed Evidence" fill="transparent" stroke="#e53e3e" stroke-width="3" stroke-dasharray="6.82 93.18" stroke-dashoffset="-90.91"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="Refuted Evidence" fill="transparent" stroke="#9b2c2c" stroke-width="3" stroke-dasharray="2.27 97.73" stroke-dashoffset="-97.73"/>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-type="No Known Disease Relationship" fill="transparent" stroke="#718096" stroke-width="3" stroke-dasharray="0 100" stroke-dashoffset="-100"/>
                        <!-- unused 10% -->
                        <g class="chart-text">
                          <text x="50%" y="50%" class="chart-number">
                            {{ $panel['count'] }}
                          </text>
                          <text x="50%" y="50%" class="chart-label">
                            Curations
                          </text>
                        </g>
                      </svg>
                      @endif
                      @if($num == 2)
                      <svg width="50%" height="50%" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#276749" stroke-width="3"></circle>

                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#718096" stroke-width="3" stroke-dasharray="40 60" stroke-dashoffset="25"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#fc8181" stroke-width="3" stroke-dasharray="20 80" stroke-dashoffset="85"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#68d391" stroke-width="3" stroke-dasharray="30 70" stroke-dashoffset="65"></circle>
                        <!-- unused 10% -->
                        <g class="chart-text">
                          <text x="50%" y="50%" class="chart-number">
                            {{ $panel['count'] }}
                          </text>
                          <text x="50%" y="50%" class="chart-label">
                            Curations
                          </text>
                        </g>
                      </svg>
                      @endif
                      @if($num == 3)
                      <svg width="50%" height="50%" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="#fff"></circle>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#fc8181" stroke-width="3"></circle>

                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#68d391" stroke-width="3" stroke-dasharray="40 60" stroke-dashoffset="25"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#276749" stroke-width="3" stroke-dasharray="20 80" stroke-dashoffset="85"></circle>
                        <circle class="donut-segment" cx="21" cy="21" r="15.91549430918954" fill="transparent" stroke="#718096" stroke-width="3" stroke-dasharray="30 70" stroke-dashoffset="65"></circle>
                        <!-- unused 10% -->
                        <g class="chart-text">
                          <text x="50%" y="50%" class="chart-number">
                            {{ $panel['count'] }}
                          </text>
                          <text x="50%" y="50%" class="chart-label">
                            Curations
                          </text>
                        </g>
                      </svg>
                      @endif
                      <div class="mb-2 lineheight-tight">{{ $panel['label'] }}</div>



                    </a>
                  </div>
                </div>
             </div>

          @if ($i % 4 == 1)
            <br clear="all" />
          @endif
          @endif
          @endforeach
          <div class="text-center mb-4">
            <a class="btn btn-default btn-lg btn-primary" href="#" role="button">Load more Gene Curation Expert Panels</a>
          </div>
        </div>
      </div>




        <hr class="mt-4 pb-4" />
        <h2 id="dosage-sensitivity">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1145/untitled-1_icon-dosage-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  />  Dosage Sensitivity Statistics</h2>
        <p>The ClinGen Dosage Sensitivity curation process collects evidence supporting/refuting the haploinsufficiency and triplosensitivity of genes and genomic regions.</p>
        <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }} Total Dosage Sensitivity Curations</h4>
        <div class="row text-center mt-4">
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total <br />Dosage Sensitivity Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Single Gene <br />Dosage Sensitivity Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_REGIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Region <br />Dosage Sensitivity Curations</div>
                </div>
              </div>
          </div>
          <!--
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Something Else <br />Dosage Sensitivity Curation</div>
                </div>
              </div>
          </div> -->
        </div>
        <div class="row mt-2">
          <div class="col-sm-6">
            <h5>Haploinsufficiency Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr>
                <td class="col-sm-4 border-0">Sufficient Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}%; background-color:#990000; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Emerging Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}%; background-color:#990000; opacity:.8">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Little Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}%; background-color:#990000; opacity:.6">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">No Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}%; background-color:#990000; opacity:.5">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Autosomal Recessive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}%; background-color:#990000; opacity:0.4">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_AR] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-3 border-0">Dosage Sensitivity Unlikely</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}%; background-color:#990000; opacity:.3">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5>Triplosensitivity  Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr>
                <td class="col-sm-4 border-0">Sufficient Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}%; background-color:#003366; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Emerging Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}%; background-color:#003366; opacity:.8">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Little Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}%; background-color:#003366; opacity:.6">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">No Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}%; background-color:#66ccff; opacity:.5">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Autosomal Recessive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}%; background-color:#003366; opacity:.4">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-3 border-0">Dosage Sensitivity Unlikely</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}%; background-color:#003366; opacity:.3">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>



        <hr class="mt-4 pb-4" />
        <h2 id="clinical-actionability">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1144/untitled-1_icon-actionability-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  />  Clinical Actionability</h2>
        <p>The overarching goal of the Clinical Actionability curation process is to identify those human genes that, when significantly altered, confer a high risk of serious disease that could be prevented or mitigated </p>
        <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }} Total Clinical Actionability Reports</h4>
<div class="row text-center mt-4">
          <div class="col-sm-2 px-1" style="width:20%">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Reports<br />&nbsp;</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2 px-1" style="width:20%">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">XXX
                    {{-- {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }} --}}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Updated Reports<br />&nbsp;</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2 px-1" style="width:20%">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Genes Included in <br>Actionability Reports<br />&nbsp;</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2 px-1" style="width:20%">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">XXX
                    {{-- {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }} --}}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Unique <br>Gene-Disease<br> Pairs</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2 px-1" style="width:20%">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">XXX
                    {{-- {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }} --}}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Outcome-Intervention<br> Pairs<br />&nbsp;</div>
                </div>
              </div>
          </div>
          {{-- <div class="col-sm-2 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">XXX
                  </div>
                  <div class="mb-2 lineheight-tight">Total Failed <br>Early Rule-out<br>&nbsp;</div>
                </div>
              </div>
          </div> --}}
          {{-- <div class="col-sm-3">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXX
                  </div>
                  <div class="mb-2 lineheight-tight">Total Adult Outcome-Intervention Pairs</div>
                </div>
              </div>
          </div> --}}
          {{-- <div class="col-sm-3">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Unique Gene-Disease Pairs in Actionability Reports</div>
                </div>
              </div>
          </div>
          <div class="col-sm-3">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_CURATIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Adult Outcome-intervention pairs scored</div>
                </div>
              </div>
          </div>
          <div class="col-sm-3">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_CURATIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Pediatric Outcome-intervention </div>
                </div>
              </div>
          </div> --}}
        </div>

        <div class="row mt-2">
          <div class="col-sm-6">
            <h5 class="mb-0">Adult Context </h4>

              <p>XXX Total Adult Outcome-Intervention Pairs</p>

              <p><strong>Total Scores Visualized</strong></p>

            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>12 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 60%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>11 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>10 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 80%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>9 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 90%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>8 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#55b2e3; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>7 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#367fc2; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>6 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 60%; background-color:#69399a; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>5 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 50%; background-color:#913699; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Rule-out</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 50%; background-color:#666; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>

                  </div>Failed Early Rule-out
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5 class="mb-0">Pediatric Context </h4>

              <p>XXX Total Pediatric Outcome-Intervention Pairs</p>

              <p><strong>Total Scores Visualized</strong></p>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>12 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 60%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>11 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>10 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 80%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>9 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 90%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>8 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#55b2e3; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>7 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 70%; background-color:#367fc2; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>6 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 60%; background-color:#69399a; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>5 Score</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 50%; background-color:#913699; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Rule-out</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 50%; background-color:#666; opacity:1">
                    </div>
                    <span class="ml-2">XX</span>

                  </div>Failed Early Rule-out
                </td>
              </tr>
            </table>
          </div>
        </div>
        <!--

        <div class="row mt-2">
          <div class="col-sm-6">
            <h5>Adult Context</h5>
              <p>XXXX Total Gene-Disease + Outcome Intervention Pairs</p>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-1 border-0">12</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">11</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">10</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">9</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">8</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">7</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">6</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">5</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5>Pediatric Context</h5>
              <p>XXXX Total Gene-Disease + Outcome Intervention Pairs</p>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-1 border-0">12</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">11</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">10</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">9</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">8</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">7</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">6</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">5</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        </div>
        </div>
      -->

      <!--
        <hr class="mt-4 pb-4" />
        <h2 id="variant-pathogenicity">Variant Pathogenicity</h2>
        <p>ClinGen variant curation utilizes the 2015 American College of Medical Genetics and Genomics (ACMG) guideline for sequence variant interpretation, which provides an evidence-based framework to classify variants. The results of these analyses will be deposited in ClinVar for community access.</p>
        <h4>XXXX Total Variant Pathogenicity Curations</h4>
        <div class="row text-center mt-4">
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Variant <br />Pathogenicity Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Benign<br />Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Likely<br />Benign Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Uncertain <br />Significance Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Likely Pathogenic Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-2">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">XXXX</div>
                  <div class="mb-2 lineheight-tight">Total Pathogenic Curations</div>
                </div>
              </div>
          </div>
        </div>
      -->
{{--

        <hr class="mt-4 pb-4" />
        <h2 id="download">DOWNLOAD</h2>
        <hr /> --}}

        <div id="gene-disease-validity-wrapper" class="">
        <hr class="mt-4 pb-4">
        <h2 id="gene-disease-validity">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1143/untitled-1_icon-variant-interface_color.600x600.png" width="50px" style="margin-top:-10px; margin-left:-50px"  /> Variant Pathogenicity Statistics</h2>
        <p>ClinGen variant curation utilizes the 2015 American College of Medical Genetics and Genomics (ACMG) guideline for sequence variant interpretation, which provides an evidence-based framework to classify variants.</p>

        <div class="row mt-4 mb-4">
          <div class="col-sm-5">
            <h4>Classifications Visualized</h4>
            <table class="table table-condensed">
              <tbody><tr class="">
                <td class="col-sm-3 border-0">Pathogenic</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_pathogenic }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_pathogenic * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_PATHOGENIC] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Likely Pathogenic</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="1" aria-valuemin="{{ $metrics->pathogenicity_percent_likely }}" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_likely * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_LIKELY] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Uncertain Significance</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_uncertain }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_uncertain * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNCERTAIN] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Likely Benign</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_likely_benign }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_likely_benign * 1.5 }}%;background-color: mediumseagreen;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_LIKELYBENIGN] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Benign</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_benign }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_benign * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_BENIGN] ?? '' }}</span>
                  </div>
                </td>
              </tr>

            </tbody></table>
          </div>
          <div class="col-sm-7 text-center">
            <div class="row">
              <div class="col-sm-6">
                <div style="height:300px; width:300px; margin-left:auto; margin-right:auto; background-image:url('/images/sample-chart-v.png'); background-size: cover;">
                  <div class="text-size-lg lineheight-tight" style="padding-top: 90px">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_CURATIONS] ?? '' }}</div>
                  <div class="">Total Variant <br>Pathogenicity Curations</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div style="height:300px; width:300px; margin-left:auto; margin-right:auto; background-image:url('/images/sample-chart-solid-v.png'); background-size: cover;">
                  <div class="text-size-lg lineheight-tight" style="padding-top: 90px">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNIQUE] ?? '' }}</div>
                  <div class="">Total Unique <br>Variants Curated</div>
                </div>
              </div>
            </div>
        </div>

        <div class="row  mt-4">
          <h4 class="col-sm-12">{{ count($metrics->values[App\Metric::KEY_EXPERT_PANELS_PATHOGENICITY]) }} ClinGen Variant Curation Expert Panels</h4>

          @foreach($metrics->values[App\Metric::KEY_EXPERT_PANELS_PATHOGENICITY] as $key => $value)
                                          <div class="col-sm-3 text-center">
              <div class="panel panel-default border-0">
                  <div class="panel-body">
                    <a href="#link-to-ep-page" class="text-dark">
                      <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #8cc63f solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#61933f">{{ $value }}</span>
                      </div>
                      <div class="mb-2 lineheight-tight">{{ $key }}</div>
                    </a>
                  </div>
                </div>
             </div>
          @endforeach

                      <br clear="all">
          <div class="text-center mb-4">
            <a class="btn btn-default btn-lg btn-primary" href="#" role="button">Load more Variant Curation Expert Panels</a>
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

@section('script_css')

@endsection

@section('script_js')

@endsection
