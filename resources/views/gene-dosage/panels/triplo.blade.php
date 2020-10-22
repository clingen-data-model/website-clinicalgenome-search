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
      <div class="col-sm-9 border-left-4"><span class="bold">{{ $record->triplo_assertion }}</span> <a data-toggle="popover" title="DISCLAIMER" data-placement="bottom" data-trigger="hover"> (Disclaimer)</a>
      </div>
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
            <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $gain_pmid['pmid'] }}" class="">PUBMED: {{ $gain_pmid['pmid'] }}</a>
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
      <div class="col-sm-9 border-left-4"><span class="data_pre">{{ $record->gain_comments }}</span></div>
    </div>
    @endif
    @if (!empty($record->cytoband) && strtoupper(substr($record->cytoband, 0, 1)) == 'X')
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">NOTE:<div></div></div>
      <div class="col-sm-9 border-left-4 bg-light p-3">
        <p>The loss-of-function and triplosensitivity ratings for genes on the X chromosome are made in the context of a male genome to account for the effects of hemizygous duplications or nullizygous deletions. In contrast, disruption of some genes on the X chromosome causes male lethality and the ratings of dosage sensitivity instead take into account the phenotype in female individuals. Factors that may affect the severity of phenotypes associated with X-linked disorders include the presence of variable copies of the X chromosome (i.e. 47,XXY or 45,X) and skewed X-inactivation in females.</p>
      </div>
    </div>
    @endif
  </div>
</div>
