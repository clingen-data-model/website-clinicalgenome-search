@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h1 class=" display-4 ">BRCA2 
              <a class="btn btn-default btn-sm pl-2 pr-2 pt-1 pb-1 text-10px" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="far fa-chevron-circle-down"></i> Gene Facts 
              </a>
          </h1>
        </div>
        <div class="col-md-10">
            
            <div class="collapse" id="collapseExample">
                <div class="row">
                    <div class="col-sm-12  mt-0 pt-0 small">
                        <h4 class="border-bottom-1">Gene Facts</h4>

                        <dl class="dl-horizontal">
                          <dt>HGNC Symbol</dt>
                          <dd>BRCA2 (HGNC:1101)</dd>
                          <dt>HGNC Name</dt>
                          <dd>BRCA2 DNA repair associated</dd>
                          <dt>Gene type</dt>
                          <dd>protein-coding</dd>
                          <dt>Locus type</dt>
                          <dd>gene with protein product</dd>
                          <dt>Previous symbols</dt>
                          <dd>FANCD1, FACD, FANCD</dd>
                          <dt>Alias symbols</dt>
                          <dd>FAD, FAD1, BRCC2, XRCC11</dd>
                          <dt>Chromosomal location</dt>
                          <dd>
                            13q13.1 <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
                            <div class="mt-2 mb-4">
                              <img src="/brand/img/chromosome.png">
                            </div>
                          </dd>
                          <dt>Genomic Coordinate</dt>
                          <dd>
                            <table>
                                <tr>
                                    <td>GRCh37/hg19</td>
                                    <td>chr17: 41,196,312-41,277,500
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> 
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
                                    </td>
                                </tr>  
                                <tr>
                                    <td class="pr-3">GRCh38/hg38</td>
                                    <td>chr17: 43,044,295-43,125,483
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> 
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
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

            <h2 class="h2 mb-0 text-primary">Dosage Sensitivity Report</h2>
            Assess whether there is evidence to support that BRCA2 gene is dosage sensitive and should be targeted on a cytogenomic array.
            <div class="row mt-2 ">
              <div class="col-sm-12">
                  <div class="card">
                      <div class="card-body bg-light">
                          <h3 class="h4 mt-0 mb-1 border-bottom-2 border-info">BRCA2 Dosage Sensitivity Summary</h3>
                          <div class="row pb-2 pt-2">
                            <div class="col-sm-3 text-right">Haploinsufficiency:</div>
                            <div class="col-sm-9 border-left-4 border-info bold">
                              Sufficient evidence for dosage pathogenicity (3)
                              <div class="small"><a href="#report_details_haploinsufficiency">Read full report...</a></div>
                            </div>
                          </div>
                          <div class="row pb-2 pt-2">
                            <div class="col-sm-3 text-right">Triplosensitivity:</div>
                            <div class="col-sm-9 border-left-4 border-info bold">
                              No evidence for dosage pathogenicity (0)
                              <div class="small"><a href="#report_details_haploinsufficiency">Read full report...</a></div>
                            </div>
                          </div>
                          <div class="row pb-2 pt-2">
                            <div class="col-sm-3 text-right">Last Evaluated:</div>
                            <div class="col-sm-9 border-left-4 border-info bold">
                              05/22/2019<br />
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
                              <div class="text-danger">13.30</div>
                              Read more about Haploinsufficiency Index
                            </div>
                          </div>
                          <div class="row pb-2 pt-2">
                            <div class="col-sm-3 text-right">Loss Intolerance (pLI):</div>
                            <div class="col-sm-9 border-left-4 border-info bold">
                              <div class="text-success">0.00</div>
                              Read more about Loss of Function (LoF) mutation score.
                            </div>
                          </div>
                      </div>
                  </div>
                
              </div>
            </div>
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
                  <div class="col-sm-9 border-left-4 bold">3</div>
                </div>
                <div class="row pb-3"> 
                  <div class="col-sm-3 text-muted text-right bold">Evidence Strength:</div>
                  <div class="col-sm-9 border-left-4"><span class="bold">Sufficient evidence for dosage pathogenicity</span> (Disclaimer)</div>
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
            <hr />
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
                  <div class="col-sm-9 border-left-4 bold">0</div>
                </div>
                <div class="row pb-3"> 
                  <div class="col-sm-3 text-muted text-right bold">Evidence Strength:</div>
                  <div class="col-sm-9 border-left-4"><span class="bold">No evidence for dosage pathogenicity</span> (Disclaimer)</div>
                </div>
                <div class="row pb-3"> 
                  <div class="col-sm-3 text-muted text-right bold">Evidence Comments:</div>
                  <div class="col-sm-9 border-left-4"><span class="">At this time there is no evidence to support the triplosensitivity of this gene.</span></div>
                </div>
              </div>
            </div>
            <hr class="mt-5 mb-5" id="report_details_browser" />

            <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>
            <h4>Genomic View</h4>
            Select assembly: <select class="input" id="g_view_menu" name="seqviewermenu">
             <option name="accession" value="?embedded=true&amp;appname=isca_public&amp;assm_context=GCF_000001405.25&amp;id=NC_000023.10&amp;from=118707433.7&amp;to=118719388.3&amp;tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.2,rendering:Default]" selected="selected">GRCh37/hg19 chrX: 118,708,430-118,718,392</option>
               <option name="accession" value="?embedded=true&amp;appname=isca_public&amp;assm_context=GCF_000001405.36&amp;id=NC_000023.11&amp;from=119573470.7&amp;to=119585425.3&amp;tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.3,rendering:Default]">GRCh38/hg38 chrX: 119,574,467-119,584,429</option>
          </select> (NC_000023.10)
            <div class="mt-2 mb-4">
                              <img src="/brand/img/browser.png" class="img-fluid">
                            </div>

            <hr class="mt-5 mb-5" />
            <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>
            <h4>Dosage Sensitivity Disclaimers</h4>
            <p><strong>NOTE:</strong> The loss-of-function and triplosensitivity ratings for genes on the X chromosome are made in the context of a male genome to account for the effects of hemizygous duplications or nullizygous deletions. In contrast, disruption of some genes on the X chromosome causes male lethality and the ratings of dosage sensitivity instead take into account the phenotype in female individuals. Factors that may affect the severity of phenotypes associated with X-linked disorders include the presence of variable copies of the X chromosome (i.e. 47,XXY or 45,X) and skewed X-inactivation in females.</p>

            <p><strong>NOTE:</strong> The loss of function score should be used to evaluate deletions, and the triplosensitivity score should be used to evaluated duplications. 
CNVs encompassing more than one gene must be evaluated in their totality (e.g. overall size, gain vs. loss, presence of other genes, etc). 
The rating of a single gene within the CNV should not necessarily be the only criteria by which one defines a clinical interpretation. 
Individual interpretations must take into account the phenotype described for the patient as well as issues of penetrance and expressivity of the disorder. 
ACMG has published guidelines for the characterization of postnatal CNVs, and these recommendations should be utilized (Genet Med (2011)13: 680-685). 
Exceptions to these interpretive correlations will occur, and clinical judgment should always be exercised.</p>
            {{-- <div class="card">
                <div class="card-body">
                    
                </div>
            </div> --}}
        </div>
        @include('_partials.nav_side.gene',['navActive' => "dosage"])
    </div>
</div>
@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('script_js')

@endsection