@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  {{  $type }} Location Search Results</h1>
      	{{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
		</div>

		<div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="small line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="small line-tight text-center pl-3 pr-3"><span class="countRegions text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
					<li class="small line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
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


@section('script_js')

<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>

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
	var showadvanced = true;
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
	$('.action-show-genes').on('click', function() {
		var viz = [];

		if ($(this).hasClass('fa-toggle-on'))
		{
			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-genes-text').html('Off')
		}
		else
		{
			viz.push(0);
			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
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
	$('.action-show-regions').on('click', function() {
		var viz = [];

		if ($('.action-show-genes').hasClass('fa-toggle-on'))
			viz.push(0);

		if ($(this).hasClass('fa-toggle-on'))
		{
			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-regions-text').html('Off')
		}
		else
		{
			viz.push(1);
			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
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
					title: 'Gene/Region',
					field: 'symbol',
					formatter: symbolFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					width: 250,
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
					title: 'Location',
					field: 'location',
					formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Haploinsufficiency',
					field: 'haplo_assertion',
					formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'Triplosensitity',
					field: 'triplo_assertion',
					formatter: triploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'OMIM',
					field: 'omimlimk',
					formatter: omimFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				/*{
					title: 'Morbid',
					field: 'morbid',
					formatter: morbidFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},*/
				/*{
					title: '%HI',
					field: 'hi',
					formatter: hiFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},*/
				{
					title: 'pLI',
					field: 'pli',
					formatter: pliFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				/*{
					field: 'date',
					title: 'Reviewed',
					formatter: reportFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortName: 'rawdate',
					sortable: true,
				}*/
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
					title: 'ISCA ID',
					field: 'isca',
					formatter: iscaFormatter,
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

  	});

</script>

@endsection
