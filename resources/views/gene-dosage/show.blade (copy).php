@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h1 class=" display-4 ">{{ $record->symbol }} 
              <a class="btn btn-default btn-sm pl-2 pr-2 pt-1 pb-1 text-10px" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="far fa-caret-square-down"></i> Gene Facts 
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
                            {{ $record->chromosome_band }} <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a>
                            <div class="mt-2 mb-4">
                              <div id="ideogram"> </div>
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
            <hr class="mt-5 mb-5" id="report_details_browser" />

            <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>
            <h4>Genomic View</h4>
            <div id="g_view">
              @if (!empty($record->GRCh38_loc))
              <div class="seqview_head">
                <span class="assembly_select">Select assembly: </span>
                <select id="g_view_menu" name="seqviewermenu">
                   <option name="accession" value="?embedded=true&appname=isca_public&assm_context=GCF_000001405.25&id={{ $record->seqID }}&from={{ $record->sv_start }}&to={{ $record->sv_stop }}&tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.2,rendering:Default]" selected="selected">GRCh37/hg19 {{ $record->loc }}</option>
                     <option name="accession" value="?embedded=true&appname=isca_public&assm_context=GCF_000001405.36&id={{ $record->GRCh38_seqID }}&from={{ $record->GRCh38_sv_start }}&to={{ $record->GRCh38_sv_stop }}&tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.3,rendering:Default]">GRCh38/hg38 {{ $record->GRCh38_loc }}</option>
                </select>
                <span class="seqviewer-comment">
                  <span> (<a href="https://www.ncbi.nlm.nih.gov/nuccore/{{ $record->seqID }}" target="_blank">{{ $record->seqID }}</a>) </span>
                  <span class="hide"> (<a href="https://www.ncbi.nlm.nih.gov/nuccore/{{ $record->GRCh38_seqID }}" target="_blank">{{ $record->GRCh38_seqID }}</a>)</span>
                 </span>
              </div>
              <div id="sv1" class="SeqViewerApp">
                <a href='?embedded=true&appname=isca_public&assm_context=GCF_000001405.25&id={{ $record->seqID }}&from={{ $record->sv_start }}&to={{ $record->sv_stop }}&tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.2,rendering:Default]'></a>
              </div> 
              @else
              <p id="gen_head"><span class="strong">GRCh37/hg19</span> {{ $record->loc }} (<a href="https://www.ncbi.nlm.nih.gov/nuccore/{{ $record->seqID }}" target="_blank">{{ $record->seqID }})</a></p>
              <div id="sv1" class="SeqViewerApp">
              <a href='?embedded=true&appname=isca_public&assm_context=GCF_000001405.25&id={{ $record->seqID }}&from={{ $record->sv_start }}&to={{ $record->sv_stop }}&tracks=[key:sequence_track][key:gene_model_track,name:NCBI,display_name:NCBI%20Genes,annots:Unnamed,Options:ShowAll][key:dbvar_track,name:dbVar_nstd45,display_name:ISCA%20Curated%20Regions,annots:NA000002000.2,rendering:Default]'></a>
              </div>
              @endif
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
<script type="text/javascript" src="https://www.ncbi.nlm.nih.gov/core/jig/1.14.8/js/jig.min.js"></script>
<script type="text/javascript" src="https://www.ncbi.nlm.nih.gov/projects/sviewer/js/sviewer.js" id="autoload"></script>

<!--IDEOGRAM-->
<script type="text/javascript" src="/js/ideo.js"> </script>

<link rel="stylesheet" type="text/css" href="https://www.ncbi.nlm.nih.gov/projects/ideogram/3.0/css/ideo.css" />
<link href="https://www.ncbi.nlm.nih.gov/projects/genome/NCBI_core/header.css" rel="stylesheet" type="text/css" />

<!-- the below link collides hard with the rest of the site.  It is included so someone can merge later -->
<!-- <link href="/css/clingen.css" rel="stylesheet" type="text/css" /> -->

<link href="/css/footnote.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
	jQuery(function(){
		jQuery("#tabs").ncbitabs();
	});
</script>

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#loss_sc").click(function(){
      jQuery("#tabs").ncbitabs("option", "active", 1);
    });
    jQuery("#gain_sc").click(function(){
      jQuery("#tabs").ncbitabs("option", "active", 2);
    });
  
          jQuery("#last_footnote").load("footnote.html", function() {
              jQuery.ui.jig.scan(this, {
                                        'widgets': ['ncbihelpwindow'] 
              });      
             
              var dt = new Date();
              var time_str = dt.getFullYear() + "-" + (dt.getMonth()+1) + "-" + dt.getDate() +
                               "T" + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds() +
                               "-04:00";
    
              var link_str = "/sites/ehelp?&Ncbi_App=" +
                               jQuery('meta[name=ncbi_app]').attr('content') +
                               "&Page=" +
                               jQuery('meta[name=ncbi_pdid]').attr('content') +
                               "&Time=" + time_str +
                               "&Data=+PageURL:+" +
                               window.location.href +  
                               ";";  
              jQuery('#help-desk-link').attr('href',link_str);
          }); 
  
          var appIndex=0;
          jQuery("#g_view_menu").on('change', function(eventObj) {
              var target = eventObj.currentTarget;
              if (target.selectedIndex != appIndex)  {
                  var appIndex = target.selectedIndex;
                  var accession = target.value;
                  var svhref = accession.substring(1, accession.length)
                  var svApp = SeqView.App.findAppByIndex(0);
                  if (svApp) {
                      svApp.reload(svhref);
                  }
  
                  jQuery(".seqviewer-comment > span").each(function (index, el) {
                      if ( index != appIndex) {
                          jQuery(el).addClass('hide');
                      }
                      else {
                          jQuery(el).removeClass('hide');
                      }
                  });
              }
          });
              
  });
  </script>

<script type="text/javascript">
//{% if chrom|length > 0 %}
jQuery(document).ready(function() {
	try{
	var shapes = new IDEO.ShapeCollection({
		defaultFill: "#0000EE", //blue
		defaultBorder: "#0000EE", //blue
		defaultShape: "triangle"
	});
	var annot = new IDEO.Annotation({
		data: [
			{chrom: '{{ $record->chromosome }}', start: {{ $record->start_location }}, stop: {{ $record->stop_location }}},
		],
		width: 10 
	});
	var bandLabelConfig = {
		mode:IDEO.LabelOrientation.Lengthwise,
		color: "#333333",
		margin: 10,
		offset: 10,
		includeChrom:true
	};
	var ideo = new IDEO.Ideogram({
		taxid: 9606,
		rep: IDEO.CytoRep({
			assm: "GRCh37"
		}),
		chroms: ['{{ $record->chromosome }}'],
		orientation: IDEO.IdeogramOrientation.Horizontal,
		bandLabels: bandLabelConfig,
		ideowidth: 25,
		ideoheight: 450,
		labelPosition: IDEO.LabelPosition.None,
		container: "#ideogram",
		align: "top",
		annotations: [annot],
		shapes: shapes
	});

	if(ideo.getStatus() != IDEO.IdeogramStatus.OK && ideo.getStatus() != IDEO.IdeogramStatus.WARNING){
		var errors = ideo.getErrors();
		var div = jQuery(ideogram.container);
		if(div[0]){
			div[0].innerHTML = "IDEOGRAM ERRORS: <br />";
		for(var n=0; n < errors.length; n++){
			div[0].innerHTML += errors[n] + "<br/>";
			div.css({
				color: "red"
			});
		}
	}
}
} catch(e){
	alert(e + (e.lineNumber? "\nline: " + e.lineNumber : "")+(e.fileName? "\nfile: " + e.fileName : " "));
}
});
//{% endif %}
</script>

@endsection