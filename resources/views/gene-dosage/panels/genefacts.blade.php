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
        <dt>HGNC Name</dt>
        <dd>{{ $record->name }}</dd>
        <dt>Gene type</dt>
        <dd>{{ $record->genetype }}</dd>
        <dt>Locus type</dt>
        <dd>{{ $record->locus_type }}</dd>
        <dt>Previous symbols</dt>
        <dd>{{ $record->prev_symbols }}</dd>
        <dt>Alias symbols</dt>
        <dd>{{ $record->alias_symbols }}</dd>
        @if($record->hi)
        <dt>%HI</dt>
        <dd>{{ $record->hi }}<a href="https://journals.plos.org/plosgenetics/article?id=10.1371/journal.pgen.1001154" class="ml-3">(Read more about the DECIPHER Haploinsufficiency Index)</a></dd>
        @endif
        @if(isset($record->pli))
        <dt>pLI</dt>
        <dd>{{  $record->pli }}<a href="http://gnomad.broadinstitute.org/faq#constraint" class="ml-3">(Read more about gnomAD pLI score)</a></dd>
        @endif
        @if($record->plof)
        <dt>LOEUF</dt>
        <dd>{{  $record->plof }}<a href="http://gnomad.broadinstitute.org/faq#constraint" class="ml-3">(Read more about gnomAD LOEUF score)</a></dd>
        @endif
        <dt>Cytoband</dt>
        <dd>
          {{ $record->chromosome_band }} <a href="/genes/{{ $record->hgnc_id }}" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
        </dd>
        <dt>Genomic Coordinates</dt>
        <dd>
          <table>
            <tr>
                <td>GRCh37/hg19</td>
                <td>{{ $record->GRCh37_position }}
                  <a href="{{ $record->formatNcbi($record->GRCh37_position, $record->GRCh37_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
                  <a href="{{ $record->formatEnsembl($record->GRCh37_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
                  <a href="{{ $record->formatUcsc19($record->GRCh37_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
                </td>
            </tr>  
            <tr>
                <td class="pr-3">GRCh38/hg38</td>
                <td>{{  $record->GRCh38_position }}
                  <a href="{{ $record->formatNcbi($record->GRCh38_position, $record->GRCh38_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
                  <a href="{{ $record->formatEnsembl($record->GRCh38_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
                  <a href="{{ $record->formatUcsc38($record->GRCh38_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
                </td>
            </tr> 
          </table>
        </dd>
        @if($record->function)
        <dt>Function</dt>
        <dd>{{ $record->function }}  <i>(Source: <a href="https://www.uniprot.org/uniprot/{{ $record->uniprot_id }}">Uniprot</a></i>)</dd>
        @endif
      </dl>
    </div>
  </div>
</div>