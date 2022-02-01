@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
      <div class="col-md-12 curated-genes-table mt-3 mb-3">
        <h1 class="h1 p-0 m-0 float-left">ClinGen Summary Statistics</h1>
        <h6 class="h6 float-right mt-3"><strong><em>Last updated: <span class="" style="color: #7a0000">{{ $metrics->display_date_time ?? '' }}</span></em></strong></h6>

      <div class="row">
        <div class="col-sm-12 mt-4 mb-1">
          <p>The ClinGen Resource Summary Statistics provides a high-level summary of ClinGen's curation efforts relating to Gene-Disease Validity, Variant Pathogenicity, Clinical Actionability, and Dosage Sensitivity.  ClinGen will be enhancing and adding additional activities so be sure to check back often.  The statistics on this page are updated daily.  The last update was at {{ $metrics->display_date_time }}.</p>
        </div>
      </div>
      <div class="small text-left">
        {{-- <a href="#gene" class="pr-2">Gene Level <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant" class="pr-2">Variant Level <i class="fas fa-arrow-circle-down"></i></a> --}}
        <a href="#summary" class="pr-2">Curation Summary Statistics <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#gene-disease-validity" class="pr-2">Gene-Disease Validity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#dosage-sensitivity" class="pr-2">Dosage Sensitivity <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#clinical-actionability" class="pr-2">Clinical Actionability <i class="fas fa-arrow-circle-down"></i></a>
        <a href="#variant-pathogenicity" class="pr-2">Variant Pathogenicity	<i class="fas fa-arrow-circle-down"></i></a>
        <a href="#pharmacogenomics" class="pr-2">Pharmacogenomics	<i class="fas fa-arrow-circle-down"></i></a>
        {{-- <a href="#download">DOWNLOAD <i class="fas fa-arrow-circle-down"></i></a> --}}
      </div>
      <hr />
      </div>
      </div>

      <div class="col-md-12">
        <h2 id="summary" class="text-center h1  font-weight-light">ClinGen Curation Summary Statistics</h2>
        <div class="row text-center">
          <div class="col-sm-4 col-sm-offset-2">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_CURATED_GENES] ?? '' }}</div>
            <div class=" lineheight-tight">Unique genes  with<br /> at least one curation
              <span data-toggle="tooltip" data-placement="top" title="Includes only the genes curated by the activities listed below." aria-describedby="tooltip"><i class="fas fa-info-circle text-muted"></i></span>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNIQUE] ?? '' }}</div>
            <div class=" lineheight-tight">Unique variants  with<br /> at least one curation</div>
          </div>
        </div>

        <div class="row text-center mt-4">
            <div class="col-md-4 col-sm-8">
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
            <div class="col-md-4 col-sm-8">
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
            <div class="col-md-4 col-sm-8">
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
                     {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_REPORTS] ?? '' }}
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


            <div class="col-md-offset-2 col-md-4 col-sm-8">
              <div class="panel panel-default border-primary">
                <div class="panel-body border-bottom-1 p-2">
                  <a href="#variant-pathogenicity" class="pr-2 text-dark ">
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

          <div class="col-md-4 col-sm-8">
            <div class="panel panel-default border-primary">
              <div class="panel-body border-bottom-1 p-2">
                <a href="#pharmacogenomics" class="pr-2 text-dark">
                  <div class="">
                    <img src="https://search.clinicalgenome.org/images/Pharmacogenomics-on.png" width="50px" />
                  </div>
                  <strong>Pharmacogenomics</strong>
                  {{-- <i class="fas fa-arrow-circle-down"></i> --}}
                </a>
              </div>
              <div class="panel-body row px-2 py-0">
                <div class="col-xs-6 lineheight-tight py-3 px-2">
                  <div class="text-size-md lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_PHARMACOGENOMIICS] ?? '' }}</div>
                  {{-- <div class="small lineheight-tight">Total number of curations</div> --}}
                  <div class="small lineheight-tight">Total reports <div class="text-10px">(Number of gene-drug pairs<br />for this activity)</div></div>

                </div>
                <div class="col-xs-6 lineheight-tight py-3 px-2 border-left-1">
                  <div class="text-size-md">{{ $metrics->values[App\Metric::KEY_TOTAL_GENES_PHARMACOGENOMIICS] ?? '' }}</div>
                  <div class="small lineheight-tight">Unique genes <div class="text-10px">(Total genes with at<br />least one gene-drug pair)</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>


      <div id="gene-disease-validity-wrapper" class="">
        <hr class="mt-4 pb-4" />
        <h2 id="gene-disease-validity"><img src="https://www.clinicalgenome.org/site/assets/files/1142/untitled-1_icon-gene-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  /> Gene-Disease Clinical Validity Statistics</h2>
        <p>The ClinGen Gene-Disease Clinical Validity curation process involves evaluating the strength of evidence supporting or refuting a claim that variation in a particular gene causes a particular disease. </p>
        {{-- <h4>{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }} Total Gene-Disease Validity Curations</h4> --}}
        <div class="row mb-4">
          <div class="col-sm-8 pt-4">
            <h4 class="mb-0">Classification Statistics</h4>
            <div class="mb-3"><a href="{{ route('validity-index')}}" target="report" class="text-dark">Gene-Disease Clinical Validity has <strong>{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }} curations</strong> encompassing <strong>{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }} genes</strong>.</a></div>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-3 border-0"><a href="{{ route('validity-index') }}?col_search=classification&col_search_val=Definitive"  target="report" class="text-dark">Definitive</a></td>
                <td class="border-0">
                  <a  target="report"  href="{{ route('validity-index') }}?col_search=classification&col_search_val=Definitive" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-definitive" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_definitive }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_definitive *1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DEFINITIVE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class=" border-0"><a  target="report"  href="{{ route('validity-index') }}?col_search=classification&col_search_val=Strong" class="text-dark">Strong</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Strong" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0  chart-bg-strong"role="progressbar" aria-valuenow="{{ $metrics->validity_percent_strong }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_strong *1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_STRONG] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Moderate" class="text-dark">Moderate</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Moderate" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-moderate" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_moderate }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_moderate *1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_MODERATE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Limited" class="text-dark">Limited</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Limited" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0  chart-bg-limited" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_limited }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_limited *1.5 }}%; ">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_LIMITED] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0 lineheight-tight"><a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=No Known Disease Relationship" class="text-dark">No Known Disease Relationship</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=No Known Disease Relationship" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-no-known-disease-relationship" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_none }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_none *1.5 }}%;" >
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_NONE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Disputed" class="text-dark">Disputed Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Disputed" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-disputed-evidence" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_disputed }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->validity_percent_disputed *1.5 }}%; ">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_DISPUTED] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Refuted" class="text-dark">Refuted Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('validity-index') }}?col_search=classification&col_search_val=Refuted" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-refuted-evidence" role="progressbar" aria-valuenow="{{ $metrics->validity_percent_refuted }}" aria-valuemin="0" aria-valuemax="100" style="width: 1%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_REFUTED] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <!--<tr>
                <td class="col-sm-4 border-0">Animal Model Only</td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 chart-bg-animal-model-only " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;>
                    </div>
                    <span class="ml-2">0</span>
                  </div>
                </td>
              </tr>-->
            </table>
          </div>
          <div class="col-sm-4 text-center">
            {{-- <div class="row">
              <div class="col-sm-6 ttwrap"> --}}
                      {{-- <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                      </div> --}}

                      <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">

                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" transform="rotate(-90 21 21)" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-definitive" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-container="body"  fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['definitive evidence'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['definitive evidence'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['definitive evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['definitive evidence'] }} Definitive');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Definitive');"/>

                        <circle class="donut-segment chart-stroke-strong" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['strong evidence'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['strong evidence'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['strong evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['strong evidence'] }} Strong');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Strong');"/>

                        <circle class="donut-segment chart-stroke-moderate" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['moderate evidence'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['moderate evidence'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['moderate evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['moderate evidence'] }} Moderate');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Moderate');"/>

                        <circle class="donut-segment chart-stroke-limited " cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['limited evidence'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['limited evidence'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['limited evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['limited evidence'] }} Limited');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Limited');"/>

                        <circle class="donut-segment chart-stroke-no-known-disease-relationship" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['no known disease relationship'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['no known disease relationship'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['no known disease relationship'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['no known disease relationship'] }} No Known Disease Relationship');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=No Known Disease Relationship');"/>

                        <circle class="donut-segment chart-stroke-disputed-evidence" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['disputing'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['disputing'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['disputing'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['disputing'] }} Disputed');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Disputed');"/>

                        <circle class="donut-segment chart-stroke-refuted-evidence" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['refuting evidence'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classlength']['refuting evidence'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classoffsets']['refuting evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GRAPH]['classtotals']['refuting evidence'] }} Refuted');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('{{ route('validity-index') }}?col_search=classification&col_search_val=Refuted');"/>

                        <!-- unused 10% -->
                        <g class="chart-text chart-small">
                          <text x="50%" y="45%" class="chart-number">
                            {{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '' }}
                          </text>
                          <text x="50%" y="45%" class="chart-label">
                            Total
                          </text>
                          <text x="50%" y="52%" class="chart-label">
                            Gene-Disease
                          </text>
                          <text x="50%" y="59%" class="chart-label">
                            Curations
                          </text>
                        </g>
                      </svg>

             {{-- </div> --}}
              {{-- <div class="col-sm-6">
                <div style="height:300px; width:300px; margin-left:auto; margin-right:auto; background-image:url('/images/sample-chart-solid.png'); background-size: cover;">
                  <div class="text-size-lg lineheight-tight" style="padding-top: 90px">{{ $metrics->values[App\Metric::KEY_TOTAL_VALIDITY_GENES] ?? '' }}</div>
                  <div class="">Total Unique <br>Genes Curated</div>
                </div>
              </div> --}}
            {{-- </div> --}}

        </div>

        <div class="row  mt-4">
          <h4 class="col-sm-12">{{ count($metrics->values[App\Metric::KEY_EXPERT_PANELS]) }} ClinGen Gene Curation Expert Panels</h4>

          @php
            $i=1;
          @endphp

          @foreach (collect($metrics->values[App\Metric::KEY_EXPERT_PANELS])->sortBy('label')->toArray() as $key => $panel)

          {{-- @if(++$i <= 9) --}}
          @php
            $i++;
          @endphp
            <div class="col-sm-3 text-center">
              <div class="panel panel-default border-0">
                  <div class="panel-body">
                    {{-- <a href="https://www.clinicalgenome.org/affiliation/{{ $panel['ep_id'] }}" class="text-dark svg-link"> --}}
                    <a  target="report" href="{{ route('affiliate-show', $panel['ep_id']) }}" class="text-dark svg-link">

                      <svg width="50%" height="50%" viewBox="0 0 42 42" class="donut">

                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" transform="rotate(-90 21 21)" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-definitive" cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $panel['classlength']['definitive evidence'] }} {{ 100.00 - $panel['classlength']['definitive evidence'] }}" stroke-dashoffset="{{ $panel['classoffsets']['definitive evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['definitive evidence'] }} Definitive');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-strong" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['strong evidence'] }} {{ 100.00 - $panel['classlength']['strong evidence'] }}" stroke-dashoffset="{{ $panel['classoffsets']['strong evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['strong evidence'] }} Strong');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-moderate" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['moderate evidence'] }} {{ 100.00 - $panel['classlength']['moderate evidence'] }}" stroke-dashoffset="{{ $panel['classoffsets']['moderate evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['moderate evidence'] }} Moderate');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-limited " cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['limited evidence'] }} {{ 100.00 - $panel['classlength']['limited evidence'] }}" stroke-dashoffset="{{ $panel['classoffsets']['limited evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['limited evidence'] }} Limited');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-no-known-disease-relationship" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $panel['classlength']['no known disease relationship'] }} {{ 100.00 - $panel['classlength']['no known disease relationship'] }}" stroke-dashoffset="{{ $panel['classoffsets']['no known disease relationship'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['no known disease relationship'] }} No Known Disease Relationship');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-disputed-evidence" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['disputing'] }} {{ 100.00 - $panel['classlength']['disputing'] }}" stroke-dashoffset="{{ $panel['classoffsets']['disputing'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['disputing'] }} Disputed');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-refuted-evidence" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['refuting evidence'] }} {{ 100.00 - $panel['classlength']['refuting evidence'] }}" stroke-dashoffset="{{ $panel['classoffsets']['refuting evidence'] }}" onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['refuting evidence'] }} Refuted');" onmouseout="hideSvgTooltip();"/>
                        <g class="chart-text">
                          <text x="50%" y="50%" class="chart-number">
                            {{ $panel['count'] }}
                          </text>
                          <text x="50%" y="50%" class="chart-label">
                            Curations
                          </text>
                        </g>
                      </svg>

                      <div class="mb-2 lineheight-tight">{{ $panel['label'] }}</div>

                    </a>
                  </div>
                </div>
             </div>

          @if ($i % 4 == 1)
            <br clear="all" />
          @endif
          {{-- @endif --}}
          @if ($i == 9)
            <div class="text-center mb-4" id="collapseMoreGcepsButtonWrapper">
              <a class="btn btn-default btn-lg btn-primary" id="collapseMoreGcepsButton" role="button" data-toggle="collapse" href="#collapseMoreGceps" aria-expanded="false" aria-controls="collapseExample">Load more Gene Curation Expert Panels</a>
            </div>
            {{-- Start the GCEP collape --}}
            <div  class="collapse" id="collapseMoreGceps">
          @endif
          @endforeach

            {{-- The next DIV is the END of the GCEP Collapse --}}
            </div>
        </div>
      </div>




        <hr class="mt-4 pb-4" />
        <h2 id="dosage-sensitivity">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1145/untitled-1_icon-dosage-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  />  Dosage Sensitivity Statistics</h2>
        <p>The ClinGen Dosage Sensitivity curation process collects evidence supporting/refuting the haploinsufficiency and triplosensitivity of genes and genomic regions.</p>
        <h4><a  target="report" href="{{ route('dosage-index')}}"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }} Total Dosage Sensitivity Curations</a></h4>
        <div class="row text-center mt-4">
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <a  target="report" href="{{ route('dosage-index')}}"  class="text-dark">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Total <br />Dosage Sensitivity Curations</div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <a  target="report" href="{{ route('dosage-index')}}"  class="text-dark">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_GENES] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Single Genes <br />Evaluated</div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-4">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-2">
                  <a  target="report" href="{{ route('dosage-index')}}"  class="text-dark">
                  <div class="text-size-lg lineheight-tight">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_REGIONS] ?? '' }}</div>
                  <div class="mb-2 lineheight-tight">Genomic Regions <br />Evaluated</div>
                  </a>
                </div>
              </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-sm-6">
            <h5>Haploinsufficiency Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=3 (Sufficient Evidence)" class="text-dark">Sufficient Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=3 (Sufficient Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT) }}%; background-color:#990000; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_SUFFICIENT] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=2 (Emerging Evidence)" class="text-dark">Emerging Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=2 (Emerging Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING) }}%; background-color:#990000; opacity:.8">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_EMERGING] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=1 (Little Evidence)" class="text-dark">Little Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=1 (Little Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE) }}%; background-color:#990000; opacity:.6">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_LITTLE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=0 (No Evidence)" class="text-dark">No Evidence</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=0 (No Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE) }}%; background-color:#990000; opacity:.5">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_NONE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class=" border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=30 (Autosomal Recessive)" class="text-dark">Autosomal Recessive</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=30 (Autosomal Recessive)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_AR) }}%; background-color:#990000; opacity:0.4">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_AR] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-3 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=40 (Dosage Sensitivity Unlikely)" class="text-dark">Dosage Sensitivity Unlikely</a></td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=haplo&col_search_val=40 (Dosage Sensitivity Unlikely)" class="text-dark">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar progress-bar-left-radius-0 " role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY) }}%; background-color:#990000; opacity:.3">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_HAP_UNLIKELY] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5>Triplosensitivity  Classifications Visualized</h4>
            <table class="table table-condensed">
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=3 (Sufficient Evidence)" class="text-dark">Sufficient Evidence</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=3 (Sufficient Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT) }}%; background-color:#003366; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_SUFFICIENT] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=2 (Emerging Evidence)" class="text-dark">Emerging Evidence</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=2 (Emerging Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING) }}%; background-color:#003366; opacity:.8">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_EMERGING] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=1 (Little Evidence)" class="text-dark">Little Evidence</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=1 (Little Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE) }}%; background-color:#003366; opacity:.6">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_LITTLE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=0 (No Evidence)" class="text-dark">No Evidence</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=0 (No Evidence)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE) }}%; background-color:#66ccff; opacity:.5">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_NONE] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class=" border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=30 (Autosomal Recessive)" class="text-dark">Autosomal Recessive</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=30 (Autosomal Recessive)" class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR) }}%; background-color:#003366; opacity:.4">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_AR] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-3 border-0"><a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=40 (Dosage Sensitivity Unlikely)" class="text-dark">Dosage Sensitivity Unlikely</td>
                <td class="border-0">
                  <a  target="report" href="{{ route('dosage-index') }}?col_search=triplo&col_search_val=40 (Dosage Sensitivity Unlikely)" class="text-dark">
                  <div class="progress progress-no-bg mb-1 mt-2">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="{{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->graphDosagePercentage(App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY) }}%; background-color:#003366; opacity:.3">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_DOSAGE_TRIP_UNLIKELY] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
            </table>
          </div>
        </div>



        <hr class="mt-4 pb-2" />
        <h2 id="clinical-actionability">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1144/untitled-1_icon-actionability-interface_color.600x600.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  />  Clinical Actionability</h2>
        <p>The overarching goal of the Clinical Actionability curation process is to identify those human genes that, when significantly altered, confer a high risk of serious disease that could be prevented or mitigated.</p>
<div class="row text-center">
    <div class="col-md-7 mt-4">

          <div class="col-sm-12 text-left"><a href="https://actionability.clinicalgenome.org/ac/?col=statusOverall&search=Released"><h4><b><a  target="report" href="https://actionability.clinicalgenome.org/ac/"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_REPORTS] ?? '' }} Total Clinical Actionability Topics</a></b></h4></a></div>

          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/?searchType=all" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                     {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_COMPLETED] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Reports</div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/?col=statusStg1&search=Failed" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_FAILED] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Topics Failed <br />Early Rule-out</div>
                  </a>
                </div>
              </div>
          </div>
          {{-- <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                     {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_REPORTS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Reports</div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_UPDATED_REPORTS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Updated Reports</div>
                  </a>
                </div>
              </div>
          </div> --}}
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="/kb/actionability/report-index" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight"><a target="report" href="/kb/actionability/report-index" class="text-dark">Total Genes Included in <br>Actionability Reports</a></div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/?searchType=updated" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_UPDATED_REPORTS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Actionability <br />Updated Reports</div>
                  </a>
                </div>
              </div>
          </div>
          {{-- <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/" class="text-dark">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GD_PAIRS] }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Gene-Disease<br> Pairs</div>
                  </a>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_OUTCOME] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Outcome-Intervention<br> Pairs</div>
                </div>
              </div>
          </div>

          <div class="col-sm-4 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    2
                  </div>
                  <div class="mb-2 lineheight-tight">Curation <br /> Working Groups</div>
                </div>
              </div>
          </div> --}}
        </div>

          <div class="col-md-4">
            <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">

                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" transform="rotate(-90 21 21)" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-actionability-peds" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-container="body"  fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classlength']['Adult'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classlength']['Adult'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classoffsets']['Adult'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classtotals']['Adult'] }} Adult Context');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?searchType=allgdpairs');"/>

                        <circle class="donut-segment chart-stroke-actionability-adult" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classlength']['Ped'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classlength']['Ped'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classoffsets']['Ped'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GRAPH]['classtotals']['Ped'] }} Pediatric Context');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?searchType=allgdpairs');"/>

                        <!-- unused 10% -->
                        <g class="chart-text chart-small">
                          <text x="50%" y="45%" class="chart-number">
                            {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GD_PAIRS] ?? '' }}
                          </text>
                          <text x="50%" y="45%" class="chart-label">
                            Total
                          </text>
                          <text x="50%" y="52%" class="chart-label">
                            Gene-Disease
                          </text>
                          <text x="50%" y="59%" class="chart-label">
                            Pairs
                          </text>
                        </g>
                      </svg>
          </div>
        </div>

        <!-- New actionability section -->
        <div class="row">
            <div class="col-sm-8">
              <h4 class="mb-0"><b>{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion'] ?? 0 }} Total Actionability Assertions (by gene)</b></h4>
              <h5 class="mb-4">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_assertion_pending'] ?? 0 }} assertions under revision <span data-toggle="tooltip" data-placement="top" title="These reports were generated prior to the implementation of the process for making actionability assertions. Topics requiring assertions are currently in the queue for assertions to be added retroactively or we plan on updating the entire report soon (and will include assertions at that time)." aria-describedby="tooltip"><i class="fas fa-info-circle text-muted ml-2"></i></span></h4>

              <table class="table table-condensed ml-4" style="width: 90%">

                <tr class="">
                    <td class="col-sm-6 border-0"><strong>Definitive</strong></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_definitive'] ?? 0) }}%; background-color:#65ba59; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_definitive'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

                  <tr class="">
                    <td class="col-sm-6 border-0"><strong>Strong</strong></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_strong'] ?? 0) }}%; background-color:#469c50; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_strong'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

                  <tr class="">
                    <td class="col-sm-6 border-0"><strong>Moderate</strong></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_moderate'] ?? 0) }}%; background-color:#4fb0a8; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_moderate'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

                  <tr class="">
                    <td class="col-sm-6 border-0"><strong>Limited</strong></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_limited'] ?? 0) }}%; background-color:#55b2e3; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_limited'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

                  <tr class="">
                    <td class="col-sm-6 border-0"><strong>N/A - Insufficient evidence: expert review</strong></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_na_expert_review'] ?? 0) }}%; background-color:#69399a; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_na_expert_review'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

                  <tr class="">
                    <td class="col-sm-6 border-0"><strong>N/A - Insufficient evidence: early rule-out</strong><br></td>
                    <td class="border-0">
                      <div class="progress progress-no-bg mb-0 mt-0">
                        <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityByGenePercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_na_early_rule_out'] ?? 0) }}%; background-color:#913699; opacity:1">
                        </div>
                        <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ASSERTIONS]['total_assertion_na_early_rule_out'] ?? 0 }}</span>
                      </div>
                    </td>
                  </tr>

              </table>
            </div>
          </div>

        <div class="row mt-2">
          <div class="col-sm-6">
            <h5 class="mb-1">Adult Context </h4>

              <p class="ml-1"><a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_COMPLETED] ?? '' }} Adult Actionability Reports</a><br/>
                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_FAILED] ?? '' }} Total Topics Failed Early Rule-out</a><br/>
                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES_ADULT] ?? '' }} Unique Genes In Adult Actionability Topics</a><br/>

                <div class="row">
                    <div class="col-sm-5">
                        {{-- <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                        </div> --}}

                        <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-actionability-definitive" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_definitive'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_definitive'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_definitive'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_definitive'] }} Definitive');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=Definitive%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-strong" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_strong'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_strong'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_strong'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_strong'] }} Strong');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=Strong%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-moderate" cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_moderate'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_moderate'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_moderate'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_moderate'] }} Moderate');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=Moderate%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-limited " cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_limited'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_limited'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_limited'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_limited'] }} Limited');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=Limited%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-nareview" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_na_expert_review'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_na_expert_review'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_na_expert_review'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_na_expert_review'] }} N/A Expert Review');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=N/A%20-%20Insufficient%20evidence:%20expert%20review');"/>

                        <circle class="donut-segment chart-stroke-actionability-naruleout" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_na_early_rule_out'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classlength']['total_adult_assertion_na_early_rule_out'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classoffsets']['total_adult_assertion_na_early_rule_out'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_GRAPH]['classtotals']['total_adult_assertion_na_early_rule_out'] }} N/A Early Rule-Out');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.cliniclagenome.org/ac/Adult/ui/summ/assertion?col=consensusAssertion&search=N/A%20-%20Insufficient%20evidence:%20early%20rule-out');"/>
                        <!-- unused 10% -->
                        <g class="chart-text chart-small">
                            <text x="50%" y="45%" class="chart-number">
                            {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_ASSERTIONS]['total_adult_assertion']  - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_ASSERTIONS]['total_adult_assertion_assertion_pending']?? '' }}
                            </text>
                            <text x="50%" y="45%" class="chart-label">
                                Adult
                                </text>
                                <text x="50%" y="52%" class="chart-label">
                                Actionability
                                </text>
                                <text x="50%" y="59%" class="chart-label">
                                Assertions
                                </text>
                        </g>
                        </svg>
                    </div>
                </div>

                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ/oi?searchType=oiScored"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_OUTCOME] ?? '' }} Total Adult Outcome-Intervention Scored Pairs</a><br/>
                {{-- <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GD_PAIRS_ADULT] ?? '' }} Total Adult Gene-Disease Pairs</a> --}}
              </p>

              <!--<p><strong>Total Scores Visualized</strong></p>-->

            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=12" class="text-dark"><strong>12 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=12" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][12] ?? 0) }}%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][12] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=11" class="text-dark"><strong>11 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=11" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][11] ?? 0) }}%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][11] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=10" class="text-dark"><strong>10 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=10" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][10] ?? 0) }}%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][10] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=9" class="text-dark"><strong>9 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=9" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][9] ?? 0) }}%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][9] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=8" class="text-dark"><strong>8 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=8" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][8] ?? 0) }}%; background-color:#55b2e3; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][8] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=7" class="text-dark"><strong>7 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=7" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][7] ?? 0) }}%; background-color:#367fc2; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][7] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=6" class="text-dark"><strong>6 Score</strong></a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=6" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][6] ?? 0) }}%; background-color:#69399a; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_ADULT_SCORE][6] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=5&matchOp=%3C=" class="text-dark"><strong>5 or less</strong><br><a></td>
                <td class="border-0">
                  <a  target="report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/summ?col=overall&search=5&matchOp=%3C=" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityAdultPercentOrLess() }}%; background-color:#913699; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->actionabilityAdultOrLess() }}</span>
                  </div>
                  </a>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5 class="mb-1">Pediatric Context </h4>

              <p class="ml-1"><a  target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_COMPLETED] ?? '' }} Pediatric Actionability Reports</a><br/>
                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_FAILED] ?? '' }} Total Pediatric Topics Failed Early Rule-out</a><br/>
                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_GENES_PED] ?? '' }} Unique Genes In Pediatric Actionability Topics</a><br/>

                <div class="row">
                    <div class="col-sm-5">
                        {{-- <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                        </div> --}}

                        <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">
                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-actionability-definitive" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_definitive'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_definitive'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_definitive'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_definitive'] }} Definitive');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=Definitive%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-strong" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_strong'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_strong'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_strong'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_strong'] }} Strong');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=Strong%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-moderate" cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_moderate'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_moderate'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_moderate'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_moderate'] }} Moderate');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=Strong%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-limited " cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_limited'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_limited'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_limited'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_limited'] }} Limited');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=Limited%20Actionability');"/>

                        <circle class="donut-segment chart-stroke-actionability-nareview" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_na_expert_review'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_na_expert_review'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_na_expert_review'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_na_expert_review'] }} N/A Expert Review');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=N/A%20-%20Insufficient%20evidence:%20expert%20review');"/>

                        <circle class="donut-segment chart-stroke-actionability-naruleout" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_na_early_rule_out'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classlength']['total_peds_assertion_na_early_rule_out'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classoffsets']['total_peds_assertion_na_early_rule_out'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_GRAPH]['classtotals']['total_peds_assertion_na_early_rule_out'] }} N/A Early Rule-Out');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/assertion?col=consensusAssertion&search=N/A%20-%20Insufficient%20evidence:%20early%20rule-out');"/>
                        <!-- unused 10% -->
                        <g class="chart-text chart-small">
                            <text x="50%" y="45%" class="chart-number">
                            {{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_ASSERTIONS]['total_peds_assertion'] - $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_ASSERTIONS]['total_peds_assertion_assertion_pending'] ?? '' }}
                            </text>
                            <text x="50%" y="45%" class="chart-label">
                                Pediatric
                                </text>
                                <text x="50%" y="52%" class="chart-label">
                                Actionability
                                </text>
                                <text x="50%" y="59%" class="chart-label">
                                Assertions
                                </text>
                        </g>
                        </svg>
                    </div>
                </div>

                <a  target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ/oi?searchType=oiScored"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_OUTCOME] ?? '' }} Total Pediatric Outcome-Intervention Scored Pairs</a><br/>
                {{-- <a  target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ"  class="text-dark">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_OUTCOME] ?? '' }} Total Pediatric Outcome-Intervention Pairs</a> --}}
              </p>

              <!--<p><strong>Total Scores Visualized</strong></p>-->
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=12" class="text-dark"><strong>12 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=12" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][12] ?? 0) }}%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][12] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=11" class="text-dark"><strong>11 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=11" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][11] ?? 0) }}%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][11] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=10" class="text-dark"><strong>10 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=10" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][10] ?? 0) }}%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][10] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=9" class="text-dark"><strong>9 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=9" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][9] ?? 0) }}%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][9] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=8" class="text-dark"><strong>8 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=8" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][8] ?? 0) }}%; background-color:#55b2e3; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][8] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=7" class="text-dark"><strong>7 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=7" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][7] ?? 0) }}%; background-color:#367fc2; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][7] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=6" class="text-dark"><strong>6 Score</strong></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=6" class="text-dark">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercent($metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][6] ?? 0) }}%; background-color:#69399a; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_ACTIONABILITY_PED_SCORE][6] ?? 0 }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=5&matchOp=%3C=" class="text-dark"><strong>5 or less</strong><br></a></td>
                <td class="border-0">
                  <a target="report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/summ?col=overall&search=5&matchOp=%3C=">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->actionabilityPedPercentOrLess() }}%; background-color:#913699; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->actionabilityPedOrLess() }}</span>
                  </div>
                  </a>
                </td>
              </tr>
            </table>
          </div>
        </div>


        <div id="variant-pathogenicity" class="">
        <hr class="mt-4 pb-4">
        <h2 id="gene-disease-validity">
                      <img src="https://www.clinicalgenome.org/site/assets/files/1143/untitled-1_icon-variant-interface_color.600x600.png" width="50px" style="margin-top:-10px; margin-left:-50px"  /> Variant Pathogenicity Statistics</h2>
        <p>
          ClinGen’s Variant Curation Expert Panels (VCEPs) classify variants using ACMG/AMP sequence variant interpretation guidelines specified for the genes and/or diseases within their scope.  These specifications are reviewed and approved as part of the ClinGen VCEP application process.
        </p>

        <div class="row mb-4">
          <div class="col-sm-7 mt-5">
            <h4 class="mb-0">Classification Statistics</h4>
            <div class="mb-3">Variant Pathogenicity has <strong>{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_CURATIONS] ?? '' }} curations</strong>.</div>            <table class="table table-condensed">
              <tbody><tr class="">
                <td class="col-sm-3 border-0"><a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Pathogenic' class="text-dark">Pathogenic</a></td>
                <td class="border-0">
                  <a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Pathogenic' class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 progress-bar-danger chart-bg-pathogenic" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_pathogenic }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_pathogenic * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_PATHOGENIC] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class=" border-0"><a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Pathogenic' class="text-dark">Likely Pathogenic</a></td>
                <td class="border-0">
                  <a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Pathogenic' class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 progress-bar-warning chart-bg-likely-pathogenic"  role="progressbar" aria-valuenow="1" aria-valuemin="{{ $metrics->pathogenicity_percent_likely }}" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_likely * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_LIKELY] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Uncertain%20Significance' class="text-dark">Uncertain Significance</a></td>
                <td class="border-0">
                  <a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Uncertain%20Significance' class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-left-radius-0 progress-bar-info chart-bg-uncertain-significance" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_uncertain }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_uncertain * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_UNCERTAIN] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Benign' class="text-dark">Likely Benign</a></td>
                <td class="border-0">
                  <a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Benign' class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0 chart-bg-likely-benign" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_likely_benign }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_likely_benign * 1.5 }}%;background-color: mediumseagreen;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_LIKELYBENIGN] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="col-sm-4 border-0"><a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Benign' class="text-dark">Benign</a></td>
                <td class="border-0">
                  <a  target="report" href='https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Benign' class="text-dark">
                  <div class="progress progress-no-bg mb-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0 chart-bg-benign" role="progressbar" aria-valuenow="{{ $metrics->pathogenicity_percent_benign }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pathogenicity_percent_benign * 1.5 }}%;">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_BENIGN] ?? '' }}</span>
                  </div>
                  </a>
                </td>
              </tr>

            </tbody></table>
          </div>
          {{-- <div class="col-sm-7 text-center">
            <div class="row"> --}}
              <div class="col-sm-4">
                  {{-- <div class="text-size-lg lineheight-tight">
                    <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                  </div> --}}

                  <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">
                    <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="none"/>
                    <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                    <circle class="donut-segment chart-stroke-pathogenic" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Pathogenic'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Pathogenic'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classoffsets']['Pathogenic'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classtotals']['Pathogenic'] }} Pathogenic');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Pathogenic');"/>

                      <circle class="donut-segment chart-stroke-likely-pathogenic" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Likely Pathogenic'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Likely Pathogenic'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classoffsets']['Likely Pathogenic'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classtotals']['Likely Pathogenic'] }} Likely Pathogenic');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Pathogenic');"/>

                    <circle class="donut-segment chart-stroke-uncertain-significance" cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Uncertain Significance'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Uncertain Significance'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classoffsets']['Uncertain Significance'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classtotals']['Uncertain Significance'] }} Uncertain Significance');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Uncertain%20Significance');"/>

                    <circle class="donut-segment chart-stroke-likely-benign " cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Likely Benign'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Likely Benign'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classoffsets']['Likely Benign'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classtotals']['Likely Benign'] }} Likely Benign');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Likely%20Benign');"/>

                      <circle class="donut-segment chart-stroke-benign" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Benign'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classlength']['Benign'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classoffsets']['Benign'] }}"  onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_GRAPH]['classtotals']['Benign'] }} Benign');" onmouseout="hideSvgTooltip();" onclick="SvgTooltipLink('https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&assertion=Benign');"/>
                    <!-- unused 10% -->
                    <g class="chart-text chart-small">
                      <text x="50%" y="45%" class="chart-number">
                        {{ $metrics->values[App\Metric::KEY_TOTAL_PATHOGENICITY_CURATIONS] ?? '' }}
                      </text>
                      <text x="50%" y="45%" class="chart-label">
                            Total Variant
                          </text>
                          <text x="50%" y="52%" class="chart-label">
                            Pathogenicity
                          </text>
                          <text x="50%" y="59%" class="chart-label">
                            Curations
                          </text>
                    </g>
                  </svg>

         {{-- </div>
            </div> --}}
        </div>

        <div class="row  mt-4">
          <h4 class="col-sm-12 mb-0">{{ count($metrics->values[App\Metric::KEY_EXPERT_PANELS_PATHOGENICITY]) }} Approved ClinGen Variant Curation Expert Panels</h4>
          <div class="col-sm-12"><small><i>(For a complete list of VCEPs at different stages of the approval process, click <a  target="report" href="https://clinicalgenome.org/affiliation"><u>here</u>)</a></i></small></div>


          @php
            $i=1;
          @endphp

          @foreach (collect($metrics->values[App\Metric::KEY_EXPERT_PANELS_PATHOGENICITY])->sortBy('label')->toArray() as $key => $panel)
          @php
            $i++;
          @endphp
          {{-- @if(++$i <= 1000) --}}
            <div class="col-sm-3 text-center">
              <div class="panel panel-default border-0">
                  <div class="panel-body">
                    <a target="report" href="https://www.clinicalgenome.org/affiliation/{{ $panel['ep_id'] }}" class="text-dark svg-link">
                      {{-- <div class="text-size-lg lineheight-tight">
                        <span style="border: 6px #13a89e solid; border-radius:100rem; margin-bottom:.25rem; padding:1.0rem .5rem .5rem .5rem; min-width:6.5rem; min-height:6.5rem; display:inline-block; color:#0e665c">{{ $panel['count'] }}</span>
                      </div> --}}

                      <svg width="50%" height="50%" viewBox="0 0 42 42" class="donut">

                      <circle class="donut-hole" cx="21" cy="21" r="15.91549430918954" fill="none"/>
                      <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" fill="none" stroke="#000" stroke-width="3"/>

                      <circle class="donut-segment chart-stroke-pathogenic" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['Pathogenic'] }} {{ 100.00 - $panel['classlength']['Pathogenic'] }}" stroke-dashoffset="{{ $panel['classoffsets']['Pathogenic'] }}"  onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['Pathogenic'] }} Pathogenic');" onmouseout="hideSvgTooltip();"/>

                      <circle class="donut-segment chart-stroke-likely-pathogenic" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['Likely Pathogenic'] }} {{ 100.00 - $panel['classlength']['Likely Pathogenic'] }}" stroke-dashoffset="{{ $panel['classoffsets']['Likely Pathogenic'] }}"  onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['Likely Pathogenic'] }} Likely Pathogenic');" onmouseout="hideSvgTooltip();"/>

                      <circle class="donut-segment chart-stroke-uncertain-significance" cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['Uncertain Significance'] }} {{ 100.00 - $panel['classlength']['Uncertain Significance'] }}" stroke-dashoffset="{{ $panel['classoffsets']['Uncertain Significance'] }}"  onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['Uncertain Significance'] }} Uncertain Significance');" onmouseout="hideSvgTooltip();"/>

                      <circle class="donut-segment chart-stroke-likely-benign " cx="21" cy="21" r="15.91549430918954"  transform="rotate(-90 21 21)" fill="none"  stroke-width="3" stroke-dasharray="{{ $panel['classlength']['Likely Benign'] }} {{ 100.00 - $panel['classlength']['Likely Benign'] }}" stroke-dashoffset="{{ $panel['classoffsets']['Likely Benign'] }}"  onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['Likely Benign'] }} Likely Benign');" onmouseout="hideSvgTooltip();"/>

                      <circle class="donut-segment chart-stroke-benign" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $panel['classlength']['Benign'] }} {{ 100.00 - $panel['classlength']['Benign'] }}" stroke-dashoffset="{{ $panel['classoffsets']['Benign'] }}"  onmousemove="showSvgTooltip(evt, '{{ $panel['classtotals']['Benign'] }} Benign');" onmouseout="hideSvgTooltip();"/>

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

                      <div class="mb-2 lineheight-tight">{{ $panel['label'] }}</div>

                    </a>
                  </div>
                </div>
             </div>

          {{-- @endif --}}
          @if ($i % 4 == 1)
            <br clear="all" />
          @endif
          @endforeach
          {{-- <div class="text-center mb-4">
            <a class="btn btn-default btn-lg btn-primary" href="#" role="button">Load more Variant Curation Expert Panels</a>
          </div> --}}


        </div>

        @include('reports.stats.includes.pharmacogenomics')

      </div>
		</div>
	</div>
</div>
<div id="svgtooltip" display="none" style="position: absolute; display: none;"></div>

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

<script>
	/**
	**
	**		Globals
	**
	*/

	/**
	 *
	 * Listener for displaying only genes
	 *
	 * */
	/*$('.donut-segment').on('mouseover', function() {

    var title = $(this).attr("data-text");
    var value = $(this).attr("data-value");

    //alert(title);
    //$('[data-toggle="tooltip"]').tooltip();
	});*/

  $( "#collapseMoreGcepsButton" ).click(function() {
  $( "#collapseMoreGcepsButtonWrapper" ).remove();
});

function showSvgTooltip(evt, text) {
  let svgtooltip = document.getElementById("svgtooltip");
  svgtooltip.innerHTML = text;
  svgtooltip.style.display = "block";
  svgtooltip.style.left = evt.pageX + 10 + 'px';
  svgtooltip.style.top = evt.pageY + 10 + 'px';
}

function hideSvgTooltip() {
  var svgtooltip = document.getElementById("svgtooltip");
  svgtooltip.style.display = "none";
}

function SvgTooltipLink(link) {
  //window.location.replace(link)
  window.open(link, 'report');
}

  $(document).ready(function(){

      $('[data-toggle="tooltip"]').tooltip();
  });

</script>

@endsection
