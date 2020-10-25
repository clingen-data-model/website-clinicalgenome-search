@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">

    <!-- Header -->
    <div class="col-md-9">
			<h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  {{ $record->symbol }}
        <a class="btn btn-default btn-sm pl-2 pr-2 pt-1 pb-1 text-10px" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          <i class="far fa-caret-square-down"></i> Region Facts
        </a>
      </h1>
    </div>

    <div class="col-md-3">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px">{{ $record->haplo_score }}</span><br />Haplo<br />Score</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px">{{ $record->triplo_score }}</span><br />Triplo<br />Score</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
					</ul>
				</div>
			</div>
    </div>

    <div class="col-md-12">

      <!-- include the genefacts panel -->
      @include('gene-dosage.panels.regionfacts')

      <!-- Show Report Summary -->
      @include('gene-dosage.panels.summary')

      <!-- Show Haploinsufficiency Details -->
      @include('gene-dosage.panels.haplo')

      <!-- Show Triplosensitivity Details -->
      @include('gene-dosage.panels.triplo')

      <!-- include the genefacts panel -->
      @include('gene-dosage.panels.viewer')

      <div id="popover_content_wrapper" style="display: none">
      The loss of function score should be used to evaluate deletions, and the triplosensitivity score should be used to evaluated duplications.  CNVs encompassing more than one gene must be evaluated in their totality (e.g. overall size, gain vs. loss, presence of other genes, etc). The rating of a single gene within the CNV should not necessarily be the only criteria by which one defines a clinical interpretation. Individual interpretations must take into account the phenotype described for the patient as well as issues of penetrance and expressivity of the disorder. ACMG has published guidelines for the characterization of postnatal CNVs, and these recommendations should be utilized <a href="https://www.ncbi.nlm.nih.gov/pubmed/21681106" >(Genet Med (2011)13: 680-685)</a>. Exceptions to these interpretive correlations will occur, and clinical judgment should always be exercised.
      </div>

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

<style>
  .popover {
    max-width: 65%;
  }
  .popover-title {
    font-weight: bold;
  }
  .data_pre { white-space: pre-line;}
</style>

<script>

$('[data-toggle="tooltip"]').tooltip();
$('[data-toggle="popover"]').popover({
      html : true,
      content: function() {
        return $('#popover_content_wrapper').html();
      }
    });

</script>

<script type="text/javascript" src="https://www.ncbi.nlm.nih.gov/core/jig/1.14.8/js/jig.min.js"></script>
<script type="text/javascript" src="https://www.ncbi.nlm.nih.gov/projects/sviewer/js/sviewer.js" id="autoload"></script>

<!--IDEOGRAM-->
<script type="text/javascript" src="/js/ideo.js"> </script>

<link rel="stylesheet" type="text/css" href="https://www.ncbi.nlm.nih.gov/projects/ideogram/3.0/css/ideo.css" />
<link href="/js/ncbiheader.css" rel="stylesheet" type="text/css" />

<!-- the below link collides hard with the rest of the site.  It is included so someone can merge later -->
<!-- <link href="/css/clingen.css" rel="stylesheet" type="text/css" /> -->

<link href="/css/footnote.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(document).ready(function() {

  $("#tabs").ncbitabs();

  $("#loss_sc").click(function(){
      $("#tabs").ncbitabs("option", "active", 1);
  });

  $("#gain_sc").click(function(){
      $("#tabs").ncbitabs("option", "active", 2);
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


//{% if chrom|length > 0 %}
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

</script>

@endsection