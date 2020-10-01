@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<h1><img src="/images/clinicalValidity-on.png" width="50" height="50">Dosage Sensitivity</h1>
      {{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
    </div>
    <div class="col-md-4">
      <div class="">
        <div class="text-right p-2">
            <ul class="list-inline pb-0 mb-0 small">
              <li class="small line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
              <li class="small line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique<br />Genes</li>
              <li class="small line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-download text-18px"></i><br />Download<br />Options
              </a>
                  <ul class="dropdown-menu dropdown-menu-left">
                    <li><a href="/gene-dosage/download">Summary Data (CSV)</a></li>
                    <li><a href="ftp://ftp.clinicalgenome.org/">Additional Data (FTP)</a></li>
                  </ul>
                </div>
            </ul>
          </div>
      </div>
    </div>
    <div class="col-md-12">
			@include('_partials.genetable')

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

<link href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<style>
	.search-input {
	  min-width: 300px;
	}
  </style>

	<script>

		var $table = $('#table')
	var selections = []


  function responseHandler(res) {
	$('#gene-count').html(res.total);
	$('.countCurations').html(res.total);
	$('.countGenes').html(res.total);

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
	var html = '<a href="/gene-dosage/' + row.hgnc_id + '">' + row.symbol + '</a>';
	return html;
	}

  function hgncFormatter(index, row) {
	var html = '<a href="/gene-dosage/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
	return html;
  }

  function reportFormatter(index, row) {
	var html = '<a class="btn btn-block btn btn-default btn-xs" href="'
			+ row.report +'"><i class="fas fa-file"></i>  View Details</a>';

	return html;
  }

  function badgeFormatter(index, row) {
	var html = '';
	if (row.has_actionability)
    	html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';

	if (row.has_validity)
    	html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';

		if (row.has_dosage)
    	html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';

	return html;
  }

  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [

        {
			title: 'Gene',
			field: 'symbol',
			formatter: symbolFormatter,
			sortable: true
				},
        {
			title: 'HGNC',
			field: 'hgnc',
			formatter: hgncFormatter,
			sortable: true,
			visible: false
				},

		{
			title: 'Location',
			field: 'location',
			sortable: true,
			visible: false
        },
        {
			title: 'Haploinsufficiency',
			field: 'haplo_assertion',
			sortable: true
        },
		{
			title: 'Triplosensitity',
			field: 'triplo_assertion',
			sortable: true
        },
		{
			title: '%HI',
			field: 'hi',
			sortable: true
        },
		{
			title: 'pLI',
			field: 'pli',
			sortable: true
        },
		{
			field: 'date',
			title: 'Date',
			sortable: true
        },
		{
			title: 'Report',
			field: 'report',
			formatter: reportFormatter
        }
      ]
    })

    $table.on('all.bs.table', function (e, name, args) {
			console.log(name, args);

			// Helpers
      $(function () {
        $( ".fixed-table-toolbar" ).show();
        $('[data-toggle="tooltip"]').tooltip()
        $('[data-toggle="popover"]').popover()
      });
    })

	$table.on('load-error.bs.table', function (e, name, args) {
		swal("Load Error!");
	})

  }

  $(function() {
    initTable()
	var $search = $('.fixed-table-toolbar .search input');
	$search.attr('placeholder', 'Search in table');
	//$search.css('border', '1px solid red');

  })

    </script>
@endsection
