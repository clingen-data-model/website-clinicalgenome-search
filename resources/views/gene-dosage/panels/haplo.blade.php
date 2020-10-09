<!-- Haploinsufficiency Details -->
<div class="row" id="report_details_haploinsufficiency">
  <div class="col-sm-12 pt-3">
    <h3 class="h4 mb-1 border-bottom-2">Haploinsufficiency Score Details</h3>
    <div class="text-muted small">
      Haploinsufficiency (HI) Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.
    </div>
  </div>
  <div class="col-sm-12">
    <div class="row pb-3 pt-2">
      <div class="col-sm-3 text-muted text-right bold">Haploinsufficiency Score:</div>
      <div class="col-sm-9 border-left-4 bold">{{ $record->haplo_score }}</div>
    </div>
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Strength:</div>
      <div class="col-sm-9 border-left-4"><span class="bold">{{ $record->haplo_assertion }}</span> (Disclaimer)</div>
    </div>
    @if (!empty($record->loss_pheno_omim))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Haploinsufficiency Phenotype:</div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          <!-- loss_pheno_omim -->
          @foreach($record->loss_pheno_omim as $item)
            <li><a href="https://omim.org/entry/{{ $item['id'] }}">{{ $item['titles'] }}</a></li>
          @endforeach
          </ul>
      </div>
    </div>
    @endif
    @if (!empty($record->loss_pmids))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Published Evidence:<div></div></div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          @foreach ($record->loss_pmids as $loss_pmid)
          <li class="mb-3 pb-3 border-bottom-1">
            <a href="" class="">[PUBMED: {{ $loss_pmid['pmid'] }}]</a>
            <div class="small  summariesShow mt-1" id="collapsesummary1">{{ $loss_pmid['desc'] ?? '' }}</div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif
    @if (!empty($record->loss_comments))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Comments:</div>
      <div class="col-sm-9 border-left-4"><span class="">{{ $record->loss_comments }}</span></div>
    </div>
    @endif
  </div>
</div>
