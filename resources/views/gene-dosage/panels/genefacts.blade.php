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
        <dd>{{ $record->genetype }}</dd>
        <dt>Locus type</dt>
        <dd>gene with protein product</dd>
        <dt>Previous symbols</dt>
        <dd>{{ $record->prev_symbols }}</dd>
        <dt>Alias symbols</dt>
        <dd>{{ $record->alias_symbols }}</dd>
        <dt>Chromosomal location</dt>
        <dd>
          {{ $record->chromosome_band }} <a href="/genes/{{ $record->hgnc_id }}" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
          <!--<div class="mt-2 mb-4">
            <div id="ideogram"> </div>
          </div>-->
        </dd>
        <dt>Genomic Coordinate</dt>
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
        <dt>Function</dt>
        <dd>Involved in double-strand break repair and/or homologous recombination. Binds RAD51 and potentiates recombinational DNA repair by promoting assembly of RAD51 onto single-stranded DNA (ssDNA). Acts by targeting RAD51 to ssDNA over double-stranded DNA, enabling RAD51 to displace … Source: UniProt</dd>
      </dl>
    </div>
  </div>
</div>