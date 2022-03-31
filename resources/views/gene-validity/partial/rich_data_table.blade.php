
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
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Label</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Variant<br>Type</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true" data-width="180">Variant</th>
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
                        @foreach ($extrecord->genetic_evidence as $record)

                        @php
                            $evidence = null;
                            $function = null;
                            foreach ($record->evidence as $ev)
                            {
                                if ($ev->__typename == "VariantEvidence")
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
								{{ $evidence->label }}
                            </td>
							<td class="vertical-align-center" role="cell">
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">
                                    {{ $evidence->variant->label }}
                                </div>
							</td>
							<td class="vertical-align-center" role="cell">
                                <span class="text-danger"><strong>####</strong></span>, et al.,
								<span class="text-danger"><strong>####</strong></span>, <a href="{{ $evidence->source->iri }}"
										target="_blank" rel="noopener noreferrer">PMID: {{ basename($evidence->source->iri) }}</a>
                            </td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">
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
                                <span><strong>{{ $record->score }}</strong> (<span class="text-danger"><strong>####</strong></span>)</span>
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
            <b>Total Variant Points:  ####</b>
        </div>
	</div>

    <div class="m-4">&nbsp;</div>

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
            @if (empty($extrecord->genetic_evidence))
            <div class="alert alert-warning" role="alert">
				No  evidence for a Family with a proband was found.
			</div>
            @else
			<div class="table-responsive">
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
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Family (Proband) Label</th>
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
                        @foreach ($extrecord->genetic_evidence as $record)
                        @php
                            $evidence = null;
                            foreach ($record->evidence as $ev)
                            {
                                if ($ev->__typename == "Segregation")
                                {
                                    $evidence = $ev;
                                    break;
                                }
                            }
                            if ($evidence === null)
                                continue;

                        @endphp
                        <tr>
                            <td>
                                {{ $evidence->label }} (<span class="text-danger"><strong>####</strong></span>)
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>, et al.,
								<span class="text-danger"><strong>####</strong></span>, <a href="{{ $evidence->source->iri }}"
										target="_blank" rel="noopener noreferrer">PMID: {{ basename($evidence->source->iri) }}</a>
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
                            <td>
                                {{ $evidence->phenotype_positive_allele_positive_count }}
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>: {{ $evidence->estimated_lod_score }}
                            </td>
                            <td>
                                <span class="text-danger"><strong>####</strong></span>
                            </td>
                            <td>
                                {{ $evidence->sequencing_method->label ?? '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
		</div>
        </div>
    </div>

    <div class="m-4">&nbsp;</div>

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

    <div class="m-4">&nbsp;</div>

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
            <span class="text-danger"><strong>Need structure to determine how to key show/nowhow</strong></span>
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
                            <th colspan="3">Statistics</th>
                            <th colspan="2"></th>
                            <th></th>
                        </tr>
						<tr role="row">
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Label</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference<br>(PMID)</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Disease<br>(Case)</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Study<br>Type</th>
                            <th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Detection Method (Case)</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># of Cases<br>Genotype/Sequenced</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true"># of Controls<br>Genotype/Sequenced</th>
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
        <div class="panel-footer text-right bg-evidence4">
            <b>Total Points:  ####</b>
        </div>
    </div>

    <div class="m-4">&nbsp;</div>

    <div class="panel panel-default" id="tag_experimental_evidence">
        <div class="panel-heading bg-evidence5" role="tab" id="expevid"">
            <h4 class="mb-0 mt-0">EXPERIMENTAL EVIDENCE</h4>
            &nbsp;
            <div class="pull-right">
                <a data-toggle="collapse" data-parent="#tag_experimental_evidence" href="#tablefive" aria-expanded="true" aria-controls="tablefive">
                    <i class="fas fa-compress-arrows-alt"></i>
                </a>
            </div>
        </div>
        <div id="tablefive" class="panel-collapse expand collapse in" role="tabpanel" aria-labelledby="expevid">
        <div class="panel-body">
            @if (empty($extrecord->experimental_evidence))
            <div class="alert alert-warning" role="alert">
				No experimental evidence was found.
			</div>
            @else
			<div class="table-responsive">
				<!-- START DEMO DATA -->
				<table id="table" role="table" class="table table-validity-data table-bordered small table-striped table-hover"
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
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Experimental Category</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reference</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Explanation</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Score Status</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Points (default points)</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Reason for Changed Score</th>
						</tr>
					</thead>
					<tbody role="rowgroup">
                        @foreach($extrecord->experimental_evidence as $record)
						<tr>
							<td class="vertical-align-center" role="cell" style="word-break: normal;">
                                {{ $record->evidence[0]->label }}
							</td>
							<td class="vertical-align-center" role="cell"><span class="text-danger"><strong>####</strong></span>
							</td>
							<td class="vertical-align-center" role="cell"><span class="text-danger"><strong>####</strong></span>, et al.,
								<span class="text-danger"><strong>####</strong></span>, <a href="{{ $record->evidence[0]->source->iri }}"
										target="_blank" rel="noopener noreferrer">PMID: {{ basename($record->evidence[0]->source->iri) }}</a></td>
							<td class="vertical-align-center" role="cell" style="max-width: 600px;">
                                {{ $record->evidence[0]->description }}
                            </td>
							<td class="vertical-align-center" role="cell">
                                Score
                            </td>
							<td class="vertical-align-center" role="cell">
                                <span><strong>{{ $record->score }}</strong> (<span class="text-danger"><strong>####</strong></span>)</span>
                            </td>
							<td class="vertical-align-center" role="cell" style="max-width: 600px;">
                                {{  $record->description }}
                            </td>
						</tr>
                        @endforeach
					</tbody>
				</table>
			</div>
		</div>
        @endif
        </div>
        <div class="panel-footer text-right bg-evidence5">
            <b>Total Points:  ####</b>
        </div>
    </div>
