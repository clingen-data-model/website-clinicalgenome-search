@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></td>
					<td class="pl-2"><h1 class="h2 p-0 m-0">  {{  $type }} Location Search Results</h1>
						<h5 class="mt-2"><span class="ml-7">Location: {{ $region }}
				@if ($region == 'INVALID')
					&nbsp;(Original: {{ $original }})
				@endif
			</span></h5>
          </td>
        </tr>
      </table>

		</div>

		<div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countRegions text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
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

@section('modals')

	@include('modals.filter')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

	var $table = $('#table');
	var showadvanced = false;
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";

	/* no longer used
	var score_assertion_strings = {
		'0': 'No Evidence',
		'1': 'Minimal Evidence',
		'2': 'Moderate Evidence',
		'3': 'Sufficient Evidence',
		'30': 'Autosomal Recessive',
		'40': 'Dosage Sensitivity Unlikely'
	};*/


	/**
	 *
	 * Listener for displaying only genes
	 *
	 * */
	$('.action-show-genes-btn').on('click', function() {
		var viz = [];

		if ($(this).find('.action-show-genes').hasClass('fa-toggle-on'))
		{
			$(this).find('.action-show-genes').removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-genes-text').html('Off')
		}
		else
		{
			viz.push(0);
			$(this).find('.action-show-genes').removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-genes-text').html('On')
		}

		if ($('.action-show-regions').hasClass('fa-toggle-on'))
			viz.push(1);

		$table.bootstrapTable('filterBy', {
				type: viz
		});
	});


	/**
	 *
	 * Listener for displaying only regions
	 *
	 * */
	$('.action-show-regions-btn').on('click', function() {
		var viz = [];
		if ($('.action-show-genes').hasClass('fa-toggle-on'))
			viz.push(0);

		if ($(this).find('.action-show-regions').hasClass('fa-toggle-on'))
		{
			$(this).find('.action-show-regions').removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-regions-text').html('Off')
		}
		else
		{
			viz.push(1);
			$(this).find('.action-show-regions').removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-regions-text').html('On')
		}

		$table.bootstrapTable('filterBy', {
					type: viz
		});
	});


	/**
	 *
	 * Listener for displaying only the recent score changes
	 *
	 * */
	$('.action-show-new').on('click', function() {
		var viz = [];

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {thr: 1, hhr: 1}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-regions-text').html('On');

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-regions-text').html('Off');

		}

	});

	/**
	 *
	 * Listener for displaying only the recent reviewed items
	 *
	 * */
	$('.action-show-recent').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {type: [0, 1]}, {'filterAlgorithm': monthFilter});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-recent-text').html('On');

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});
			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-recent-text').html('Off');

		}
	});

	var timestamp = new Date().getTime() - (12 * 30 * 24 * 60 * 60 * 1000);

	function monthFilter(rows, filters)
	{
		return Date.parse(rows.rawdate) > timestamp;
	}

	/**
	 *
	 * Table response handler for updating page counters after data load
	 *
	 * */
	function responseHandler(res) {

		// update the counters
		$('.countGenes').html(res.gene_count);
		$('.countRegions').html(res.region_count);
		//$('.countTriplo').html(res.ntriplo);
		return res
	}


	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
			locale: 'en-US',
			columns: [
				{
					title: 'Gene',
					field: 'symbol',
					formatter: symbolFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					width: 200,
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'HGNC',
					field: 'hgnc',
					formatter: hgncFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: 'Cytoband',
					field: 'location',
					//formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Chromosome',
					field: 'chr',
					//formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Start',
					field: 'start',
					//formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Stop',
					field: 'stop',
					//formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Relationship',
					field: 'relationship',
					//formatter: pliFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					field: 'status',
					title: 'Activity',
					formatter: badgeFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					field: 'date_last_curated',
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
					//formatter: badgeFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				}

			]
		});

		$table.on('load-error.bs.table', function (e, name, args) {
			console.log("error fired");

			$("body").css("cursor", "default");

			swal({
				title: "Load Error",
				text: "The system could not retrieve data from GeneGraph",
				icon: "error"
			});
		})

		$table.on('load-success.bs.table', function (e, name, args) {
			console.log("success fired");

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

		//$(".fixed-table-toolbar .search .input-group").attr("style","width:800px;");
        //$(".fixed-table-toolbar .search .input-group:first").attr("style","float:left; width:200px;");
		//$(".fixed-table-toolbar .search .input-group:first").after(html);

		//region_listener();

  	});

</script>

@endsection
