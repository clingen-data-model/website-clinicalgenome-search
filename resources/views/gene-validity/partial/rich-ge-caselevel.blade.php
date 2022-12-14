<div class="panel panel-default" id="tag_genetic_evidence_case_level_with_proband">
    <!-- Default panel contents -->
    <div class="panel-heading bg-evidence1" role="tab" id="genev_case_level_variants"">
        <h4 class="mb-0 mt-0">SCORED GENETIC EVIDENCE <span class="pull-right small">Total Proband Counted Points:  <u>{{ $ge_count ?? 'N/A' }}</u></span></h4>
        Case Level Variants
        @if ($ge_count > 12)
        <span class="pull-right">Total Maximum Points:  12</span>
        @endif
        <!--<div class="pull-right">
            <a data-toggle="collapse" data-parent="#tag_genetic_evidence_case_level_with_proband" href="#tableone" aria-expanded="true" aria-controls="tableone">
                <i class="fas fa-compress-arrows-alt"></i>
            </a>
        </div>-->
    </div>
    <div class="alert alert-info mx-3 mb-0 mt-3" role="alert"><b>
        <i class="mr-3">Important!</i>  This is an extremely long table and portions of it may be horizontally scrolled out of view.
        Use your horizontal scroll controls on your mouse, pad, or touch screen to view all columns.
    </b></div>
    <div id="tableone" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="genev_case_level_variants">
    <div class="panel-body">
        @if (empty($extrecord->genetic_evidence))
        <div class="alert alert-warning" role="alert">
            No  evidence for a Family with a proband was found.
        </div>
        @else
        <div class="wrapper1">
            <div class="div1">
            </div>
        </div>
        <div class="table-responsive double-scroll light-arrows">
            <table id="geclv" role="table" class="table table-validity-data table-bordered table-striped table-hover"
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
                    data-show-multi-sort="true"
                    data-show-export="true"
                    data-sort-order="asc"
                    data-sort-name="reference"
                    data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'xlsx', 'pdf']"
                    data-minimum-count-columns="2"
                    data-pagination="true"
                    data-id-field="id"
                    data-sticky-header="true"
                    data-page-list="[10, 25, 50, 100, 250, all]"
                    data-page-size="{{ $display_list ?? 25 }}"
                    data-show-footer="false"
                    data-side-pagination="client"
                    data-pagination-v-align="both"
                    data-show-extended-pagination="false"
                    data-query-params="queryParams"
                    data-response-handler="responseHandler"
                    data-header-style="headerStyle"
                    data-show-filter-control-switch="true"
                    data-resizable="true"
                    data-group-by="true"
                    data-group-by-field="pheno">
                <thead>
                    <tr role="row">
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-field="label">Proband<br>Label</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Variant<br>Type</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" style="max-width: 260px;">Variant</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-sorter="referenceSorter" data-field="reference">Reference<br>(PMID)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Sex</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-sorter="ageSorter">Proband<br>Age</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Proband<br>Ethnicity</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Proband<br>Phenotypes</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Proband<br>Previous<br>Testing</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="false">Proband<br>Methods<br>of<br>Detection</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Functional<br>Data<br>(Explanation)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">De Novo<br>(paternity/<br>maternity<br>confirmed)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Score<br>Status</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Points<br>(default<br>points)</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Counted<br>Points</th>
                        <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Explanation</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    @foreach ($extrecord->caselevel as $key => $record)

                    @php
                        $evidence = null;
                        $function = null;
                        $nodenovo = false;
                        $dual = false;
                        foreach ($record->evidence as $ev)
                        {
                            if ($ev->__typename == "VariantEvidence")
                            {
                                $evidence = $ev;
                            }
                            else if ($ev->__typename == 'ProbandEvidence')
                            {
                                if ($record->type[0]->curie == "SEPIO:0004174")
                                    $dual = true;

                                $evidence = $ev;

                                // ugly hack to account for different structures for essentially the same evidence model in genegraph
                                $evidence->proband = $ev;

                                $nodenovo = true;
                            }
                            else if ($ev->__typename == "GenericResource")
                            {
                                $function = $ev;
                            }
                        }
                        if ($evidence === null)
                        {
                            continue;
                            //dd($record);
                        }
                    @endphp

                    <tr>
                        <td class="vertical-align-center" role="cell" style="min-width: 260px; word-break: break-word;">
                            {{ $evidence->proband->label ?? $evidence->label }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            {{--
                                @if (isset($evidence->type[0]->curie))
                            {{ App\Validity::evidenceTypeString($evidence->type[0]->curie ?? '') }}
                            @else
                            --}}
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    {{ App\Validity::evidenceTypeString($v->type[0]->curie ?? '') }}
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                            {{ App\Validity::evidenceTypeString($record->type[0]->curie ?? '') }}
                            @endif
                          {{--  @endif --}}
                        </td>
                        <td class="vertical-align-center" role="cell" style="min-width: 260px; word-break: break-all;">
                            <div class="variant-info">
                                @if ($dual)
                                    @foreach($record->altvariants as $v)
                                        <div class="mt-2">
                                            {{ $v->variant->variant->label ?? '' }}
                                            </div>
                                            @if (isset($v->variant->variant->canonical_reference[0]->curie) && $v->variant->variant->canonical_reference[0]->curie != "http://reg.genome.network/allele/"  && strpos(basename($v->variant->variant->canonical_reference[0]->curie), 'CA') === 0 )
                                            <div class="mt-1">
                                                <a  target="_cgar" href="{{ App\Validity::alleleUrlString($v->variant->variant->canonical_reference[0]->curie) }}" >
                                                    <i>ClinGen Allele Registry:</i><br>
                                                    {{ basename($v->variant->variant->canonical_reference[0]->curie) }}
                                                    <i class="glyphicon glyphicon-new-window"></i>
                                                </a>
                                            </div>
                                            @elseif (isset($v->variant->variant->canonical_reference[0]->curie) && $v->variant->variant->canonical_reference[0]->curie != "http://reg.genome.network/allele/"  && strpos($v->variant->variant->canonical_reference[0]->curie, 'CLINVAR:') === 0 )
                                            <div class="mt-1">
                                                <a  target="_cgar" href="{{ App\Validity::alleleUrlString($v->variant->variant->canonical_reference[0]->curie) }}" >
                                                    <i>ClinVar:</i><br>
                                                    {{ basename($v->variant->variant->canonical_reference[0]->curie) }}
                                                    <i class="glyphicon glyphicon-new-window"></i>
                                                </a>
                                            </div>
                                            @endif
                                        @if (!$loop->last)
                                        <hr>
                                        @endif
                                    @endforeach
                                @else
                                @if(!isset($evidence->variant->label) && isset($evidence->variants) && is_array($evidence->variants))
                                    @foreach($evidence->variants as $v)
                                    <div class="mt-2">
                                    {{ $v->label ?? '' }}
                                    </div>
                                    @if (isset($v->canonical_reference[0]->curie) && $v->canonical_reference[0]->curie != "http://reg.genome.network/allele/" && strpos(basename($v->canonical_reference[0]->curie), 'CA') === 0)
                                    <div class="mt-1">
                                    <a  target="_cgar" href="{{ App\Validity::alleleUrlString($v->canonical_reference[0]->curie) }}" >
                                        <i>ClinGen Allele Registry:</i><br>
                                        {{ basename($v->canonical_reference[0]->curie) }}
                                        <i class="glyphicon glyphicon-new-window"></i>
                                    </a>
                                    </div>
                                    @endif
                                    @if ($dual && !$loop->last)
                                    <hr>
                                    @endif
                                    @endforeach
                                @else
                                    {{ $evidence->variant->label ?? '' }}
                                    @if (isset($evidence->variant->canonical_reference[0]->curie) && strpos(basename($evidence->variant->canonical_reference[0]->curie), 'CA') === 0)
                                    <div class="mt-1">
                                        <a target="_cgar"  href="{{ App\Validity::alleleUrlString($evidence->variant->canonical_reference[0]->curie) }}" >
                                            <i>Clingen Allele Registry:</i><br>
                                            {{ basename($evidence->variant->canonical_reference[0]->curie) }}
                                            <i class="glyphicon glyphicon-new-window"></i>
                                        </a>
                                    </div>
                                    @endif
                                @endif
                                @endif
                            </div>
                            @if ($showzygosity && isset($evidence->zygosity->curie))
                            <div class="variant-info">
                            <strong>{{ App\Validity::zygosityTypeString($evidence->proband->zygosity->curie) }}</strong>
                            </div>
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if ($dual)
                            @foreach($record->altvariants as $v)
                                @if (empty($v->variant->source))
                                <span class="text-danger"><strong>ERROR:  Missing evidence->source structure</strong></span>
                                @else
                                {!! displayCitation($v->variant->source) !!}
                                    @if (in_array($v->variant->source->curie, $extrecord->eas))
                                    <div><span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="The article is selected as earliest report of a variant in the gene causing the disease of interest in a human"><i class="fas fa-check-square text-success"></i></span></div>
                                    @endif
                                @endif
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                            @if (empty($evidence->source))
                            <span class="text-danger"><strong>ERROR:  Missing evidence->source structure</strong></span>
                            @else
                            {!! displayCitation($evidence->source) !!}
                            @if (in_array($evidence->source->curie, $extrecord->eas))
                            <div><span data-toggle="popover" data-placement="top" data-trigger="hover" data-content="The article is selected as earliest report of a variant in the gene causing the disease of interest in a human"><i class="fas fa-check-square text-success"></i></span></div>
                            @endif
                            @endif
                            @endif
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
                            {{ $evidence->proband->ethnicity->label ?? '' }}
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if (!empty($evidence->proband->phenotypes))
                            <strong>HPO terms(s):</strong>
                            <ul>
                                @foreach($evidence->proband->phenotypes as $term)
                                <li>{{ $term->label }} ({{ $term->curie }})</li>
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
                            @endif
                            @if (!empty($evidence->proband->genotyping_method))
                            <strong>Description of genotyping method:</strong>
                            {{ $evidence->proband->genotyping_method ?? '' }}
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if ($showfunctionaldata)
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    {{ empty($v->function) ?  'No' : 'Yes (' . $v->function->description . ')'}}
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                            {{ empty($function) ?  'No' : 'Yes (' . $function->description . ')'}}
                            @endif
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    @if (isset($v->variant->allele_origin))
                                    @switch($v->variant->allele_origin)
                                        @case("http://purl.obolibrary.org/obo/GENO_0000880")
                                        Yes
                                        @if (isset($v->variant->proband->paternity_maternity_confirmed))
                                        ({{ $v->variant->proband->paternity_maternity_confirmed ?? '' }})
                                        @endif
                                        @break
                                        @case("http://purl.obolibrary.org/obo/GENO_0000888")
                                        No
                                        @if (isset($v->variant->proband->paternity_maternity_confirmed))
                                        ({{ $v->variant->proband->paternity_maternity_confirmed ?? '' }})
                                        @endif
                                        @break
                                        @case("http://purl.obolibrary.org/obo/GENO_0000877")
                                        @default
                                        Unknown
                                        @if (isset($v->variant->proband->paternity_maternity_confirmed))
                                        ({{ $v->variant->proband->paternity_maternity_confirmed ?? '' }})
                                        @endif
                                    @endswitch
                                    @endif
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                                @if (isset($evidence->allele_origin))
                                @switch($evidence->allele_origin)
                                    @case("http://purl.obolibrary.org/obo/GENO_0000880")
                                    Yes ({{ $evidence->proband->paternity_maternity_confirmed }})
                                    @break
                                     @case("http://purl.obolibrary.org/obo/GENO_0000888")
                                    No
                                    @break
                                    @case("http://purl.obolibrary.org/obo/GENO_0000877")
                                    @default
                                    Unknown
                                @endswitch
                                @endif
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    {{ $v->score_status ?? '' }}
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                            {{ $record->score_status->label ?? '' }}
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    <span><strong>{{ $v->score }}</strong> ({{ $v->calculated_score }})</span>
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                            <span><strong>{{ $record->score }}</strong> ({{ $record->calculated_score }})</span>
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell">
                            @if (isset($propoints[$evidence->proband->label]))
                                {{ number_format($propoints[$evidence->proband->label],2) }}
                            @elseif (isset($propoints[$evidence->label]))
                                {{ number_format($propoints[$evidence->label],2) }}
                            @else
                                {{ $record->score }}
                            @endif
                        </td>
                        <td class="vertical-align-center" role="cell" style="max-width: 240px;">
                            @if ($dual)
                                @foreach($record->altvariants as $v)
                                    @markdown {{ $v->description }} @endmarkdown
                                    @if (!$loop->last)
                                    <hr>
                                    @endif
                                @endforeach
                            @else
                                @markdown {{ $record->description }} @endmarkdown
                            @endif
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
        <b>Total Proband Counted Points:  {{ $ge_count ?? 'N/A' }}</b>
        @if ($ge_count > 12)
        <div >Total Maximum Points:  12</div>
        @endif
    </div>
</div>
