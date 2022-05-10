<div class="panel panel-default" id="tag_genetic_evidence_case_level_with_proband">
    <!-- Default panel contents -->
    <div class="panel-heading bg-evidence1" role="tab" id="genev_case_level_variants"">
        <h4 class="mb-0 mt-0">SCORED GENETIC EVIDENCE</h4>
        Case Level Variants
        <div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_level_with_proband" href="#tableone" aria-expanded="true" aria-controls="tableone">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>
    </div>
    <div id="tableone" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_variants">
    <div class="panel-body">
        @if (empty($extrecord->genetic_evidence))
        <div class="alert alert-warning" role="alert">
            No  evidence for a Family with a proband was found.
        </div>
        @else
        <div class="table-responsive">
            <table id="geclv" role="table" class="table table-validity-data table-bordered small table-striped table-hover"
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
                    data-resizable="true"
                    data-group-by="true"
                    data-group-by-field="pheno">
                <thead>
                    <tr role="row">
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" >Proband<br>Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Variant<br>Type</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-width="100">Variant</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference<br>(PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Sex</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Age</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Phenotypes</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Previous<br>Testing</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Methods<br>of<br>Detection</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Functional<br>Data<br>(Explanation)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">De Novo (paternity/<br>maternity<br>confirmed)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Score<br>Status</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Points<br>(default<br>points)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Counted<br>Points</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Explanation</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @foreach ($extrecord->caselevel as $record)

                    @php
                        $evidence = null;
                        $function = null;
                        foreach ($record->evidence as $ev)
                        {
                            if ($ev->__typename == "VariantEvidence" || $ev->__typename == 'ProbandEvidence')
                            {
                                $evidence = $ev;
                            }
                            else if ($ev->__typename == "GenericResource")
                            {
                                $function = $ev;
                            }
                        }
                        if ($evidence === null)
                        {
                            continue;
                        }
                    @endphp

                    <tr>
                        <td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
                            {{ $evidence->proband->label ?? $evidence->label }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            {{ App\Validity::evidenceTypeString($record->type[0]->curie ?? '') }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            <div class="variant-info">
                                @if(isset($evidence->variants) && is_array($evidence->variants))
                                    {{ $evidence->variants[0]->label ?? 'NT' }}
                                @else
                                    {{ $evidence->variant->label ?? '' }}
                                @endif
                            </div>
                        </td>
                        <td class="vertical-align-center" role="cell">
                            <span class="text-danger"><strong>####</strong></span>, et al.,
                            <span class="text-danger"><strong>####</strong></span>, <a href="{{ $evidence->source->iri }}"
                                    target="_blank" rel="noopener noreferrer">PMID: {{ basename($evidence->source->iri) }}</a>
                        </td>
                        <td class="vertical-align-center" role="cell" style="max-width: 80px;">
                            {{ $evidence->proband->sex->label ?? '' }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if (!empty($evidence->proband->age_type->label))
                            <strong>Age of {{ $evidence->proband->age_type->label }}: </strong>
                            @endif
                            {{ $evidence->proband->age_value ?? '' }} {{ $evidence->proband->age_unit->label ?? '' }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            {{ $evidence->proband->ethnicity ?? '' }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if (!empty($evidence->proband->phenotypes))
                            <strong>HPO terms(s):</strong>
                            <ul>
                                @foreach($evidence->proband->phenotypes as $term)
                                <li><span class="text-danger"><strong>{{ basename($term) }}</strong></span></li>
                                @endforeach
                            </ul>
                            @endif
                            @if(!empty($evidence->proband->phenotype_free_text))
                            <strong>Free text:</strong><br>
                            {{ $evidence->proband->phenotype_free_text }}
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            {{ $evidence->proband->previous_testing_description ?? '' }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if (!empty($evidence->proband->testing_methods))
                                @foreach($evidence->proband->testing_methods as $key => $value)
                                <strong>Method {{ $key + 1 }}:</strong><br>{{ $value }}<br>
                                @endforeach
                                <strong>Description of genotyping method:</strong>
                                <span class="text-danger"><strong>####</strong></span>
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            {{ empty($function) ?  'No' : 'Yes (' . $function->description . ')'}}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            <span class="text-danger"><strong>####</strong></span>
                        </td>
                        <td class="vertical-align-center" role="cell">
                            Score
                        </td>
                        <td class="vertical-align-center" role="cell">
                            <span><strong>{{ $record->score }}</strong> (<span class="text-danger"><strong>####</strong></span>)</span>
                        </td>
                        <td class="vertical-align-center" role="cell">
                            <span class="text-danger"><strong>####</strong></span>
                        </td>
                        <td class="vertical-align-center" role="cell" style="max-width: 240px;">
                            {{ $record->description }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    </div>
    <div class="panel-footer text-right bg-evidence1">
        <b>Total Variant Points:  {{ $ge_count ?? 'N/A' }}</b>
    </div>
</div>
