<div class="panel panel-default" id="tag_genetic_evidence_case_control">
    <div class="panel-heading bg-evidence4" role="tab" id="genev_case_control"">
        <h4 class="mb-0 mt-0">GENETIC EVIDENCE</h4>
        Case Control
        <div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_control" href="#tablefour" aria-expanded="true" aria-controls="tablefour">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>
    </div>
    <div id="tablefour" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_control">
        <div class="panel-body">
            @if (false)
            <div class="alert alert-warning" role="alert">
                No case-control genetic evidence was found.
            </div>
            @else
            <div class="table-responsive">
                <table id="gecc" role="table" class="table table-validity-data table-bordered small table-striped table-hover"
                        data-classes="table"
                        data-locale="en-US"
                        data-addrbar="true"
                        data-search="true"
                        data-filter-control="true"
                        data-filter-control-visible="false"
                        data-id-table="advancedTable"
                        data-search-align="left"
                        data-trim-on-search="true"
                        data-show-search-clear-button="true"
                        data-buttons="table_buttons"
                        data-show-align="left"
                        data-show-fullscreen="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="true"
                        data-search-formatter="false"
                        data-show-export="true"
                        data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'xlsx', 'pdf']"
                        data-minimum-count-columns="2"
                        data-pagination="true"
                        data-id-field="id"
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
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Label</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference<br>(PMID)</th>
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
                                    <span class="text-danger"><strong>####</strong></span>, et al.,
                                    <span class="text-danger"><strong>####</strong></span>, <a href="{{ $evidence->source->iri }}"
                                            target="_blank" rel="noopener noreferrer">PMID: {{ basename($evidence->source->iri) }}</a>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ App\Validity::evidenceTypeString($casecontrol->type[0]->curie ?? '') }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    {{ $casecontrol->description }}
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
                                </td>
                                <td class="vertical-align-center" role="cell">
                                    <span class="text-danger"><strong>####</strong></span>
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
