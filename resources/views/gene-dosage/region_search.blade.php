@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-5">
			<table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">  {{  $type }} Search Results</h1>
        		<div>
							<h5 class="mt-1"><span class="">Location: {{ $region }}
								@if ($region == 'INVALID')
									&nbsp;(Original: {{ $original }})
								@endif
							</span></h5>
						</div>
     		 	</td>
        </tr>
      </table>
		</div>

		<div class="col-md-4 mt-3">

			@include('gene-dosage.panels.selector')

		</div>

		<div class="col-md-3">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countRegions text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
					<!--<li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>-->
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
	/*$('.action-show-genes-btn').on('click', function() {
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
	});*/
	$('.action-show-genes').on('click', function() {
		var viz = [];

		if ($(this).hasClass('btn-success'))
		{
			$(this).removeClass('btn-success').addClass('btn-default active');
			$(this).html('<b>Genes: Off</b>');
		}
		else
		{
			viz.push(0);
			$(this).addClass('btn-success').removeClass('btn-default active');
			$(this).html('<b>Genes: On</b>')
		}

		if ($('.action-show-regions').hasClass('btn-success'))
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
	/*$('.action-show-regions-btn').on('click', function() {
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
	});*/
	$('.action-show-regions').on('click', function() {
		var viz = [];
		if ($('.action-show-genes').hasClass('btn-success'))
			viz.push(0);

		if ($(this).hasClass('btn-success'))
		{
			$(this).removeClass('btn-success').addClass('btn-default active');
			$(this).html('<b>Regions: Off</b>');
		}
		else
		{
			viz.push(1);
			$(this).addClass('btn-success').removeClass('btn-default active');
			$(this).html('<b>Regions: On</b>')
		}

		$table.bootstrapTable('filterBy', {
					type: viz
		});
	});


	/**
	 *
	 * Listener for displaying only the known HI
	 *
	 * */
	 $('.action-show-hiknown').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {haplo_assertion: '3 (Sufficient Evidence)'});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-hiknown-text').html('On');

		}
		else
		{
			$table.bootstrapTable('filterBy', {type: [0, 1]});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-hiknown-text').html('Off');

		}
	});


	/**
	*
	* Listener for displaying only the known TS
	*
	* */
	$('.action-show-tsknown').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {triplo_assertion: '3 (Sufficient Evidence)'});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-tsknown-text').html('On');

		}
		else
		{
			$table.bootstrapTable('filterBy', {type: [0, 1]});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-tsknown-text').html('Off');

		}
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
			$('.action-show-new-text').html('On');

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-new-text').html('Off');

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

	var timestamp = new Date().getTime() - (90 * 24 * 60 * 60 * 1000);

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

	var choices=['Yes', 'No'];

	var hibin=['<= 10%', '<= 25%', '<= 50%', '<= 75%'];
	var plibin=['< 0.9', '>= 0.9'];
	var plofbin=['<= 0.2', '<= 0.35', '<= 1'];

	// HI bin
	function checkbin(text, value, field, data)
	{
		switch (text)
		{
			case '<= 10%': 
				return value <= 10;
			case '<= 25%':
				return value <= 25;
			case '<= 50%':
				return value <= 50;
			case '<= 75%':
				return value <= 75;
			default:
				return true;
		}

		/*
		if (text == '<= 10')
			return value <= 10;
		else
			return value > 10;
		*/
	}


	function checkpli(text, value, field, data)
	{
		if (text == '< .9')
			return value < .9;
		else
			return value >= .9;
	}


	function checkplof(text, value, field, data)
	{
		switch (text)
		{
			case '<= 0.2':
				return value <= .2;
			case '<= 0.35':
				return value <= .35;
			case  '<= 1':
				return value <= 1;
			default:
				return true;
		}
		
		//console.log(value);
		/*if (text == '> .35')
			return value > .35;
		else
			return value <= .35;*/
	}

	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
			locale: 'en-US',
			sortName:  "location",
			sortOrder: "asc",
			columns: [
				{
					title: '',
					field: 'relationship',
					formatter: relationFormatter,
					//cellStyle: typeFormatter,
					//align: 'center',
					filterControl: 'select',
					searchFormatter: false,
					sortable: false
				},
				{
					title: 'Gene/Region',
					field: 'symbol',
					formatter: dssymbolFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					width: 200,
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'HGNC/<br>Dosage ID',
					field: 'hgnc',
					formatter: hgncFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: 'Issue',
					field: 'isca',
					//formatter: hgncFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: '{{ $type }}',
					field: 'location',
					formatter: location01Formatter,
					cellStyle: cellFormatter,
					sorter: locationSorter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Haploinsufficiency score"></i></div>HI Score',
					field: 'haplo_assertion',
					formatter: haploFormatter,
					cellStyle: cellFormatter,
					//align: 'center',
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Triplosensitivity score"></i></div>TS Score',
					field: 'triplo_assertion',
					formatter: triploFormatter,
					cellStyle: cellFormatter,
					//align: 'center',
					filterControl: 'select',
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'OMIM',
					field: 'omim',
					formatter: omimFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:choices',
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="OMIM morbid map"></i></div>Morbid',
					field: 'morbid',
					formatter: morbidFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:choices',
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="DECIPHER Haploinsufficiency index.  Values less than 10% predict that a gene is more likely to exhibit haploinsufficiency."></i></div>%HI',
					field: 'hi',
					formatter: hiFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:hibin',
					filterCustomSearch: checkbin,
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD pLI score.  Values greater than or equal to 0.9 indicate that a gene appears to be intolerant of loss of function variation."></i></div>pLI',
					field: 'pli',
					formatter: pliFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:plibin',
					filterCustomSearch: checkpli,
					searchFormatter: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD predicted loss-of-function.  Values less than 0.35 indicate that a gene appears to be intolerant of loss of function variation."></i></div>LOEUF',
					field: 'plof',
					formatter: plofFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:plofbin',
					filterCustomSearch: checkplof,
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
					field: 'workflow',
					title: 'Report',
					formatter: dsreportFormatter,
					cellStyle: cellFormatter,
					//align: 'center',
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
				}

			]
		});

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

		var html = `@include("gene-dosage.panels.search")`;

		$(".fixed-table-toolbar .search .input-group").attr("style","width:800px;");
        $(".fixed-table-toolbar .search .input-group:first").attr("style","float:left; width:200px;");
		$(".fixed-table-toolbar .search .input-group:first").after(html);

		$("button[name='filterControlSwitch']").attr('title', 'Column Search');
		$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

		region_listener();

  	});

</script>

@endsection
