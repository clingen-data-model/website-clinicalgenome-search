<!-- Haploinsufficiency Details -->
<div class="row" id="report_details_haploinsufficiency">
  <div class="col-sm-12 pt-3">
    <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>
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
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">CURATION(s):</div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          <li><a href="">BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2 (OMIM:612555)</li>
        </ul>
      </div>
    </div> 
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Evidence Comments:</div>
      <div class="col-sm-9 border-left-4"><span class="">Loss of function mutations in BRCA1 (nonsense, frameshift, splice site, and exonic deletions) as well as whole gene deletions of BRCA1 have been associated with cancer development (Genereviews and PMIDs: 21989022, 17661172, and 22762150). The penetrance associated with BRCA1 mutations is still an active area of study; however, patients with pathogenic BRCA1 mutations are thought to have an increased lifetime risk of developing breast cancer (50-80% in females, 1-2% in males), ovarian cancer (24-40%), prostate cancer (up to 30%), and pancreatic cancer (1-7%) (Genereviews Table 3).</span></div>
    </div>
    <div class="row pb-3"> 
      <div class="col-sm-3 text-muted text-right bold">Published Evidence:<div><button class="badge-light badge small"role="button" data-toggle="collapse" href=".summariesShow" aria-expanded="false" aria-controls="collapsesummary1">Show summaries</button></div></div>
      <div class="col-sm-9 border-left-4">
        <ul class="list-unstyled">
          <li class="mb-3 pb-3 border-bottom-1">
            <a href="" class="">UBE2A, which encodes a ubiquitin-conjugating enzyme, is mutated in a novel X-linked mental retardation syndrome. [PUBMED: 16909393]</a> <button class="badge-light badge small" role="button" data-toggle="collapse" href="#collapsesummary1" aria-expanded="false" aria-controls="collapsesummary1">Show summary...</button>
            <div class="small  summariesShow" id="collapsesummary1">A report of a family with syndromic X-linked intellectual disability where affected males had a nonsense mutation in the 3' end of UBE2A. The female carriers were phenotypically normal and had skewed X-inactivation. A normal non-carrier sister had random X-inactivation. Functional studies were not provided and it is not known if this mutation leads to a protein-degradation.</div>
          </li>
          <li class="mb-3 pb-3 border-bottom-1">
            <a href="" class="">Which encodes a ubiquitin-conjugating enzyme, is mutated in a novel X-linked mental retardation syndrome. [PUBMED: 16909393]</a> <button class="badge-light badge small" role="button" data-toggle="collapse" href="#collapsesummary2" aria-expanded="false" aria-controls="collapsesummary1">Show summary...</button>
            <div class="small summariesShow" id="collapsesummary2">A report of a family with syndromic X-linked intellectual disability where affected males had a nonsense mutation in the 3' end of UBE2A. The female carriers were phenotypically normal and had skewed X-inactivation. A normal non-carrier sister had random X-inactivation. Functional studies were not provided and it is not known if this mutation leads to a protein-degradation.</div>
          </li>
          <li class="mb-3 pb-3 border-bottom-1">
            <a href="" class="">Encodes a ubiquitin-conjugating enzyme, is mutated in a novel X-linked mental retardation syndrome. [PUBMED: 16909393]</a> <button class="badge-light badge small" role="button" data-toggle="collapse" href="#collapsesummary3" aria-expanded="false" aria-controls="collapsesummary1">Show summary...</button>
            <div class="small summariesShow" id="collapsesummary3">A report of a family with syndromic X-linked intellectual disability where affected males had a nonsense mutation in the 3' end of UBE2A. The female carriers were phenotypically normal and had skewed X-inactivation. A normal non-carrier sister had random X-inactivation. Functional studies were not provided and it is not known if this mutation leads to a protein-degradation.</div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
