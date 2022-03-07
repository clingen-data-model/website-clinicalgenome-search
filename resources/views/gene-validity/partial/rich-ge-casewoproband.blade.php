<div class="panel panel-default" id="tag_genetic_evidence_case_level_without_proband">
    <div class="panel-heading bg-evidence3" role="tab" id="genev_case_level_family"">
        <h4 class="mb-0 mt-0">GENETIC EVIDENCE</h4>
        Case Level Family Segregation Information Without Proband Data or Scored Proband
        <div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_level_without_proband" href="#tablethree" aria-expanded="true" aria-controls="tablethree">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>
    </div>
    <div id="tablethree" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_family">
    <div class="panel-body">
        @if (false)
        <div class="alert alert-warning" role="alert">
            No segregation evidence for a Family without a proband was found.
            <span class="text-danger"><strong>Need a gene example of what this table looks like</strong></span>
        </div>
        @else
        <span class="text-danger"><strong>Need structure to determine how to key show/nowhow</strong></span>
        <div class="table-responsive">
            <table id="geclfs" role="table" class="table table-validity-data table-bordered small table-striped table-hover"
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
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference<br>(PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family<br>Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family<br>Phenotypes</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family<br>MOI</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Number of<br>Affected<br>Individuals</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Number of<br>Unaffected<br>Individuals</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score<br>Counted</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Sequencing<br>Method</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
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
                        <span class="text-danger"><strong>####</strong></span>
                    </td>
                </tbody>
            </table>
        </div>
        @endif
    </div>
    </div>
    <div class="panel-footer text-right bg-evidence3">
        <b>Total LOD Score:  ####</b>
    </div>
</div>
