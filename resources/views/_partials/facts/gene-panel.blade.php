<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1">Gene Facts</h4>

					<dl class="dl-horizontal">
						<dt>HGNC Symbol</dt>
						<dd>{{ $record->symbol }} ({{ $record->hgnc_id }})
							<a target='external' href="{{env('CG_URL_GENENAMES_GENE')}}{{ $record->hgnc_id }}" class="badge-info badge pointer">HGNC <i class="fas fa-external-link-alt"></i></a>
							@if($record->entrez_id)
							<a target='external' href="{{env('CG_URL_NCBI_GENE')}}{{ $record->entrez_id }}" class="badge-info badge pointer">Entrez <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->ensembl_id)
							<a target='external' href="{{env('CG_URL_ENSEMBL_GENE')}}{{ $record->ensembl_id }}" class="badge-info badge pointer">Ensembl <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->omim_id)
							<a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $record->omim_id[0] ?? '' }}" class="badge-info badge pointer">OMIM <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->ucsc_id)
							<a target='external' href="{{env('CG_URL_UCSC_GENE')}}{{ $record->ucsc_id ?? '' }}" class="badge-info badge pointer">UCSC <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->uniprot_id)
							<a target='external' href="{{env('CG_URL_UNIPROT_GENE')}}{{ $record->uniprot_id }}" class="badge-info badge pointer">Uniprot <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->symbol)
							<a target='external' href="{{env('CG_URL_REVIEWS_GENE')}}{{ $record->symbol }}" class="badge-info badge pointer">GeneReviews <i class="fas fa-external-link-alt"></i> </a>
							@endif
							@if($record->symbol)
								<a target='external' href="{{env('CG_URL_CLINVAR_GENE')}}{{ $record->symbol }}[gene]" class="badge-info badge pointer">ClinVar <i class="fas fa-external-link-alt"></i> </a>
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
						@if($record->hi)
						<dt>%HI <i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="DECIPHER Haploinsufficiency index"></i></dt>
						<dd>{{ $record->hi }}</dd>
						@endif
						@if($record->pli)
						<dt>pLI <i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD pLI score"></i></dt>
						<dd>{{  $record->pli }}</dd>
						@endif
						@if($record->plof)
						<dt>LOEUF</dt>
						<dd>{{  $record->plof }}</dd>
						@endif
						@if($record->chromosome_band)
						<dt>Cytoband</dt>
						<dd>{{ $record->chromosome_band }}</dd>
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
						@if($record->function)
						<dt>Function</dt>
						<dd>{{ $record->function }}  <i>(Source: <a href="https://www.uniprot.org/uniprot/{{ $record->uniprot_id }}">Uniprot</a>)</i></dd>
						@endif
					</dl>
			</div>
	</div>
</div>