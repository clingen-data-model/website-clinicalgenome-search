@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">


      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Curations of Recurrent CNVs</h1>
          </td>
        </tr>
			</table>

		</div>

		<div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12 light-arrows">
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
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/js-xlsx/xlsx.core.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/filter-control/bootstrap-table-filter-control.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

	var $table = $('#table');
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";


	function responseHandler(res) {
		//$('#gene-count').html(res.total);
		$('.countCurations').html(res.total);
		$('.countGenes').html(res.total);
		$('.countHaplo').html(res.nhaplo);
		$('.countTriplo').html(res.ntriplo);

    	return res
  	}


  	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
		locale: 'en-US',
		sortName:  "location",
		sortOrder: "asc",
		columns: [
		{
			title: 'Region Name',
			field: 'name',
			formatter: regionFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			searchFormatter: false,
			sortable: true
		},
		{
			title: 'Location on GRCh37',
			field: 'location',
			sortable: true,
			filterControl: 'input',
			formatter: cnvlocationFormatter,
			cellStyle: cellFormatter,
			sorter: locationSorter,
			searchFormatter: false
			//visible: false
        },
        {
			title: '<div><i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Haploinsufficiency Score"></i></div>HI Score',
			field: 'haplo_assertion',
			filterControl: 'select',
			formatter: cnvhaploFormatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        },
		{
			title: '<div><i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Triplosensitivity Score"></i></div>TS Score',
			field: 'cnvtriplo_assertion',
			filterControl: 'select',
			formatter: cnvtriploFormatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        },
		{
			field: 'date',
      title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			formatter: cnvreportFormatter,
			sortName: 'rawdate'
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
