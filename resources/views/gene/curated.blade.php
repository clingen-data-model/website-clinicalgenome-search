@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">


    <div class="col-md-5">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><span id="gene-count"></span><img src="/images/clinicalValidity-on.png" width="40" height="40"><img src="/images/clinicalActionability-on.png" width="40" height="40" style="margin-left: -6px"><img src="/images/dosageSensitivity-on.png" width="40" height="40" style="margin-left: -6px"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Curated Genes </h1>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-md-7">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique Curated<br />Genes</li>
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countValidity text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Gene-Disease<br />Validity Genes</li>
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countActionability text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Actionability<br />Genes</li>
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDosage text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Dosage<br />Sensitivity Genes</li>
            </ul>
				</div>
			</div>
    </div>

      <div class="col-md-12">
        @include('_partials.genetable')
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

<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.css">
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

  var $table = $('#table');
  var lightstyle = true;

  function responseHandler(res) {
    $('.countGenes').html(res.total);
    $('.countValidity').html(res.nvalid);
    $('.countActionability').html(res.naction);
    $('.countDosage').html(res.ndosage);

    return res
  }

  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      sortName:  "symbol",
			sortOrder: "asc",
      columns: [
        [{
          title: 'Gene',
          field: 'symbol',
          rowspan: 2,
          formatter: geneFormatter,
          filterControl: 'input',
          sortable: true
        },
        {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?" aria-describedby="popover"> <img src="/images/clinicalValidity-on.png" width="40" height="40"><br> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
			    align: 'center'
        },
		    {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/clinical-actionability/" data-content="How does this genetic diagnosis impact medical management?"> <img src="/images/clinicalActionability-on.png" width="40" height="40"><br> Clinical Actionability <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
          align: 'center',
        },
        {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?" aria-describedby="popover954864"> <img src="/images/dosageSensitivity-on.png" width="40" height="40"><br> Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
          colspan: 2,
          align: 'center',
        }],
		    [{
          title: 'Clinical Validity<br />Classifications',
          field: 'has_validity',
          formatter: hasvalidityFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: true,
          sortable: true
        },
        {
          title: 'Evidence-Based<br />Summary',
          field: 'has_actionability',
          formatter: hasactionabilityFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: true,
          sortable: true
        },
        {
          title: 'Haploinsufficiency<br />Score',
          field: 'has_dosage_haplo',
          formatter: hashaploFormatter,
          align: 'center',
          filterControl: 'select',
          searchFormatter: false,
          sortable: true
        },
        {
          title: 'Triplosensitivity<br />Score',
          field: 'has_dosage_triplo',
          formatter: hastriploFormatter,
          align: 'center',
          filterControl: 'select',
          searchFormatter: false,
          sortable: true
        }]
      ]
    })

    $table.on('load-error.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");

      swal({
            title: "Load Error",
            text: "The system could not retrieve data from GeneGraph",
            icon: "error"
      });
    })

    $table.on('load-success.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");

      if (name.hasOwnProperty('error'))
      {
        swal({
            title: "Load Error",
            text: name.error,
            icon: "error"
        });
      }
    })

    $table.on('post-body.bs.table', function (e, name, args) {
			console.log("post body fired");

			$('[data-toggle="tooltip"]').tooltip();
		})

  }

$(function() {

  // Set cursor to busy prior to table init
  $("body").css("cursor", "progress");

  // initialize the table and load the data
  inittable();

  // make some mods to the search input field
  var search = $('.fixed-table-toolbar .search input');
  search.attr('placeholder', 'Search in table');

  $( ".fixed-table-toolbar" ).show();
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

});


</script>
@endsection
