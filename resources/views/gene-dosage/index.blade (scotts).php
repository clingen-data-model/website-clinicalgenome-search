@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">
			<h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  Dosage Sensitivity</h1>
      	{{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
		</div>

		<!--<div class="col-md-2 pt-3">
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="showgenes" checked>
				Show Genes
			  </div>
			  <div class="form-check form-check-inline">
				<input class="form-check-input" type="checkbox" id="showregions" checked>
				Show Regions
			  </div>
		</div>-->

		<div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-download text-18px"></i><br />Download<br />Options
					</a>
						<ul class="dropdown-menu dropdown-menu-left">
							<li><a href="{{ route('dosage-download') }}">Summary Data (CSV)</a></li>
							<li><a href="{{ route('dosage-ftp') }}">Additional Data (FTP)</a></li>
						</ul>
					</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-list-alt text-18px text-muted"></i><br />More<br />Data
					</a>
						<ul class="dropdown-menu dropdown-menu-left">
							<li><a href="{{ route('dosage-cnv') }}">Recurrent CNVs</a></li>
							<li><a href="{{ route('dosage-acmg59') }}">ACMG 59 Genes</a></li>
						</ul>
					</li>
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

<style>
	.fixed-table-toolbar .search-input {
	  min-width: 300px;
	}
	.swal-overlay--show-modal, .swal-modal {
    animation: none !important;
	}
	/* REVIEW
	.fixed-table-container .global_table_cell {
    font-weight: 500;
    font-size: 14px;
	padding: 21px 12px 19px !important;
	} */
	.header_class {
    	font-size: 14px;
	}
.bootstrap-table .fixed-table-container .table thead th .sortable {
    cursor: pointer;
    background-position: left;
    background-repeat: no-repeat;
    padding-left: 20px !important;
}
.bootstrap-table .fixed-table-container .table thead th .both {
	background-image: url("/images/sort-both-20.png") !important;
}
.bootstrap-table .fixed-table-container .table thead th .asc {
	background-image: url("/images/sort-up-20.png") !important;
}

.bootstrap-table .fixed-table-container .table thead th .desc {
	background-image: url("/images/sort-dn-20.png") !important;

}
  </style>

<script>

	var $table = $('#table')
	var selections = []
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";

	var score_assertion_strings = {
          '0': 'No Evidence',
          '1': 'Minimal Evidence',
          '2': 'Moderate Evidence',
          '3': 'Sufficient Evidence',
		  //'30': 'Gene Associated with Autosomal Recessive Phenotype',
		  '30': 'Autosomal Recessive',
          '40': 'Dosage Sensitivity Unlikely'
	};

	function table_buttons ()
	{
		return {
			btnUsersAdd: {
				text: 'Filters',
				icon: 'glyphicon-ok',
				event: function () {
					$('#modalFilter').modal('toggle');
					//alert('Do some stuff to e.g. search all users which has logged in the last week')
				},
				attributes: {
					title: 'Advanced Filters'
				}
			}
    	}
	}


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

	$('.action-show-new').on('click', function() {
		var viz = [];

		if ($(this).hasClass('fa-toggle-off'))
		{
			$table.bootstrapTable('filterBy', {thr: 1, hhr: 1}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');

		}
		else
		{
			$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');

		}

		// 'filterAlgorithm': function (){ return true;}
	});


	function responseHandler(res) {
		//$('#gene-count').html(res.total);
		$('.countCurations').html(res.total);
		$('.countGenes').html(res.total);
		$('.countHaplo').html(res.nhaplo);
		$('.countTriplo').html(res.ntriplo);

		/*
		$.each(res.rows, function (i, row) {
		row.state = $.inArray(row.id, selections) !== -1
		})*/

    	return res
  	}

	function detailFormatter(index, row) {
		var html = []
		$.each(row, function (key, value) {
		html.push('<p><b>' + key + ':</b> ' + value + '</p>')
		})
		return html.join('')
	}

  	function symbolFormatter(index, row) {
		if (row.type == 0)
			return '<a href="/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>  <a href="/gene-dosage/'
						+ row.hgnc_id + '"><i class="fas fa-external-link-alt"></i></a>';
		else
			return '<a href="https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
	}

  	function hgncFormatter(index, row) {
		if (row.type == 0)
			return '<a href="/gene-dosage/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
		else
			return '<a href="https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id + '"><b>' + row.hgnc_id + '</b></a>';
	}

	function locationFormatter(index, row) {

		if (row.type == 0)
			return row.location;
		else
			return row.GRCh37_position;


		var name = row.GRCh37_position.trim();

		// strip off chr
		if (name.indexOf("chr") === 0)
			name = name.substring(3);

		var chr = name.indexOf(':');
		var pos = name.indexOf('-');

		var html = '<table><tr><td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">'
				+ name.substring(0, chr)
				+ '</td><td class="text-10px line-height-normal">'
				+ name.substring(chr + 1, pos)
				+ '</td></tr><tr><td class="text-10px line-height-normal">'
				+ name.substring(pos + 1)
				+ '</td></tr></table>';
		return html;
	}

	  function locationCellStyle(value, row, index) {
			if (row.type == 0)
			return {
					classes: "format-cyto"
				}

			var name = row.GRCh37_position.trim();
			if (name.indexOf("chr") === 0)
				return {
					classes: "format-loc"
				}
			else
				return {
					classes: "format-null"
				}
  }

	function pliCellStyle(value, row, index) {
			if (row.pli > .50)
				return {
					classes: "format-pli-high"
				}
			else
				return {
					classes: "format-pli-low"
				}
	}

	function pliFormatter(index, row) {
		if (row.pli === null)
			return '-';
		else
			return row.pli;
	}

	function hiCellStyle(value, row, index) {
			if (row.hi > .50)
				return {
					classes: "format-hi-high"
				}
			else
				return {
					classes: "format-hi-low"
				}
	}
	function hiFormatter(index, row) {
		if (row.hi === null)
			return '-';
		else
			return row.hi;
	}

	// function hiFormatter(index, row) {
	// 	if (row.hi === null)
	// 		return '-';

	// 	if (row.hi > 50)
	// 		return '<span style="color: red">' + row.hi + '</span>';
	// 	else
	// 		return '<span style="color: green">' + row.hi + '</span>';
	// }

	function haploFormatter(index, row) {
		if (row.haplo_assertion === false)
			return '';

		/*if (row.haplo_assertion < 10)
			return score_assertion_strings[row.haplo_assertion] + ' for Haploinsufficiency';
		else
			return score_assertion_strings[row.haplo_assertion];*/

		var html = score_assertion_strings[row.haplo_assertion] + '<br />(' + row.haplo_assertion + ')';

		if (row.haplo_history === null)
			return html;

		return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.haplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';

		//return score_assertion_strings[row.haplo_assertion] + '<br />(' + row.haplo_assertion + ')';
	}

	function triploFormatter(index, row) {
		if (row.triplo_assertion === false)
			return '';

		/*if (row.triplo_assertion < 10)
			return score_assertion_strings[row.triplo_assertion] + ' for Triplosensitivity';
		else
			return score_assertion_strings[row.triplo_assertion];*/

			var html = score_assertion_strings[row.triplo_assertion] + '<br />(' + row.triplo_assertion + ')';

			if (row.triplo_history === null)
				return html;


			return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.triplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';

			return '<span style="color:red">' + html + '</span>';


		//return score_assertion_strings[row.triplo_assertion] + '<br />(' + row.triplo_assertion + ')';
	}

	function omimFormatter(index, row) {
		if (row.omimlink)
			return '<i class="fas fa-check text-success"></i>';
		else
			return '';
	}

	function morbidFormatter(index, row) {
		if (row.morbid)
			return '<span class="text-success"><i class="fas fa-check"></i></span>';
		else
			return '';
	}

  	function reportFormatter(index, row) {
		/*return '<a class="btn btn-block btn btn-default btn-xs" href="'
				+ report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
		if (row.type == 0)
			return '<a class="btn btn-block btn btn-default btn-xs" href="'
				+ report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
		else
			return '<a class="btn btn-block btn btn-default btn-xs" href="'
				+ 'https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id
				+ '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
  	}

	// function cellFormatter(index, row) {
	// 	return { classes: 'global_table_cell' };
  // 	}

	function headerStyle(column) {
    	return {
      		// css: { 'font-weight': 'normal' },
      		classes: 'bg-secondary text-light header_class'
    	}
  	}

  	function initTable() {
		$table.bootstrapTable('destroy').bootstrapTable({
		locale: 'en-US',
		columns: [
		{
			title: 'Gene/Region',
			field: 'symbol',
			formatter: symbolFormatter,
			// cellStyle: cellFormatter,
			filterControl: 'input',
			searchFormatter: false,
			sortable: true
		},
        {
			title: 'HGNC',
			field: 'hgnc',
			formatter: hgncFormatter,
			sortable: true,
			filterControl: 'input',
			searchFormatter: false,
			// cellStyle: cellFormatter,
			visible: false
		},

		{
			title: 'Location',
			field: 'location',
			sortable: true,
			filterControl: 'input',
			formatter: locationFormatter,
			cellStyle: locationCellStyle,
			searchFormatter: false,
			//visible: false
        },
        {
			title: 'Haploinsufficiency',
			field: 'haplo_assertion',
			filterControl: 'select',
			formatter: haploFormatter,
			// cellStyle: cellFormatter,
			sortable: true
        },
		{
			title: 'Triplosensitity',
			field: 'triplo_assertion',
			filterControl: 'select',
			formatter: triploFormatter,
			searchFormatter: false,
			// cellStyle: cellFormatter,
			sortable: true
        },
		{
			title: 'OMIM',
			field: 'omimlimk',
			filterControl: 'select',
			formatter: omimFormatter,
			// cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        },
		{
			title: 'Morbid',
			field: 'morbid',
			filterControl: 'select',
			formatter: morbidFormatter,
			//cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        },
		{
			title: '%HI',
			field: 'hi',
			filterControl: 'input',
			cellStyle: hiCellStyle,
			formatter: hiFormatter,
			sortable: true
        },
		{
			title: 'pLI',
			field: 'pli',
			filterControl: 'input',
			cellStyle: pliCellStyle,
			formatter: pliFormatter,
			searchFormatter: false,
			sortable: true
        },
		/*{
			field: 'date',
			title: 'Date',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			sortName: 'rawdate'
		},*/
		{
			field: 'date',
			title: 'Reviewed',
			sortable: true,
			filterControl: 'input',
			// cellStyle: cellFormatter,
			formatter: reportFormatter,
			searchFormatter: false,
			sortName: 'rawdate'
        }
		/*{
			title: 'Report',
			cellStyle: cellFormatter,
			formatter: reportFormatter
        }*/
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

	$table.on('post-body.bs.table', function (e, name, args) {

			$('div.bootstrap-table table td.format-loc').each(function() {
					var text = $(this).text();
					var name = text.trim();
					name = name.substring(3)
					var chr = name.indexOf(':');
					var pos = name.indexOf('-');
					var html = '<table><tr><td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">'
							+ name.substring(0, chr)
							+ '</td><td class="text-10px line-height-normal">'
							+ name.substring(chr + 1, pos)
							+ '</td></tr><tr><td class="text-10px line-height-normal">'
							+ name.substring(pos + 1)
							+ '</td></tr></table>';
					$(this).html(html);
			});
		})

  	$table.on('load-success.bs.table', function (e, name, args) {
		$("body").css("cursor", "default");

		// if (name.indexOf("chr") === 0)
		// 	name = name.substring(3);

		// var chr = name.indexOf(':');
		// var pos = name.indexOf('-');

		// var html = '<table><tr><td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">'
		// 		+ name.substring(0, chr)
		// 		+ '</td><td class="text-10px line-height-normal">'
		// 		+ name.substring(chr + 1, pos)
		// 		+ '</td></tr><tr><td class="text-10px line-height-normal">'
		// 		+ name.substring(pos + 1)
		// 		+ '</td></tr></table>';
		// return html;

		if (name.hasOwnProperty('error'))
      {
        swal({
            title: "Load Error",
            text: name.error,
            icon: "error"
        });
      }
	})

  }

  $(function() {
	$("body").css("cursor", "progress");
    initTable();
	var search = $('.fixed-table-toolbar .search input');
	search.attr('placeholder', 'Search in table');

	//$search.css('border', '1px solid red');
	$( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

	$('#showgenes').on('change', function() {
		var viz = [];

		if (this.checked)
			viz.push(0);

		if ($('#showregions').prop("checked"))
			viz.push(1);

    	$table.bootstrapTable('filterBy', {
       			 type: viz
     	});

    });

	$('#showregions').on('change', function() {
		var viz = [];

		if ($('#showgenes').prop("checked"))
			viz.push(0);

		if (this.checked)
			viz.push(1);

		$table.bootstrapTable('filterBy', {
					type: viz
		});
	});

  })

</script>
@endsection
