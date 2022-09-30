<div class="panel panel-default" id="tag_genetic_evidence_case_level_without_proband">
    <div class="panel-heading bg-evidence3" role="tab" id="genev_case_level_family"">
        <h4 class="mb-0 mt-0">GENETIC EVIDENCE <span class="pull-right small">Total LOD Score:  <u>{{ $clfs_count ?? 'N/A' }}</u></span></h4>
        Case Level Family Segregation Information Without Proband Data or Scored Proband
        <!--<div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_level_without_proband" href="#tablethree" aria-expanded="true" aria-controls="tablethree">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>-->
    </div>
    <div id="tablethree" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_family">
    <div class="panel-body">
        @if (!$clfswopb)
        <div class="alert alert-warning" role="alert">
            No segregation evidence for a Family without a proband was found.
        </div>
        @else
        <div class="table-responsive light-arrows">
            <table id="geclfs" role="table" class="table table-validity-data table-bordered table-striped table-hover"
                    data-classes="table"
                    data-locale="en-US"
                    data-addrbar="true"
                    data-search="true"
                    data-filter-control="true"
                    data-filter-control-visible="false"
                    data-id-table="advancedTable"
                    data-search-align="left"
                    data-sort-order="asc"
                    data-sort-name="reference"
                    data-trim-on-search="true"
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
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-field="label">Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-sorter="referenceSorter" data-field="reference">Reference<br>(PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Family<br>Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Family<br>Phenotypes</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family<br>MOI</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Number of<br>Affected<br>Individuals</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Number of<br>Unaffected<br>Individuals</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score<br>Counted</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Sequencing<br>Method</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @foreach ($extrecord->segregation as $record)
                        @foreach($record->evidence as $evidence)
                        @if ($evidence->proband !== null && $evidence->proband->label !== null || ($evidence->estimated_lod_score === null && $evidence->published_lod_score === null))
                        @continue
                        @endif
                    <tr>
                        <td>
                            {{ $evidence->label }}
                        </td>
                        <td>
                            @if (empty($evidence->source))
                            <span class="text-danger"><strong>ERROR:  Missing evidence->source structure</strong></span>
                            @else
                            {!! displayCitation($evidence->source) !!}
                            @if (in_array($evidence->source->curie, $extrecord->eas))
                            <div><span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="The article is selected as earliest report of a variant in the gene causing the disease of interest in a human"><i class="fas fa-check-square text-success"></i></span></div>
                            @endif
                            @endif
                        </td>
                        <td>
                            {{ $evidence->family->ethnicity->label ?? '' }}
                        </td>
                        <td class="vertical-align-center text-left" role="cell">
                            @if(!empty($evidence->conditions))
                            <strong>HPO terms(s)</strong>
                            <ul>
                                @foreach($evidence->conditions as $condition)
                                <li>{{ $condition->label }} ({{ $condition->curie }})</li>
                                @endforeach
                            </ul>
                            @endif
                            @if(!empty($evidence->phenotype_free_text))
                            <strong>Free text:</strong><br>
                            {{ $evidence->phenotype_free_text }}
                            @endif
                        </td>
                        <td>
                            {{ $evidence->family->mode_of_inheritance ?? '' }}
                        </td>
                        <td>
                            {{ $evidence->phenotype_positive_allele_positive_count }}
                        </td>
                        <td>
                            {{ $evidence->phenotype_negative_allele_negative_count }}
                        </td>
                        <td>
                            @if ($evidence->estimated_lod_score !== null)
                            <strong>Calculated:</strong><br>{{ $evidence->estimated_lod_score }}
                            @elseif ($evidence->published_lod_score !== null)
                            <strong>Calculated:</strong><br>{{ $evidence->published_lod_score }}
                            @endif
                        </td>
                        <td>
                            {{ $evidence->meets_inclusion_criteria ? 'Yes' : 'No' }}
                        </td>
                        <td>
                            {{ ucfirst($evidence->sequencing_method->label ?? '') }}
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
    <div class="panel-footer text-right bg-evidence3">
        <b>Total LOD Score:  {{ $clfs_count ?? 'N/A' }}</b>
    </div>
</div>
