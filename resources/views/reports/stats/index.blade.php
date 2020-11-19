@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
      <div class="col-md-12 curated-genes-table">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><h1 class="h2 p-0 m-0">ClinGen Summary Statitics</h1>
          </td>
        </tr>
      </table>
      <div class="small">
        <a href="#gene" class="pr-2">Gene Level <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant" class="pr-2">Variant Level <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#gene-disease-validity" class="pr-2">Gene-Disease Validity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#dosage-sensitivity" class="pr-2">Dosage Sensitivity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#clinical-actionability" class="pr-2">Clinical Actionability <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant-vathogenicity" class="pr-2">Variant Pathogenicity	<i class="fas fa-arrow-circle-down"></i></a>
        <a href="#download">DOWNLOAD <i class="fas fa-arrow-circle-down"></i></a>
      </div>
      <hr />
      </div>

      <div class="col-md-12">
        <h2 id="gene" class="text-center h1  font-weight-light">ClinGen Gene Level Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_CURATED_GENES] ?? '' }}</div>
            <div class=" lineheight-tight">Total unique genes<br /> with any curation</div>
          </div>
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_GENE_LEVEL_CURATIONS] ?? '' }}</div>
            <div class=" lineheight-tight">Total gene level<br /> curations</div>
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
                    <div class="small lineheight-tight">Total curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Genes with at least one curation</div>
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
                    <div class="small lineheight-tight">Total curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Genes with at least one curation</div>
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
                    <div class="small lineheight-tight">Total curations</div>
                  </div>
                  <div class="col-sm-6 lineheight-tight py-3 px-4 border-left-1">
                    <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}</div>
                    <div class="small lineheight-tight">Genes with at least one curation</div>
                  </div>
                </div>
              </div>
            </div>
        </div>




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

        <hr class="mt-4 pb-4" />
        <h2 id="gene-disease-validity">Gene-Disease Clinical Validity Statistics</h2>
        <p>The ClinGen Gene-Disease Clinical Validity curation process involves evaluating the strength of evidence supporting or refuting a claim that variation in a particular gene causes a particular disease.</p>
        <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }} Total Gene-Disease Validity Curations</h4>
        <div class="row mt-4 mb-4">
          <div class="col-sm-6">
            <h4>Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-3 border-0">Definitive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_definitive }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_definitive }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DEFINITIVE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Strong</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_strong }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_strong }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_STRONG] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Moderate</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_moderate }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_moderate }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_MODERATE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Limited</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_limited }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_limited }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_LIMITED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Disputed Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_disputed }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_disputed }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DISPUTED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Refuted Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_refuted }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_refuted }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_REFUTED] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Animal Model Only</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    </div>
                    <span class="ml-2">0</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0 lineheight-tight">No Known Disease relationship</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mt-2 mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_none }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_none }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 text-center">
            <div class="">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }}</div>
            <div class="">Total Gene-Disease Validity Curations</div>
          </div>
        </div>

        <div class="row  mt-4">
          <h5 class="col-sm-12">Gene Curation Expert Panels Stats</h5>

          @foreach ($metrics->values[App\Metric::KEY_EXPERT_PANELS] as $panel)
          <div class="col-sm-2 text-center">
            <div class="panel panel-default border-0">
                <div class="panel-body p-2">
                  <a href="#link-to-ep-page" class="text-dark">
                    <div class="text-size-lg lineheight-tight">{{ $panel['count'] }}</div>
                    <div class="mb-2 lineheight-tight">{{ $panel['label'] }}</div>
                  </a>
                </div>
              </div>
          </div>
          @endforeach

        </div>





        <hr class="mt-4 pb-4" />
        <h2 id="dosage-sensitivity">Dosage Sensitivity Statistics</h2>
        <p>The ClinGen Dosage Sensitivity curation process collects evidence supporting/refuting the haploinsufficiency and triplosensitivity of genes and genomic regions.</p>
        <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }} Total Dosage Sensitivity Curations</h4>
        <div class="row text-center mt-4">
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Gene With <br />DosageSensitivity Curation</div>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_REGIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Regions With <br />Dosage Sensitivity Curation</div>
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
              <tr class="">
                <td class="col-sm-3 border-0">Dosage Sensitivity Unlikely</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Autosomal Recessive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_AR] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Sufficient Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Emerging Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Little Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">No Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5>Triplosensitivity  Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-3 border-0">Dosage Sensitivity Unlikely</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class=" border-0">Autosomal Recessive</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Sufficient Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Emerging Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">Little Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0">No Evidence</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE] ?? '' }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>



        <hr class="mt-4 pb-4" />
        <h2 id="clinical-actionability">Clinical Actionability</h2>
        <p>The overarching goal of the Clinical Actionability curation process is to identify those human genes that, when significantly altered, confer a high risk of serious disease that could be prevented or mitigated </p>
        <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }} Total Clinical Actionability Reports</h4>
<div class="row text-center mt-4">
          <div class="col-sm-3">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_CURATIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Curations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-3">
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
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">11</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">10</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">9</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">8</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">7</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">6</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">5</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
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
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">11</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">10</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">9</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">8</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">7</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">6</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                    </div>
                    <span class="ml-2">##</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="border-0">5</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-1">
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
