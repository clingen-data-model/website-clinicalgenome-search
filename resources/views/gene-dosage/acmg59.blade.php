@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  Curation of the ACMG 59 Genes</h1>
      	{{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
		</div>
	
		<div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="small line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="small line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Genes</li>
					<li class="small line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Genes</li>
					<li class="small line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.css">
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.js"></script>
<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.css" rel="stylesheet">
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.min.js"></script>


<style>
	.fixed-table-toolbar .search-input {
	  min-width: 300px;
	}
	.swal-overlay--show-modal, .swal-modal {
    animation: none !important;
	}
	.fixed-table-container .global_table_cell {
    font-weight: 500;
    font-size: 14px;
	padding: 21px 12px 19px !important;
	}
	.header_class {
    	font-size: 14px;
	}
.bootstrap-table .fixed-table-container .table thead th .sortable {
    cursor: pointer;
    background-position: left;
    background-repeat: no-repeat;
    padding-left: 20px !important;
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

		var url = "https://dosage.clinicalgenome.org/clingen_gene.cgi?sym=";

		return '<a href="' + url + row.gene + '"><b>' + row.gene + '</b></a>';
	}

	function omimFormatter(index, row) {

		var name = row.omim.substring(row.omim.lastIndexOf('/') + 1);

		return '<a href="' + row.omim + '">' + name + '</a>';
	}

	function omimsFormatter(index, row) {

		var html = '';

		var list = row.omims.split(',');

		var addcomma = false;

		list.forEach(function(item) {
			var trimmed = item.trim();
			if (addcomma)
				html += ', ';

			html += '<a href="https://omim.org/entry/' + trimmed + '">' + trimmed + '</a>';
			addcomma = true;
		});

		return html;
	}


	function pmidsFormatter(index, row) {

		var html = '';

		var list = row.pmids.split(',');

		var addcomma = false;

		list.forEach(function(item) {
			var trimmed = item.trim();
			if (addcomma)
				html += ', ';

			html += '<a href="https://ncbi.nlm.nih.gov/pubmed/' + trimmed + '">' + trimmed + '</a>';
			addcomma = true;
		});

		return html;
	}


	function haploFormatter(index, row) {
		if (row.haplo_assertion === false)
			return '';

		/*if (row.haplo_assertion < 10)
			return score_assertion_strings[row.haplo_assertion] + ' for Haploinsufficiency';
		else
			return score_assertion_strings[row.haplo_assertion];*/

		return score_assertion_strings[row.haplo_assertion] + '<br />(' + row.haplo_assertion + ')';
	}

	function triploFormatter(index, row) {
		if (row.triplo_assertion === false)
			return '';

		/*if (row.triplo_assertion < 10)
			return score_assertion_strings[row.triplo_assertion] + ' for Triplosensitivity';
		else
			return score_assertion_strings[row.triplo_assertion];*/

		return score_assertion_strings[row.triplo_assertion] + '<br />(' + row.triplo_assertion + ')';
	}

  	function reportFormatter(index, row) {
		/*return '<a class="btn btn-block btn btn-default btn-xs" href="'
				+ report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
		return '<a class="btn btn-block btn btn-default btn-xs" href="'
				+ report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
  	}

	function cellFormatter(index, row) {
		return { classes: 'global_table_cell' };
  	}

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
			title: 'Disorder',
			field: 'pheno',
			//formatter: phenoFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true,
			visible: false
		},
		{
			title: 'OMIM',
			field: 'omims',
			sortable: true,
			filterControl: 'input',
			formatter: omimsFormatter,
			cellStyle: cellFormatter
		},
		{
			title: 'GeneReviews',
			field: 'pmids',
			sortable: true,
			filterControl: 'input',
			formatter: pmidsFormatter,
			cellStyle: cellFormatter
        },
		{
			title: 'Typical Age of Onset',
			field: 'age',
			sortable: true,
			filterControl: 'input',
			//formatter: locationFormatter,
			cellStyle: cellFormatter
        },
		{
			title: 'Gene',
			field: 'gene',
			formatter: symbolFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true
		},
		{
			title: 'Gene (OMIM)',
			field: 'omimgene',
			formatter: omimsFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true
		},
        {
			title: 'Haploinsufficiency',
			field: 'haplo_assertion',
			filterControl: 'select',
			formatter: haploFormatter,
			cellStyle: cellFormatter,
			sortable: true
        },
		{
			title: 'Triplosensitity',
			field: 'triplo_assertion',
			filterControl: 'select',
			formatter: triploFormatter,
			cellStyle: cellFormatter,
			sortable: true
        }/*,
		{
			field: 'date',
			title: 'Reviewed',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			formatter: reportFormatter,
			sortName: 'rawdate'
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

  }

  $(function() {
	$("body").css("cursor", "progress");
    initTable()
	var $search = $('.fixed-table-toolbar .search input');
	$search.attr('placeholder', 'Search in table');
	//$search.css('border', '1px solid red');
	$( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip()
    $('[data-toggle="popover"]').popover()

  })

</script>
@endsection
