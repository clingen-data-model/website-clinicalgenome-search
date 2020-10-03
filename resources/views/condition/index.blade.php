@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<h1>Diseases</h1>
                {{-- <h3>Clingen has information on <span id="gene-count">many</span> conditions</h3> --}}
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
			sortable: true
        },
		{
			title: 'Curations',
			field: 'has_actionability',
      formatter: badgeFormatter,
        },
		{
      title: 'Last Curated',
			field: 'date',
      sortable: true
        }
      ]
    })

    $table.on('all.bs.table', function (e, name, args) {
      console.log(name, args);
      $(function () {
        $( ".fixed-table-toolbar" ).show();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
      });
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
	})

  }

  $(function() {
    $("body").css("cursor", "progress");
    initTable()
	var $search = $('.fixed-table-toolbar .search input');
	$search.attr('placeholder', 'Search in table');
	//$search.css('border', '1px solid red');

  })

    </script>
@endsection