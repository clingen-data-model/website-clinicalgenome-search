<hr class="mt-4 pb-4" />
<h2 id="pharmacogenomics">
    <img src="https://search.clinicalgenome.org/images/Pharmacogenomics-on.png" width="50px"  style="margin-top:-10px; margin-left:-50px"  />  Pharmacogenomics
</h2>
<p>The overarching goal of the Pharmacogenomics...</p>
<div class="row text-center">
    <div class="col-md-7 mt-4">

          <div class="col-sm-12 text-left"><h4>{{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_PHARMACOGENOMIICS] ?? '' }} Total Pharmacogenomics Annotations</h4></div>

          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_CPC_PHARMACOGENOMIICS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Cpic <br />Annotations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_GKB_PHARMACOGENOMIICS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total PharmGKB <br />Annotations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_GENES_CPC_PHARMACOGENOMIICS] ?? '' }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Genes Included in <br>Cpic annotations</div>
                </div>
              </div>
          </div>
          <div class="col-sm-6 px-1">
            <div class="panel panel-default border-primary">
                <div class="panel-body p-1">
                  <div class="text-size-lg lineheight-tight">
                    {{ $metrics->values[App\Metric::KEY_TOTAL_GENES_GKB_PHARMACOGENOMIICS] }}
                  </div>
                  <div class="mb-2 lineheight-tight">Total Gene-Included in<br>PharmGKB annotations</div>
                </div>
              </div>
          </div>
        </div>

          <div class="col-md-4">
            <svg width="110%" height="110%" viewBox="0 0 42 42" class="donut">

                        <circle class="donut-hole" cx="21" cy="21" r="13.91549430918954" transform="rotate(-90 21 21)" fill="none"/>
                        <circle class="donut-ring" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke="#000" stroke-width="3"/>

                        <circle class="donut-segment chart-stroke-actionability-peds" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" data-container="body"  fill="none"  stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classlength']['Cpic'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classlength']['Cpic'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classoffsets']['Cpic'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classtotals']['Cpic'] }} Cpic');" onmouseout="hideSvgTooltip();"/>

                        <circle class="donut-segment chart-stroke-actionability-adult" cx="21" cy="21" r="15.91549430918954" transform="rotate(-90 21 21)" fill="none" stroke-width="3" stroke-dasharray="{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classlength']['PharmGKB'] }} {{ 100.00 - $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classlength']['PharmGKB'] }}" stroke-dashoffset="{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classoffsets']['PharmGKB'] }}" onmousemove="showSvgTooltip(evt, '{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['classtotals']['PharmGKB'] }} PharmGKB');" onmouseout="hideSvgTooltip();"/>

                        <!-- unused 10% -->
                        <g class="chart-text chart-small">
                          <text x="50%" y="45%" class="chart-number">
                            {{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_PHARMACOGENOMIICS] ?? '' }}
                          </text>
                          <text x="50%" y="45%" class="chart-label">
                            Total
                          </text>
                          <text x="50%" y="52%" class="chart-label">
                            Annotations
                          </text>
                        </g>
                      </svg>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-sm-6">
            <h5 class="mb-0">Cpic Annotations </h4>

              <p>{{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_CPC_PHARMACOGENOMIICS] ?? '' }} Total Cpic Annotations</p>

              <p><strong>Total Scores Visualized</strong></p>

            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level A</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['A'] ?? 0) }}%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['A'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level A/B</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['A/B'] ?? 0) }}%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['A/B'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level B</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['B'] ?? 0) }}%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['B'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level B/C</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['B/C'] ?? 0) }}%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['B/C'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level C</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['C'] ?? 0) }}%; background-color:#55b2e3; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['C'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level C/D</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['C/D'] ?? 0) }}%; background-color:#367fc2; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['C/D'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level D</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaCpicPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['D'] ?? 0) }}%; background-color:#69399a; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['Cpic']['D'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6 border-left-1">
            <h5 class="mb-0">PharmGKB Annotations </h4>

              <p>{{ $metrics->values[App\Metric::KEY_TOTAL_ANNOT_GKB_PHARMACOGENOMIICS] ?? '' }} Total PharmGKB Annotations</p>

              <p><strong>Total Scores Visualized</strong></p>
            <table class="table table-condensed">
              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level 1A</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaGkbPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['1A'] ?? 0) }}%; background-color:#a2cb50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['1A'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level 1B</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaGkbPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['1B'] ?? 0) }}%; background-color:#65ba59; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['1B'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level 2A</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaGkbPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['2A'] ?? 0) }}%; background-color:#469c50; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['2A'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>

              <tr class="">
                <td class="col-sm-2 text-right border-0"><strong>Level 2B</strong></td>
                <td class="border-0">
                  <div class="progress progress-no-bg mb-0 mt-0">
                    <div class="progress-bar progress-bar-success progress-bar-left-radius-0" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: {{ $metrics->pharmaGkbPercent($metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['2B'] ?? 0) }}%; background-color:#4fb0a8; opacity:1">
                    </div>
                    <span class="ml-2">{{ $metrics->values[App\Metric::KEY_TOTAL_PHARMACOGENOMICS_GRAPH]['scores']['PharmGKB']['2B'] ?? 0 }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>