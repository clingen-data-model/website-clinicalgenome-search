@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">

    <!-- Header -->
    <div class="col-md-7">
			<h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  Dosage Sensitivity Report</h1>
    </div>
    
    <div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="small line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Score</li>
            <li class="small line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Score</li>
            <li class="small line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
					</ul>
				</div>
			</div>
    </div>

    <div class="col-md-12">
      Assess whether there is evidence to support that <b>{{ $record->symbol }}</b> gene is dosage sensitive and should be targeted on a cytogenomic array.
    </div>
    

    <div class="col-md-12">
      <h1 class=" display-4 ">{{ $record->symbol }} 
        <a class="btn btn-default btn-sm pl-2 pr-2 pt-1 pb-1 text-10px" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          <i class="far fa-caret-square-down"></i> Gene Facts 
        </a>
      </h1>
    </div>

    <div class="col-md-12">
    
      <!-- include the genefacts panel -->
      @include('gene-dosage.panels.genefacts')

      <!-- Show the viewer -->
      @include('gene-dosage.panels.viewer')

      <hr class="mt-5 mb-5" />
      <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>

      <!-- Show Report Summary -->
      @include('gene-dosage.panels.summary')

      <hr class="mt-5 mb-5" />
      <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>

      <!-- Show Haploinsufficiency Details -->
      @include('gene-dosage.panels.haplo')

      <hr class="mt-5 mb-5" id="report_details_browser" />
      <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>

      <!-- Show Triplosensitivity Details -->
      @include('gene-dosage.panels.triplo')
      
      <hr class="mt-5 mb-5" />
      <a href="#top" class="text-10px pull-right text-muted"><i class="fas fa-arrow-to-top"></i> Top</a>

      <h4>Dosage Sensitivity Disclaimers</h4>
      <p><strong>NOTE:</strong> The loss-of-function and triplosensitivity ratings for genes on the X chromosome are made in the context of a male genome to account for the effects of hemizygous duplications or nullizygous deletions. In contrast, disruption of some genes on the X chromosome causes male lethality and the ratings of dosage sensitivity instead take into account the phenotype in female individuals. Factors that may affect the severity of phenotypes associated with X-linked disorders include the presence of variable copies of the X chromosome (i.e. 47,XXY or 45,X) and skewed X-inactivation in females.</p>

      <p><strong>NOTE:</strong> The loss of function score should be used to evaluate deletions, and the triplosensitivity score should be used to evaluated duplications.  CNVs encompassing more than one gene must be evaluated in their totality (e.g. overall size, gain vs. loss, presence of other genes, etc). The rating of a single gene within the CNV should not necessarily be the only criteria by which one defines a clinical interpretation. Individual interpretations must take into account the phenotype described for the patient as well as issues of penetrance and expressivity of the disorder. ACMG has published guidelines for the characterization of postnatal CNVs, and these recommendations should be utilized (Genet Med (2011)13: 680-685). Exceptions to these interpretive correlations will occur, and clinical judgment should always be exercised.</p>
    
    </div>
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