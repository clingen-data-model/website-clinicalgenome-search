
<!-- Report Summary -->
<div class="row mt-2 ">
  <div class="col-sm-12">
    <div class="card">
      <div class="card-body bg-light">
        <h3 class="h4 mt-0 mb-1 border-bottom-2 border-info">{{ $record->symbol }} Dosage Sensitivity Summary</h3>
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
            <div class="small"><a href="#report_details_haploinsufficiency">Read full report...</a></div>
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
            GRCh37/hg19 chrX: 118,708,430-118,718,392 <a href="#report_details_browser" class="badge-info badge pointer"><i class="fal fa-browser"></i> View in browser</a><br />
            GRCh38/hg38 chrX: 119,574,467-119,584,429 <a href="#report_details_browser" class="badge-info badge pointer"><i class="fal fa-browser"></i> View in browser</a><br />
            <a class="small " role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Gene Facts... </a>
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Location Relationship:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            Contained
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Morbid:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            OMIM:114480, OMIM:155255, OMIM:176807, OMIM:194070, OMIM:605724, OMIM:612555, OMIM:613029, OMIM:613347
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">%HI index:</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-danger">{{ $record->hi }}</div>
            Read more about Haploinsufficiency Index
          </div>
        </div>
        <div class="row pb-2 pt-2">
          <div class="col-sm-3 text-right">Loss Intolerance (pLI):</div>
          <div class="col-sm-9 border-left-4 border-info bold">
            <div class="text-success">{{ $record->pli }}</div>
            Read more about Loss of Function (LoF) mutation score.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
