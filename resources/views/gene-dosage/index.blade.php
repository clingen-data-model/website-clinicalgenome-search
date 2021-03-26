@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-5">
			<table class="mt-3 mb-2">
				<tr>
					<td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
					<td class="pl-2">
						<h1 class="h2 p-0 m-0"> Dosage Sensitivity</h1>
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
						<li class="text-stats line-tight text-center pl-3 pr-3"><span
								class="countCurations text-18px"><i
									class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations
						</li>
						<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i
									class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes
						</li>
						<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countRegions text-18px"><i
									class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions
						</li>
						{{-- <li class="text-stats line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-download text-18px"></i><br />Download<br />Options
					</a>
						<ul class="dropdown-menu dropdown-menu-left">
							<li><a href="{{ route('dosage-download') }}">Summary Data (CSV)</a></li>
						<li><a href="{{ route('dosage-ftp') }}">Additional Data (FTP)</a></li>
					</ul>
					</li>
					<li class="text-stats line-tight text-center pl-3 pr-3">
						<div class="btn-group p-0 m-0" style="display: block"><a
								class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true"
								aria-expanded="false"><i
									class="glyphicon glyphicon-list-alt text-18px text-muted"></i><br />ACMG<br />CNV
							</a>
							<ul class="dropdown-menu dropdown-menu-left">
								<li><a href="{{ route('dosage-acmg59') }}">ACMG 59 Genes</a></li>
								<li><a href="{{ route('dosage-cnv') }}">Recurrent CNVs</a></li>
							</ul>
					</li> --}}
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12">
       		<button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#modalFilter">
				<span class="text-muted font-weight-bold mr-1"><small><i class="glyphicon glyphicon-tasks" style="top: 2px"></i> Advanced Filters:  </small></span><span class="filter-container"><span class="badge action-af-badge">None</span></span>
			</button>
		</div>
		<div class="col-md-12 light-arrows dark-table">

			@include('_partials.genetable', ['expand' => true])

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

	window.ajaxOptions = {
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('laravel_token'))
    }
  }

	/**
	 *
	 * Listener for displaying only genes
	 *
	 * */
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
			viz.push(3);
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
	$('.action-show-regions').on('click', function() {
		var viz = [];
		if ($('.action-show-genes').hasClass('btn-success'))
		{
			viz.push(0);
			viz.push(3);
		}

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

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-hi-badge bg-primary mr-1">Known HI</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			$table.bootstrapTable('filterBy', {type: [0, 1, 3]});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-hiknown-text').html('Off');

			$('.action-hi-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

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

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-ts-badge bg-primary mr-1">Known TS</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			$table.bootstrapTable('filterBy', {type: [0, 1, 3]});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-tsknown-text').html('Off');

			$('.action-ts-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

		}
	});


	/**
	 *
	 * Listener for displaying only protein coding genes
	 *
	 * */
	$('.action-show-protein').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {locus: 'protein-coding gene'});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-protein-text').html('On');

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-pc-badge bg-primary mr-1">Protein Coding</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			$table.bootstrapTable('filterBy', {type: [0, 1, 3]});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-protein-text').html('Off');

			$('.action-pc-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}
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

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-new-badge bg-primary mr-1">Score Change 365</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-new-text').html('Off');

			$('.action-new-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

		}

		// 'filterAlgorithm': function (){ return true;}
	});


	/**
	 *
	 * Listener for displaying only the recent reviewed items
	 *
	 * */
	 $('.action-show-recent').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {type: [0, 1, 3]}, {'filterAlgorithm': monthFilter});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-recent-text').html('On');

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-nine-badge bg-primary">Recently Reviewed</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});
			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-recent-text').html('Off');

			$('.action-nine-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

		}
	});

	var timestamp = new Date().getTime() - (90 * 24 * 60 * 60 * 1000);		// 90 days

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
		$('.countCurations').html(res.ncurations);
		$('.countGenes').html(res.ngenes);
		$('.countRegions').html(res.nregions);
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

	var tripChoices=[
                '0 (No Evidence)',
                '1 (Little Evidence)',
                '2 (Emerging Evidence)',
                '3 (Sufficient Evidence)',
                '30 (Autosomal Recessive)',
                '40 (Dosage Sensitivity Unlikely)',
                'Not Yet Evaluated',
  ];
	var hapChoices=[
                '0 (No Evidence)',
                '1 (Little Evidence)',
                '2 (Emerging Evidence)',
                '3 (Sufficient Evidence)',
                '30 (Autosomal Recessive)',
                '40 (Dosage Sensitivity Unlikely)',
                'Not Yet Evaluated',
  ];


	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
			locale: 'en-US',
			sortName:  "symbol",
			sortOrder: "asc",
      		filterControlVisible: {{ $col_search['col_search'] === null ? "false" : "true" }},
	  		rowStyle:  function(row, index) {
				if (index % 2 === 0) {
     				return { 
						classes: 'bt-even-row bt-hover-row'
					}
				}
				else {
     				return { 
						classes: 'bt-odd-row bt-hover-row'
					}
				}			
     		},
			columns: [
				{
					title: '',
					field: 'type',
					formatter: nullFormatter,
					cellStyle: typeFormatter,
					//filterControl: 'input',
					searchFormatter: false
					//sortable: true
				},
				{
					title: 'Gene/Region',
					field: 'symbol',
					formatter: symbolFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					width: 190,
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'HGNC/<br>Dosage ID',
					field: 'hgnc_id',
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
					visible: false,
					sortable: true
				},
				{
					title: 'GRCh37',
					field: 'grch37',
					formatter: locationFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					sorter: locationSorter,
					searchFormatter: false,
					sortable: true
				},
				{
					title: 'GRCh38',
					field: 'grch38',
					formatter: location38Formatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					sorter: locationSorter,
					searchFormatter: false,
					visible: false,
					sortable: true
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Haploinsufficiency score"></i></div>HI Score',
					field: 'haplo_assertion',
					formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
          filterData: 'var:hapChoices',
          filterDefault: "{{ $col_search['col_search'] === "haplo" ? $col_search['col_search_val'] : "" }}",
					sortable: true
				},
				{
					title: 'Haplo Disease',
					field: 'haplo_disease',
					//formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: 'Haplo Disease ID',
					field: 'haplo_disease_id',
					//formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Triplosensitivity score"></i></div>TS Score',
					field: 'triplo_assertion',
					formatter: triploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					searchFormatter: false,
          filterData: 'var:tripChoices',
          filterDefault: "{{ $col_search['col_search'] === "triplo" ? $col_search['col_search_val'] : "" }}",
					sortable: true
				},
				{
					title: 'Triplo Disease',
					field: 'triplo_disease',
					//formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
				},
				{
					title: 'Triplo Disease ID',
					field: 'triplo_disease_id',
					//formatter: haploFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortable: true,
					visible: false
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
				{
					field: 'date',
					//title: 'Last Eval.',
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
					formatter: reportFormatter,
					cellStyle: cellFormatter,
					filterControl: 'input',
					searchFormatter: false,
					sortName: 'rawdate',
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

		var html = `@include("gene-dosage.panels.selector")`;

		$table.on('post-body.bs.table', function (e, name, args) {

			$('[data-toggle="tooltip"]').tooltip();

		})


		/*$table.on('click-cell.bs.table', function (event, field, value, row, $obj) {
			//console.log(e);
			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();

		});*/

		$table.on('expand-row.bs.table', function (e, index, row, $obj) {

			$obj.attr('colspan',12);
		
			var t = $obj.closest('tr');

			var stripe = t.prev().hasClass('bt-even-row');

			t.addClass('dosage-row-bottom');

			if (stripe)
				t.addClass('bt-even-row');
			else
				t.addClass('bt-odd-row');

			t.prev().addClass('dosage-row-top');

			$obj.load( "/api/dosage/expand/" + row.hgnc_id );

			return false;
		})


		$table.on('collapse-row.bs.table', function (e, index, row, $obj) {

			$obj.closest('tr').prev().removeClass('dosage-row-top');

			return false;
		});

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
		$("button[aria-label='Columns']").attr('title', 'Show/Hide More Columns');

		region_listener();

  	});

</script>

@endsection