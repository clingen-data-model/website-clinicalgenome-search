<div class="panel panel-default" id="tag_genetic_evidence_segregation">
    <div class="panel-heading bg-evidence2" role="tab" id="genev_case_level_segregation"">
        <h4 class="mb-0 mt-0">SCORED GENETIC EVIDENCE  <span class="pull-right small">Total Counted LOD Score <span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="This is the cumulative LOD score (from counted cases) across both segregation tabs."><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            :  {{ $cls_sum ?? 'N/A' }}</span></h4>
        Case Level Segregation
        <span class="pull-right text-secondary">Total Points <span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="This is the total amount of segregation points scored, determined based on the aggregated LOD scores across both segregation tabs.  See the gene-disease validity SOP for further information."><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            :  {{ $cls_pt_count ?? 0 }}</span>
        <!--<div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_segregation" href="#tabletwo" aria-expanded="true" aria-controls="tabletwo">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>-->
    </div>
    <div class="text-danger ml-3 mb-0 mt-2">
        Note:  This is an extremely wide table and portions of it may be horizontally scrolled out of view.
        Use the horizontal scroll controls on your mouse, pad, or touch screen to view all columns.
    </div>
    <div id="tabletwo" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_segregation">
    <div class="panel-body">
        @if (!$clfs)
        <div class="alert alert-warning" role="alert">
            No  evidence for a Family with a proband was found.
        </div>
        @else
        <div class="table-responsive light-arrows">
            <table id="gecls" role="table" class="table table-validity-data table-bordered table-striped table-hover"
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
                    <tr>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-field="label">Family (Proband) Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-sorter="referenceSorter" data-field="reference">Reference (PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Family Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Family Phenotypes</th>
                        @if ($moiflag)
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family MOI</th>
                        @endif
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
                            @if(!empty($evidence->conditions))
                            <strong>HPO terms(s)</strong>
                            <ul>
                                @foreach(App\Validity::hpsort($evidence->conditions) as $condition)
                                <li>{{ $condition->label }} ({{ $condition->curie }})</li>
                                @endforeach
                            </ul>
                            @endif
                            @if(!empty($evidence->phenotype_free_text))
                            <strong>Free text:</strong><br>
                            {{ $evidence->phenotype_free_text }}
                            @endif
                        </td>
                        @if($moiflag)
                        <td>
                            {{ $evidence->family->mode_of_inheritance ?? '' }}
                        </td>
                        @endif
                        <td>
                            {{ $evidence->phenotype_positive_allele_positive_count ?? ''}}
                        </td>
                        <td>
                            {{ $evidence->phenotype_negative_allele_negative_count ?? ''}}
                        </td>
                        <td>
                            @if ($evidence->published_lod_score !== null)
                            <strong>Published:</strong><br>{{ $evidence->published_lod_score }}
                            @elseif ($evidence->estimated_lod_score !== null)
                            <strong>Calculated:</strong><br>{{ $evidence->estimated_lod_score }}
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
    <div class="panel-footer text-right bg-evidence2">
        <b>Total Counted LOD Score <span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="This is the cumulative LOD score (from counted cases) across both segregation tabs."><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            :  {{ $cls_sum ?? 'N/A' }}</b>
        <div><b>Total Points <span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="This is the total amount of segregation points scored, determined based on the aggregated LOD scores across both segregation tabs.  See the gene-disease validity SOP for further information."><i class="fa fa-info-circle" aria-hidden="true"></i></span>
            :  {{ $cls_pt_count ?? 0 }}</b></div>
    </div>
</div>
