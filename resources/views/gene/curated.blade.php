@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">


    <div class="col-md-5">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><span id="gene-count"></span><img src="/images/clinicalValidity-on.png" width="40" height="40"><img src="/images/dosageSensitivity-on.png" width="40" height="40" style="margin-left: -6px"><img src="/images/clinicalActionability-on.png" width="40" height="40" style="margin-left: -6px"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0"> All Curated Genes </h1>
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
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDosage text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Dosage<br />Sensitivity Genes</li>
              <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countActionability text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Actionability<br />Genes</li>
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

@section('script_css')
	<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.css">
	<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
        {
          title: 'Gene',
          field: 'symbol',
          formatter: geneFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'HGNC ID',
          field: 'hgnc_id',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true,
          visible: false
        },
        /*{
          title: '<span data-toggle="tooltip" data-placement="top" title="Can variation in this gene cause disease?" aria-describedby="tooltip"> <img src="/images/clinicalValidity-on.png" width="40" height="40"><i class="fas fa-info-circle text-muted"></i></span>',
			    align: 'center'
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?" aria-describedby="tooltip"> <img src="/images/dosageSensitivity-on.png" width="40" height="40"><i class="fas fa-info-circle text-muted"></i></span>',
          align: 'center',
        },
		    {
          title: '<span data-toggle="tooltip" data-placement="top" title="How does this genetic diagnosis impact medical management?"> <img src="/images/clinicalActionability-on.png" width="40" height="40"><i class="fas fa-info-circle text-muted"></i></span>',
          align: 'center',
        },*/
		    {
          title: '<span data-toggle="tooltip" data-placement="top" title="Can variation in this gene cause disease?" aria-describedby="tooltip"><div><img src="/images/clinicalValidity-on.png" width="40" height="40"></div>Gene Disease Validity <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
         // title: 'Gene Disease Validity',
          field: 'has_validity',
          formatter: hasvalidityFormatter,
          align: 'center',
          filterControl: 'select',
          searchFormatter: false,
          sortable: true
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?" aria-describedby="tooltip"><div><img src="/images/dosageSensitivity-on.png" width="40" height="40"></div>Dosage Sensitivity <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Dosage Sensitivity',
          field: 'has_dosage',
          formatter: hasdosageFormatter,
          align: 'center',
          filterControl: 'select',
          searchFormatter: false,
          sortable: true
        },
        // {
        //   title: 'Haploinsufficiency<br />Score',
        //   field: 'has_dosage_haplo',
        //   formatter: hashaploFormatter,
        //   align: 'center',
        //   filterControl: 'select',
        //   searchFormatter: false,
        //   sortable: true
        // },
        // {
        //   title: 'Triplosensitivity<br />Score',
        //   field: 'has_dosage_triplo',
        //   formatter: hastriploFormatter,
        //   align: 'center',
        //   filterControl: 'select',
        //   searchFormatter: false,
        //   sortable: true
        // },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="How does this genetic diagnosis impact medical management?"><div><img src="/images/clinicalActionability-on.png" width="40" height="40"></div>Clinical Actionability <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Clinical Actionability',
          field: 'has_actionability',
          formatter: hasactionabilityFormatter,
          align: 'center',
          filterControl: 'select',
          searchFormatter: false,
          sortable: true
        }
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

  $("button[name='filterControlSwitch']").attr('title', 'Column Search');
	$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

});


</script>
@endsection