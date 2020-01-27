<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1">Gene Facts</h4>

					<dl class="dl-horizontal">
						<dt>HGNC Symbol</dt>
						<dd>{{ $record->symbol }} ({{ $record->hgnc_id }})</dd>
						<dt>HGNC Name</dt>
						<dd>{{ $record->name }}</dd>
						<dt>Gene type</dt>
						<dd>{{ $record->locus_group }}</dd>
						<dt>Locus type</dt>
						<dd>{{ $record->locus_type }}</dd>
						<dt>Previous symbols</dt>
						<dd>{{ $record->prev_symbols_string }}</dd>
						<dt>Alias symbols</dt>
						<dd>{{ $record->alias_symbols_string }}</dd>
						<dt>Genomic Coordinate</dt>
						<dd>
							<table>
									<tr>
											<td>GRCh37/hg19</td>
											<td>chr17: 41,196,312-41,277,500
													<a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
													<a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
											</td>
									</tr>
									<tr>
											<td class="pr-3">GRCh38/hg38</td>
											<td>chr17: 43,044,295-43,125,483
													<a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
													<a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
											</td>
									</tr>
							</table>
						</dd>
						<dt>Chromosomal location</dt>
						<dd>{{ $record->location }} <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> </dd>
						<dt>Function</dt>
						<dd>Involved in double-strand break repair and/or homologous recombination. Binds RAD51 and potentiates recombinational DNA repair by promoting assembly of RAD51 onto single-stranded DNA (ssDNA). Acts by targeting RAD51 to ssDNA over double-stranded DNA, enabling RAD51 to displace … Source: UniProt</dd>
					</dl>
			</div>
	</div>
</div>