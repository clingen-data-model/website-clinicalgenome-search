<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1">Gene Facts <span class=" ml-2" style="font-size:11px"><i class="fas fa-question-circle"></i> <a href='https://clinicalgenome.org/tools/clingen-website-faq/attribution/' class="_blank">External Data Attribution</a></span></h4>

					<dl class="dl-horizontal">
						<dt>HGNC Symbol</dt>
						<dd>{{ $record->symbol }} ({{ $record->hgnc_id }})
							<a target='external' href="{{env('CG_URL_GENENAMES_GENE')}}{{ $record->hgnc_id }}" class="badge-info badge pointer ml-2">HGNC <i class="fas fa-external-link-alt"></i></a>
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
								<a target='external' href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->symbol }}[gene]" class="badge-info badge pointer">ClinVar <i class="fas fa-external-link-alt"></i> </a>
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
						@if(isset($record->hi))
						<dt>%HI <i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="DECIPHER Haploinsufficiency index"></i></dt>
						<dd>{{ $record->hi }}<a href="https://journals.plos.org/plosgenetics/article?id=10.1371/journal.pgen.1001154" class="ml-3">(Read more about the DECIPHER Haploinsufficiency Index)</a></dd>
						@endif
						@if(isset($record->pli))
						<dt>pLI <i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD pLI score"></i></dt>
						<dd>{{  $record->pli }}<a href="http://gnomad.broadinstitute.org/faq#constraint" class="ml-3">(Read more about gnomAD pLI score)</a></dd>
						@endif
						@if($record->plof)
						<dt>LOEUF <i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD predicted loss-of-function"></i></dt>
						<dd>{{  $record->plof }}<a href="http://gnomad.broadinstitute.org/faq#constraint" class="ml-3">(Read more about gnomAD LOEUF score)</a></dd>
						@endif
						@if($record->chromosome_band)
						<dt>Cytoband</dt>
						<dd>{{ $record->chromosome_band }}</dd>
						@endif
						<dt>Genomic Coordinates</dt>
						<dd>
						<table>
							<tr>
								<td class='pr-2'><u>GRCh37/hg19:</u></td>
								<td class='pr-3'>{{ $record->grch37 }}</td>
								<td>
								<a href="{{ $record->formatNcbi($record->grch37, $record->GRCh37_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
								<a href="{{ $record->formatEnsembl($record->grch37) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
								<a href="{{ $record->formatUcsc19($record->grch37) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
								</td>
							</tr>
							<tr>
								<td class="pr-2"><u>GRCh38/hg38:</u></td>
								<td class='pr-3'>{{  $record->grch38 }}</td>
								<td>
								<a href="{{ $record->formatNcbi($record->grch38, $record->GRCh38_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
								<a href="{{ $record->formatEnsembl($record->grch38) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
								<a href="{{ $record->formatUcsc38($record->grch38) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
								</td>
							</tr>
						</table>
						</dd>
						@if (!empty($record->mane_select))
						<dt>MANE Select Transcript</dt>
						<dd>
							{!!  $record->displayManeString('select') !!}<a href="https://www.ncbi.nlm.nih.gov/refseq/MANE" class="ml-3">(Read more about MANE Select)</a>
						</dd>
						@endif
						@if (!empty($record->mane_plus))
						<dt>MANE Plus Clinical Transcript(s)</dt>
						<dd>
							<table>
							@foreach ($record->mane_plus as $plus)
								<tr>
								<td >{!!  $record->displayManeString('plus', $plus) !!}<a href="https://www.ncbi.nlm.nih.gov/refseq/MANE" class="ml-3">(Read more about MANE Plus Clinical)</a>
								</td>
								</tr>
							@endforeach
							</table>
						</dd>
						@endif
						@if($record->function)
						<dt>Function</dt>
						<dd>{{ $record->function }}  <i>(Source: <a href="https://www.uniprot.org/uniprot/{{ $record->uniprot_id }}">Uniprot</a>)</i></dd>
						@endif
					</dl>
			</div>
	</div>
</div>