<div class="panel panel-default" id="tag_genetic_evidence_segregation">
    <div class="panel-heading bg-evidence2" role="tab" id="genev_case_level_segregation"">
        <h4 class="mb-0 mt-0">SCORED GENETIC EVIDENCE</h4>
        Case Level Segregation
        <div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_segregation" href="#tabletwo" aria-expanded="true" aria-controls="tabletwo">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>
    </div>
    <div id="tabletwo" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_segregation">
    <div class="panel-body">
        @if (!$clfs)
        <div class="alert alert-warning" role="alert">
            No  evidence for a Family with a proband was found.
        </div>
        @else
        <div class="table-responsive light-arrows">
            <table id="gecls" role="table" class="table table-validity-data table-bordered small table-striped table-hover"
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
                    data-sort-name="label"
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
                    <tr>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-field="label">Family (Proband) Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference (PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family Phenotypes</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family MOI</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># Aff</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># Unaff</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">LOD Score Counted</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Sequencing Method</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @foreach ($extrecord->segregation as $record)
                        @foreach($record->evidence as $evidence)
                        @if ($evidence->proband === null)
                        @continue
                        @endif
                    <tr>
                        <td>
                            {{ $evidence->label }}
                            <div>({{ $evidence->proband->label ?? '' }})</div>
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
                            @if($evidence->conditions !== null)
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
                            {{ $evidence->phenotype_positive_allele_positive_count ?? ''}}
                        </td>
                        <td>
                            {{ $evidence->phenotype_negative_allele_negative_count ?? ''}}
                        </td>
                        <td>
                            <strong>Calculated:</strong><br>{{ $evidence->estimated_lod_score ?? ''}}
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
</div>
