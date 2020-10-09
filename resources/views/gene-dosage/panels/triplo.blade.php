<!-- Triplosensitivity Details -->
<div class="row" id="report_details_triplosensitivity">
  <div class="col-sm-12 pt-3">
    <h3 class="h4 mb-1 border-bottom-2">Triplosensitivity Score Details</h3>
    <div class="text-muted small">
      Triplosensitivity (HI) Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.
    </div>
  </div>
  <div class="col-sm-12">
    <div class="row pb-3 pt-2">
      <div class="col-sm-3 text-muted text-right bold">Triplosensitivity Score:</div>
      <div class="col-sm-9 border-left-4 bold">{{ $record->triplo_score }}</div>
    </div>
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Strength:</div>
      <div class="col-sm-9 border-left-4"><span class="bold">{{ $record->triplo_assertion }}</span> (Disclaimer)</div>
    </div>
    @if (!empty($record->gain_pheno_omim))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Triplosensitivity Phenotype:</div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          <!-- loss_pheno_omim -->
          @foreach($record->gain_pheno_omim as $item)
            <li><a href="https://omim.org/entry/{{ $item['id'] }}">{{ $item['titles'] }}</a></li>
          @endforeach        </ul>
      </div>
    </div>
    @endif
    @if (!empty($record->gain_pmids))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Published Evidence:<div></div></div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          @foreach ($record->gain_pmids as $gain_pmid)
          <li class="mb-3 pb-3 border-bottom-1">
            <a href="" class="">[PUBMED: {{ $gain_pmid['pmid'] }}]</a>
            <div class="small  summariesShow mt-1" id="collapsesummary1">{{ $gain_pmid['desc'] ?? '' }}</div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif
    @if (!empty($record->gain_comments))
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Comments:</div>
      <div class="col-sm-9 border-left-4"><span class="">{{ $record->gain_comments }}</span></div>
    </div>
    @endif
  </div>
</div>
