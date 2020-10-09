
<!-- Report Summary -->
<div class="row mt-2 ">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body bg-light">
        <h3 class="h4 mt-0 mb-1 border-bottom-2 border-info">{{ $record->symbol }} Dosage Sensitivity Summary</h3>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">DCI Issue:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{  $record->key }}
            <div class="small"><a href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym={{ $record->symbol }}">View legacy report...</a></div>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Haploinsufficiency:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{ $record->haplo_assertion }} ({{ $record->haplo_score }})
            <div class="small"><a href="#report_details_haploinsufficiency">Read full report...</a></div>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Triplosensitivity:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{  $record->triplo_assertion }} ({{ $record->triplo_score }})
            <div class="small"><a href="#report_details_triplosensitivity">Read full report...</a></div>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Last Evaluated:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{ $record->date }}<br />
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Genomic Coordinates:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            {{ $record->cytoband }}<br />
            GRCh37/hg19 {{ $record->GRCh37_position }}
            <a href="{{ $record->formatNcbi($record->GRCh37_position, $record->GRCh37_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
            <a href="{{ $record->formatEnsembl($record->GRCh37_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
            <a href="{{ $record->formatUcsc19($record->GRCh37_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
            <br />
            GRCh38/hg38 {{ $record->GRCh38_position }}
            <a href="{{ $record->formatNcbi($record->GRCh38_position, $record->GRCh38_seqid) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   NCBI</a>
            <a href="{{ $record->formatEnsembl($record->GRCh38_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   Ensembl</a>
            <a href="{{ $record->formatUcsc38($record->GRCh38_position) }}" class="badge-info badge pointer"><i class="fas fa-external-link-alt"></i>   UCSC</a>
            <br />
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Location Relationship:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            Contained
          </div>
        </div>
        <!--
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Morbid:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            OMIM:114480, OMIM:155255, OMIM:176807, OMIM:194070, OMIM:605724, OMIM:612555, OMIM:613029, OMIM:613347
          </div>
        </div>-->
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">%HI index:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-danger">{{ $record->hi }}</div>
            <a href="http://gnomad.broadinstitute.org/faq">Read more about Haploinsufficiency Index</a>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Loss Intolerance (pLI):</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-success">{{ $record->pli }}</div>
            <a href="http://gnomad.broadinstitute.org/faq">Read more about Loss of Function (LoF) mutation score.</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
