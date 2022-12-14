<div class="panel panel-default" id="tag_genetic_evidence_case_control">
    <div class="panel-heading bg-evidence4" role="tab" id="genev_case_control"">
        <h4 class="mb-0 mt-0">GENETIC EVIDENCE <span class="pull-right small">Total Points:  <u>{{ $cc_count ?? 'N/A' }}</u></span></h4>
        Case Control
        <!--<div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_control" href="#tablefour" aria-expanded="true" aria-controls="tablefour">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>-->
    </div>
    <div class="alert alert-info mx-3 mb-0 mt-3" role="alert"><b>
        <i class="mr-3">Important!</i>  This is an extremely long table and portions of it may be horizontally scrolled out of view.
        Use your horizontal scroll controls on your mouse, pad, or touch screen to view all columns.
    </b></div>
    <div id="tablefour" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_control">
        <div class="panel-body">
            @if (false)
            <div class="alert alert-warning" role="alert">
                No case-control genetic evidence was found.
            </div>
            @else
            <div class="table-responsive light-arrows">
                <table id="gecc" role="table" class="table table-validity-data table-bordered table-striped table-hover"
                        data-classes="table"
                        data-locale="en-US"
                        data-addrbar="true"
                        data-search="true"
                        data-filter-control="true"
                        data-filter-control-visible="false"
                        data-id-table="advancedTable"
                        data-search-align="left"
                        data-trim-on-search="true"
                        data-sort-order="asc"
                        data-sort-name="reference"
                        data-show-search-clear-button="true"
                        data-buttons="table_buttons"
                        data-show-align="left"
                        data-show-fullscreen="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="true"
                        data-search-formatter="false"
                        data-show-multi-sort="true"
                        data-show-export="true"
                        data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'xlsx', 'pdf']"
                        data-minimum-count-columns="2"
                        data-pagination="true"
                        data-id-field="id"
                        data-sticky-header="true"
                        {{-- data-ajax-options="ajaxOptions" --}}
                        data-page-list="[10, 25, 50, 100, 250, all]"
                        data-page-size="{{ $display_list ?? 25 }}"
                        data-show-footer="false"
                        data-side-pagination="client"
                        data-pagination-v-align="both"
                        data-show-extended-pagination="false"
                        {{-- data-url="{{  $apiurl }}" --}}
                        data-query-params="queryParams"
                        data-response-handler="responseHandler"
                        data-header-style="headerStyle"
                        data-show-filter-control-switch="true"
                        data-group-by="true"
                        data-group-by-field="pheno">
                    <thead>
                        <tr role="row">
                            <th colspan="5"></th>
                            <th colspan="2">Power</th>
                            <th></th>
                            <th colspan="4">Statistics</th>
                            <th colspan="1"></th>
                            <th></th>
                        </tr>
                        <tr role="row">
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-field="label">Label</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-sorter="referenceSorter" data-field="reference">Reference<br>(PMID)</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Disease<br>(Case)</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Study<br>Type</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Detection Method (Case)</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># of Cases<br>Genotype/<br>Sequenced</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># of Controls<br>Genotype/<br>Sequenced</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Bias Confounding</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Cases with<br>Variant in Gene<br>/ All Cases<br>Genotype/Sequenced</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Controls with<br>Variant in Gene<br>/ All Cases<br>Genotype/Sequenced</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Test<br>Statistic:<br>Value</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">P-value</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Confidence<br>interval</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Points</th>
                        </tr>
                    </thead>
                    <tbody role="rowgroup">
                        @foreach($extrecord->casecontrol as $casecontrol)
                            @foreach ($casecontrol->evidence as $evidence)
                            <tr>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->label }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    @if (empty($evidence->source))
                                    <span class="text-danger"><strong>ERROR:  Missing evidence->source structure</strong></span>
                                    @else
                                    {!! displayCitation($evidence->source) !!}
                                    @if (in_array($evidence->source->curie, $extrecord->eas))
                                    <div><span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="The article is selected as earliest report of a variant in the gene causing the disease of interest in a human"><i class="fas fa-check-square text-success"></i></span></div>
                                    @endif
                                    @endif
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->case_cohort->disease->label ?? '' }}
                                    <div>({{ $evidence->case_cohort->disease->curie ?? '' }})</div>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ App\Validity::evidenceTypeString($casecontrol->type[0]->curie ?? '') }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->case_cohort->case_detection_method ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->case_cohort->all_genotyped_sequenced ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->control_cohort->all_genotyped_sequenced ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $casecontrol->description }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->case_cohort->num_with_variant ?? '' }}/{{ $evidence->case_cohort->all_genotyped_sequenced ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->control_cohort->num_with_variant ?? '' }}/{{ $evidence->control_cohort->all_genotyped_sequenced ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    @if (!empty($evidence->statistical_significance_value_type) && !empty($evidence->statistical_significance_value))
                                    <strong>{{ $evidence->statistical_significance_value_type }}:</strong>
                                    {{ $evidence->statistical_significance_value }}
                                    @elseif (!empty($evidence->statistical_significance_value_type) && empty($evidence->statistical_significance_value))
                                    <strong>{{ $evidence->statistical_significance_value_type }}:</strong>
                                    0
                                    @endif
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $evidence->p_value ?? '' }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    @if (isset($evidence->confidence_interval_from) && $evidence->confidence_interval_from !== null)
                                        {{ $evidence->confidence_interval_from }}-{{ $evidence->confidence_interval_to }} (%)
                                    @endif
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $casecontrol->score }}
                                </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    <div class="panel-footer text-right bg-evidence4">
        <b>Total Points: {{ $cc_count ?? 'N/A' }} </b>
    </div>
</div>
