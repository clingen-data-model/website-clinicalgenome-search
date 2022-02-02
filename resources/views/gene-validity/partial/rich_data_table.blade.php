@section('content-full-width')
<section id='validity_supporting_data' class="container-fluid">
	<div class="row " id="validity_supporting_data_genetic">
		<hr />
		<div class="col-12 pb-4" id='tag_genetic_evidence_case_level_with_proband'>
			<h3 class="text-white bg-dark p-1"> SCORED GENETIC EVIDENCE: CASE LEVEL (VARIANTS)</h3>
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
                        data-group-by="true"
                        data-group-by-field="pheno">
					<thead>
						<tr role="row">
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Proband<br>Label</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Variant<br>Type</th>
							<th data-cell-style="cellFormatter" data-filter-control="input" data-sortable="true">Variant</th>
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

        <hr />

        <div class="col-12 pb-4" id='tag_genetic_evidence_segregation'>
			<h3 class="text-white bg-dark p-1">SCORED GENETIC EVIDENCE: CASE LEVEL (SEGREGATION)</h3>
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
                                {{ $evidence->sequencing_method }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
		</div>

        <hr />

		<div class="col-12 pb-4" id='tag_genetic_evidence_case_level_without_proband'>
			<h3 class="text-white bg-dark p-1">GENETIC EVIDENCE: CASE LEVEL (FAMILY SEGREGATION INFORMTAION WITHOUT PROBAND DATA OR SCORED PROBAND)
				data)</h3>
			<div class="alert alert-warning" role="alert">
				No segregation evidence for a Family without a proband was found.
			</div>
		</div>

        <hr />

		<div class="col-12 pb-4" id='tag_genetic_evidence_case_control'>
			<h3 class="text-white bg-dark p-1">GENETIC EVIDENCE: CASE-CONTROL</h3>

			<div class="alert alert-warning" role="alert">
				No case-control genetic evidence was found.
			</div>
		</div>

        <hr />

		<div class="col-12 pb-4" id='tag_experimental_evidence'>
			<h3 class="text-white bg-dark p-1">EXPERIMENTAL EVIDENCE</h3>
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
		<hr />
	</div>
</section>
@endsection
