<!-- Triplosensitivity Details -->
<div class="row" id="report_details_triplosensitivity">
  <div class="col-sm-12">
    <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>
    <h3 class=" h4 mb-1 border-bottom-2">Triplosensitivity Score Details</h3>
    <div class="text-muted small">
      Triplosensitivity (TI) Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.
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
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Comments:</div>
      <div class="col-sm-9 border-left-4"><span class="">At this time there is no evidence to support the triplosensitivity of this gene.</span></div>
    </div>
  </div>
</div>
            