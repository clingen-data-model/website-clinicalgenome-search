@section('content-full-width')
<section id='validity_supporting_data' class="container-fluid">
	<div class="row " id="validity_supporting_data_genetic">
		<hr />
		<div class="col-12 pb-4" id='tag_genetic_evidence_case_level_with_proband'>
			<h3> Scored Genetic Evidence: Case Level (variants)</h3>
            @if (empty($extrecord->genetic_evidence))
            <div class="alert alert-warning" role="alert">
				No  evidence for a Family with a proband was found.
			</div>
            @else
			<div class="table-responsive">
				<!-- START DEMO DATA -->
				<table id="geclv" role="table" class="table-validity-data table table-bordered table-sm table-striped table-hover">
					<thead>
						<tr role="row">
							<th colspan="1" role="columnheader">Proband<br>Label</th>
							<th colspan="1" role="columnheader">Variant<br>Type</th>
							<th colspan="1" role="columnheader">Variant</th>
							<th colspan="1" role="columnheader">Reference<br>(PMID)</th>
							<th colspan="1" role="columnheader">Proband<br>Sex</th>
							<th colspan="1" role="columnheader">Proband<br>Age</th>
							<th colspan="1" role="columnheader">Proband<br>Ethnicity</th>
							<th colspan="1" role="columnheader">Proband<br>Phenotypes</th>
							<th colspan="1" role="columnheader">Proband<br>Previous<br>Testing</th>
                            <th colspan="1" role="columnheader">Proband<br>Methods<br>of<br>Detection</th>
                            <th colspan="1" role="columnheader">Functional<br>Data<br>(Explanation)</th>
                            <th colspan="1" role="columnheader">De Novo (paternity/<br>maternity<br>confirmed)</th>
							<th colspan="1" role="columnheader">Score<br>Status</th>
							<th colspan="1" role="columnheader">Proband<br>Points<br>(default<br>points)</th>
                            <th colspan="1" role="columnheader">Proband<br>Counted<br>Points</th>
							<th colspan="1" role="columnheader">Explanation</th>
						</tr>
					</thead>
					<tbody role="rowgroup">
                        @foreach ($extrecord->genetic_evidence as $record)
                        @php
                            $evidence = null;
                            foreach ($record->evidence as $ev)
                            {
                                if ($ev->__typename == "VariantEvidence")
                                {
                                    $evidence = $ev;
                                    break;
                                }
                            }
                            if ($evidence === null)
                                continue;
                        @endphp
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
								{{ $evidence->label }}
                            </td>
							<td class="vertical-align-center" role="cell">
                                ### NO PROPER TYPE STRING FOUND ###
                            </td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">
                                    {{ $evidence->variant->label }}
                                </div>
							</td>
							<td class="vertical-align-center" role="cell">
                                <span>##NO PPOPER CITATION, et al.,
									<strong>NO PROPER YEAR</strong>, <a href="{{ $evidence->source->iri }}"
										target="_blank" rel="noopener noreferrer">PMID: {{ basename($evidence->source->iri) }}</a></span>
                            </td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">
                                ## No Proband Sex Field ##
                            </td>
							<td class="vertical-align-center" role="cell">
                                ## No Proband Age Field ##
                            </td>
							<td class="vertical-align-center" role="cell">
                                ## No Proband Ethnicity Field ##
                            </td>
							<td class="vertical-align-center" role="cell">
                                No Proband Phenotypes Fields ##
                            </td>
							<td class="vertical-align-center" role="cell">
                                ## No Proband Previous Testing Field##
                            </td>
							<td class="vertical-align-center" role="cell">
                                ## Probands Metthods of Detection fields##
                            </td>
							<td class="vertical-align-center" role="cell">
                                ## No Functionnal Data field ##
							</td>
							<td class="vertical-align-center" role="cell">
                                ##No DeNovo Field ##
                            </td>
							<td class="vertical-align-center" role="cell">
                                Score
                            </td>
							<td class="vertical-align-center" role="cell">
                                <span><strong>{{ $record->score }}</strong> (####)</span>
                            </td>
                            <td class="vertical-align-center" role="cell">
                                ## No Proband Counted Points ##
                            </td>
							<td class="vertical-align-center" role="cell" style="max-width: 240px;">
                                {{ $record->description }}
                            </td>
						</tr>
                        @endforeach
                        <!--
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
								Proband III:1</td>
							<td class="vertical-align-center" role="cell">Proband with other variant type with some
								evidence of gene impact</td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">NM_006013.5(RPL10):c.191C&gt;T (p.Ala64Val)</div>
								<span><strong></strong></span>
							</td>
							<td class="vertical-align-center" role="cell"><span>Zanni G, et al., <strong>2015</strong>,
									<a href="https://www.ncbi.nlm.nih.gov/pubmed/26290468" target="_blank"
										rel="noopener noreferrer">PMID: 26290468</a></span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">Male</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell">2</td>
							<td class="vertical-align-center" role="cell">-</td>
							<td class="vertical-align-center" role="cell"><span><span><strong>Calculated:</strong>
										0.3</span></span></td>
							<td class="vertical-align-center" role="cell">No</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>Method 1:</strong> Exome
									sequencing</span><br><strong>Description of genotyping method: </strong>X-chromosome
								exome resequencing</td>
							<td class="vertical-align-center" role="cell">Score</td>
							<td class="vertical-align-center" role="cell"><span><strong>0.5</strong> (0.5)</span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 240px;">The functional data
								published in this study is not supportive, but the phenotypes associated with this
								proband are consistent with X-linked syndromic intellectual disability. Further studies
								are needed to determine the effect of p.Ala64Val. Scored at 0.5 to be consistent with
								the variant reported in Thevenon et al. (2015). </td>
						</tr>
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
								Twin 1</td>
							<td class="vertical-align-center" role="cell">Proband with other variant type with some
								evidence of gene impact</td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">NM_006013.5(RPL10):c.616C&gt;A (p.Leu206Met)</div>
								<span><strong></strong></span>
							</td>
							<td class="vertical-align-center" role="cell"><span>Klauck SM, et al.,
									<strong>2006</strong>, <a href="https://www.ncbi.nlm.nih.gov/pubmed/16940977"
										target="_blank" rel="noopener noreferrer">PMID: 16940977</a></span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">Male</td>
							<td class="vertical-align-center" role="cell"><span><strong>Age of Onset: </strong>1
									Years</span></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>HPO term(s):</strong>
									<ul class="hpo-terms-list">
										<li class="hpo-term-item"><span>Hemiparesis</span></li>
										<li class="hpo-term-item"><span>Absent speech</span></li>
										<li class="hpo-term-item"><span>Aggressive behavior</span></li>
									</ul>
								</span><span><strong>Free text:</strong><br>Left-sided hemiparesis, seizure
									disorder</span></td>
							<td class="vertical-align-center" role="cell">2</td>
							<td class="vertical-align-center" role="cell">-</td>
							<td class="vertical-align-center" role="cell"><span><span><strong>Calculated:</strong>
										0.3</span></span></td>
							<td class="vertical-align-center" role="cell">No</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>Method 1:</strong> Sanger
									sequencing</span><br><strong>Description of genotyping method: </strong>Only
								screened RPL10</td>
							<td class="vertical-align-center" role="cell">Score</td>
							<td class="vertical-align-center" role="cell"><span><strong>0</strong> (0.5)</span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 240px;">Functional data in
								Brooks et al., 2014, PMID: 25316788 contradicts variant's pathogenicity. </td>
						</tr>
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
								Family 277 Proband</td>
							<td class="vertical-align-center" role="cell">Proband with other variant type with some
								evidence of gene impact</td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">NM_006013.5(RPL10):c.639C&gt;G (p.His213Gln)</div>
								<span><strong></strong></span>
							</td>
							<td class="vertical-align-center" role="cell"><span>Klauck SM, et al.,
									<strong>2006</strong>, <a href="https://www.ncbi.nlm.nih.gov/pubmed/16940977"
										target="_blank" rel="noopener noreferrer">PMID: 16940977</a></span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">Male</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>HPO term(s):</strong>
									<ul class="hpo-terms-list">
										<li class="hpo-term-item"><span>Autistic behavior</span></li>
										<li class="hpo-term-item"><span>Severe expressive language delay</span></li>
									</ul>
								</span></td>
							<td class="vertical-align-center" role="cell">2</td>
							<td class="vertical-align-center" role="cell">-</td>
							<td class="vertical-align-center" role="cell"><span><span><strong>Calculated:</strong>
										0.3</span></span></td>
							<td class="vertical-align-center" role="cell">No</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>Method 1:</strong> Sanger
									sequencing</span><br><strong>Description of genotyping method: </strong>Only RPL10
								screened</td>
							<td class="vertical-align-center" role="cell">Score</td>
							<td class="vertical-align-center" role="cell"><span><strong>0</strong> (0.5)</span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 240px;">Brooks et al., 2014,
								PMID: 25316788 functional data contradicts this variant's pathogenicity. This variant is
								also seen at a high frequency in gnomAD: 8/191959, including 1 hemizygous individual.
							</td>
						</tr>
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="min-width: 80px; word-break: normal;">
								Chiocchetti Family Proband</td>
							<td class="vertical-align-center" role="cell">Proband with other variant type with some
								evidence of gene impact</td>
							<td class="vertical-align-center" role="cell">
								<div class="variant-info">NM_006013.5(RPL10):c.639C&gt;G (p.His213Gln)</div>
								<span><strong></strong></span>
							</td>
							<td class="vertical-align-center" role="cell"><span>Chiocchetti A, et al.,
									<strong>2011</strong>, <a href="https://www.ncbi.nlm.nih.gov/pubmed/21567917"
										target="_blank" rel="noopener noreferrer">PMID: 21567917</a></span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 80px;">Male</td>
							<td class="vertical-align-center" role="cell"><span><strong>Age of Report: </strong>15
									Years</span></td>
							<td class="vertical-align-center" role="cell">Not Hispanic or Latino</td>
							<td class="vertical-align-center" role="cell"><span><strong>HPO term(s):</strong>
									<ul class="hpo-terms-list">
										<li class="hpo-term-item"><span>Absent speech</span></li>
										<li class="hpo-term-item"><span>Stereotypy</span></li>
									</ul>
								</span></td>
							<td class="vertical-align-center" role="cell">1</td>
							<td class="vertical-align-center" role="cell">-</td>
							<td class="vertical-align-center" role="cell"><span>-</span></td>
							<td class="vertical-align-center" role="cell">-</td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"></td>
							<td class="vertical-align-center" role="cell"><span><strong>Method 1:</strong> Sanger
									sequencing</span><br><strong>Description of genotyping method: </strong>Direct
								sequencing of RPL10</td>
							<td class="vertical-align-center" role="cell">Score</td>
							<td class="vertical-align-center" role="cell"><span><strong>0</strong> (0.5)</span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 240px;">Variant is not
								scored because it is the same variant identified by Klauk et al. (2006), which was
								contradicted by Brooks et al. (2014) functional data. The proband was only screened for
								the RPL10 gene, and the variant is present in gnomAD: 8/191959, including 1 hemizygous
								individual. </td>
						</tr>-->
					</tbody>
				</table>
			</div>
            @endif
		</div>

        <div class="col-12 pb-4" id='tag_genetic_evidence_case_control'>
			<h3>Scored Genetic Evidence: Case Level (segregation)</h3>
            @if (empty($extrecord->genetic_evidence))
            <div class="alert alert-warning" role="alert">
				No  evidence for a Family with a proband was found.
			</div>
            @else
			<div class="table-responsive">
				<!-- START DEMO DATA -->
				<table id="geclv" role="table" class="table-validity-data table table-bordered table-sm table-striped table-hover">
					<thead>
						<tr role="row">
							<th colspan="1" role="columnheader">Family (Proband) Label</th>
							<th colspan="1" role="columnheader">Reference (PMID)</th>
							<th colspan="1" role="columnheader">Family Ethnicity</th>
							<th colspan="1" role="columnheader">Family Phenotypes</th>
							<th colspan="1" role="columnheader">Family MOI</th>
							<th colspan="1" role="columnheader"># Aff</th>
							<th colspan="1" role="columnheader"># Unaff</th>
							<th colspan="1" role="columnheader">LOD Score</th>
							<th colspan="1" role="columnheader">LOD Score Counted</th>
                            <th colspan="1" role="columnheader">Sequencing Method</th>
						</tr>
					</thead>
					<tbody role="rowgroup">
                    </tbody>
                </table>
            </div>
            @endif
		</div>

		<div class="col-12 pb-4" id='tag_genetic_evidence_case_level_without_proband'>
			<h3>Genetic Evidence: Case Level (family segregation information without proband data or scored proband
				data)</h3>
			<div class="alert alert-warning" role="alert">
				No segregation evidence for a Family without a proband was found.
			</div>
		</div>


		<div class="col-12 pb-4" id='tag_genetic_evidence_case_control'>
			<h3>Genetic Evidence: Case-Control</h3>

			<div class="alert alert-warning" role="alert">
				No case-control genetic evidence was found.
			</div>
		</div>


		<div class="col-12 pb-4" id='tag_experimental_evidence'>
			<h3>Experimental Evidence</h3>
            @if (empty($extrecord->experimental_evidence))
            <div class="alert alert-warning" role="alert">
				No experimental evidence was found.
			</div>
            @else
			<div class="table-responsive">
				<!-- START DEMO DATA -->
				<table id="table" role="table" class="table table-validity-data table-bordered small">
					<thead>
						<tr role="row">
							<th colspan="1" role="columnheader" style="word-break: normal;">Label<span></span></th>
							<th colspan="1" role="columnheader" title="Toggle SortBy">Experimental Category<span></span>
							</th>
							<th colspan="1" role="columnheader" title="Toggle SortBy">Reference<span></span></th>
							<th colspan="1" role="columnheader" style="max-width: 600px;">Explanation<span></span></th>
							<th colspan="1" role="columnheader" title="Toggle SortBy">Score Status<span></span></th>
							<th colspan="1" role="columnheader">Points (default points)<span></span></th>
							<th colspan="1" role="columnheader" style="max-width: 600px;">Reason for Changed
								Score<span></span></th>
						</tr>
					</thead>
					<tbody role="rowgroup">
                        @foreach($extrecord->experimental_evidence as $record)
						<tr role="row">
							<td class="vertical-align-center" role="cell" style="word-break: normal;">
                                {{ $record->evidence[0]->label }}
							</td>
							<td class="vertical-align-center" role="cell"><strong>### Missing category string</strong><span> ###</span>
							</td>
							<td class="vertical-align-center" role="cell"><span>##missing proper author##, et al.,
									<strong>##missing proper year##</strong>, <a href="{{ $record->evidence[0]->source->iri }}"
										target="_blank" rel="noopener noreferrer">PMID: {{ basename($record->evidence[0]->source->iri) }}</a> </span></td>
							<td class="vertical-align-center" role="cell" style="max-width: 600px;">
                                {{ $record->evidence[0]->description }}
                            </td>
							<td class="vertical-align-center" role="cell">
                                Score
                            </td>
							<td class="vertical-align-center" role="cell">
                                <span><strong>{{ $record->score }}</strong> (###)</span>
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
