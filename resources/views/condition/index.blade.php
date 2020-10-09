@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
    <div class="col-md-8 curated-genes-table">
      <h1><span id="gene-count"></span><img src="/images/C_Biohazard-512.png" width="50" height="50">  Diseases</h1>
    </div>

    <div class="col-md-4">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="small line-tight text-center pl-3 pr-3"><span class="countDisease text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Diseases</li>
            <li class="small line-tight text-center pl-3 pr-3"><span class="countCurated text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curated</li>
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

<style>
  .fixed-table-toolbar .search-input {
	  min-width: 300px;
	}
	.swal-overlay--show-modal, .swal-modal {
    animation: none !important;
	}
  </style>

	<script>

		var $table = $('#table')
	var selections = []

  function table_buttons ()
	{
		return {
    	}
  }
  
  function responseHandler(res) {
	  //$('#gene-count').html(res.total);
    $('.countDisease').html(res.total);
    $('.countCurated').html(res.ncurated);

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
	// var html = '<a href="/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>'
  //           + '<div class="small text-muted">' + row.curie + ' <span class="badge text-xs">Condition</span></div>';
	var html = '<a href="/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>'
            + '<div class="small text-muted">' + row.curie + '</div>';

  //if (row.description != null)
  //  html += '<div class="text-sm text-muted">' + row.description + '</div>';

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
          title: 'Name',
          field: 'label',
          formatter: symbolFormatter,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'Curations',
          field: 'has_actionability',
          filterControl: 'input',
          formatter: badgeFormatter,
        },
        {
          title: 'Last Curated',
          field: 'date',
          filterControl: 'input',
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

  }

  $(function() {
    $("body").css("cursor", "progress");
    initTable()
    var $search = $('.fixed-table-toolbar .search input');
    $search.attr('placeholder', 'Search in table');
    $( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
	//$search.css('border', '1px solid red');

  })

    </script>
@endsection