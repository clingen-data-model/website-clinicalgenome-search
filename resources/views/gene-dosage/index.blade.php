@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center" style="margin-left: -100px; margin-right: -100px">
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
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12">
       		<button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#modalFilter">
				<span class="text-muted font-weight-bold mr-1"><small><i class="glyphicon glyphicon-tasks" style="top: 2px"></i> Advanced Filters:  </small></span><span class="filter-container"><span class="badge action-af-badge">None</span></span>
			</button>
			<span class="text-info font-weight-bold mr-1 float-right action-hidden-columns hidden"><small>Click on <i class="glyphicon glyphicon-th icon-th" style="top: 2px"></i> below to view hidden columns</small></span>

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
@include('modals.bookmark')

@endsection


@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
    <link href="/css/bootstrap-table-sticky-header.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>
<script src="/js/filters.js"></script>
<script src="/js/bookmark.js"></script>

<script>
	/**
	**
	**		Globals
	**
	*/

	var $table = $('#table');
	var showadvanced = true;
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";
    window.scrid = {{ $display_tabs['scrid'] }};
    window.token = "{{ csrf_token() }}";

	window.ajaxOptions = {
		beforeSend: function (xhr) {
			xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
		}
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
		return res
	}

	var choices=['Yes', 'No'];

	var hibin=['<= 10%', '<= 25%', '<= 50%', '<= 75%'];
	var plibin=['< 0.9', '>= 0.9'];
	var plofbin=['< 0.6', '>= 0.6'];

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
			case '< 0.6':
				return value < .6;
			case  '>= 0.6':
				return value >= .6;
			default:
				return true;
		}
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
            stickyHeader: true,
    		stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
            stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
			locale: 'en-US',
			sortName:  "symbol",
			sortOrder: "asc",
      		filterControlVisible: {{ $col_search['col_search'] === null ? "false" : "true" }},
	  		rowStyle:  function(row, index) {
				if (index % 2 === 0) {
     				return {
						classes: 'bt-even-row2 bt-hover-row'
					}
				}
				else {
     				return {
						classes: 'bt-odd-row2 bt-hover-row'
					}
				}
     		},
			columns: [
				{
					title: 'Locus',
					field: 'type',
					formatter: relationFormatter,
					cellStyle: cellFormatter,
					//formatter: nullFormatter,
					//cellStyle: typeFormatter,
					//filterControl: 'input',
					width: 80,
					searchFormatter: false,
					sortable: true,
				},
				{
					title: 'Gene Symbol /<div>Region Name</div>',
					field: 'symbol',
					formatter: symbol2Formatter,
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
					formatter: haplo2Formatter,
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
					formatter: triplo2Formatter,
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
					title: 'OMIM<hr class="mt-1 mb-1 bg-white mr-4">Morbid',
					field: 'omim',
					formatter: omimFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:choices',
					searchFormatter: false,
					sortable: true
				},
				/*{
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="OMIM morbid map"></i></div>Morbid',
					field: 'morbid',
					formatter: morbidFormatter,
					cellStyle: cellFormatter,
					filterControl: 'select',
					filterData: 'var:choices',
					searchFormatter: false,
					sortable: true
				},*/
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
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD v4.0 pLI score.  Values greater than or equal to 0.9 indicate that a gene appears to be intolerant of loss of function variation."></i></div>pLI',
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
					title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD v4.0 predicted loss-of-function.  Values less than 0.6 indicate that a gene appears to be intolerant of loss of function variation."></i></div>LOEUF',
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
          			title: 'Last<div>Evaluated Date</div>',
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

		$table.on('column-switch.bs.table', function (e, name, args) {
			var hidden = $table.bootstrapTable('getHiddenColumns');

			if (hidden.length > 0)
				$('.action-hidden-columns').removeClass('hidden');
			else
				$('.action-hidden-columns').addClass('hidden');
		});

		$table.on('load-success.bs.table', function (e, name, args) {

			$("body").css("cursor", "default");
            window.update_addr();

			if (name.hasOwnProperty('error'))
			{
				swal({
					title: "Load Error",
					text: name.error,
					icon: "error"
				});
			}

			var hidden = $table.bootstrapTable('getHiddenColumns');

			if (hidden.length > 0)
				$('.action-hidden-columns').removeClass('hidden');
			else
				$('.action-hidden-columns').addClass('hidden');

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
    	//$('[data-toggle="popover"]').popover();

		var html = `@include("gene-dosage.panels.search")`;

		$(".fixed-table-toolbar .search .input-group").attr("style","width:800px;");
        $(".fixed-table-toolbar .search .input-group:first").attr("style","float:left; width:200px;");
		//$(".fixed-table-toolbar .search .input-group:first").after(html);

		$("button[name='filterControlSwitch']").attr('title', 'Column Search');
		$("button[aria-label='Columns']").attr('title', 'Show/Hide More Columns');

        $('[data-toggle="popover"]').popover();
		
		region_listener();

		$('.fixed-table-toolbar').on('change', '.toggle-all', function (e, name, args) {

			var hidden = $table.bootstrapTable('getHiddenColumns');

			if (hidden.length > 0)
				$('.action-hidden-columns').removeClass('hidden');
			else
				$('.action-hidden-columns').addClass('hidden');
		});
  	});

</script>

@endsection
