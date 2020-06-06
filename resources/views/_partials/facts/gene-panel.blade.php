<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1">Gene Facts</h4>

					<dl class="dl-horizontal">
						<dt>HGNC Symbol</dt>
						<dd>{{ $record->symbol }} ({{ $record->hgnc_id }})
							<a target='external' href="{{env('CG_URL_GENENAMES_GENE')}}{{ $record->hgnc_id }}" class="badge-info badge pointer">HGNC <i class="fas fa-external-link-alt"></i></a>
							@if($record->entrez_id)
							<a target='external' href="{{env('CG_URL_NCBI_GENE')}}{{ $record->entrez_id }}" class="badge-info badge pointer">NCBI <i class="fas fa-external-link-alt"></i> </a>
							@endif
						</dd>
						@if($record->name)
						<dt>HGNC Name</dt>
						<dd>{{ $record->name }}</dd>
						@endif
						@if($record->locus_group)
						<dt>Gene type</dt>
						<dd>{{ $record->locus_group }}</dd>
						@endif
						@if($record->locus_type)
						<dt>Locus type</dt>
						<dd>{{ $record->locus_type }}</dd>
						@endif
						@if($record->prev_symbols)
						<dt>Previous symbols</dt>
						<dd>{{ $record->prev_symbols ?? 'oops' }}</dd>
						@endif
						@if($record->alias_symbols)
						<dt>Alias symbols</dt>
						<dd>{{ $record->alias_symbols ?? 'oops'}}</dd>
						@endif
						{{-- <dt>Genomic Coordinate</dt>
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
					--}}
						@if($record->chromosome_band)
						<dt>Chromosomal location</dt>
						<dd>{{ $record->chromosome_band }}</dd>
						@endif
						{{-- <dt>Function</dt>
						<dd>Involved in double-strand break repair and/or homologous recombination. Binds RAD51 and potentiates recombinational DNA repair by promoting assembly of RAD51 onto single-stranded DNA (ssDNA). Acts by targeting RAD51 to ssDNA over double-stranded DNA, enabling RAD51 to displace … Source: UniProt</dd> --}}
					</dl>
			</div>
	</div>
</div>