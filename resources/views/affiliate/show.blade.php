@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<h1>
        {{-- <img src="/images/clinicalValidity-on.png" width="50" height="50"> --}}
        <span class="affiliate-id">Loading...</span> Expert Panel</h1>
      <h5><strong class="gene-count">Loading...</strong> gene disease curations have been published by <span class="affiliate-id">loading..</span>.</h5>

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

    // TODO - Moved away from #gene-count to class... check if all changed and temove the # code below
    $('#gene-count').html(res.total);
    $('.gene-count').html(res.total);
    /*
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })*/

    $('.affiliate-id').html(res.id);

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
	var html = '<a href="/genes/' + row.hgnc_id + '">' + row.symbol + '</a>';
	return html;
  }

  function hgncFormatter(index, row) {
	var html = '<a href="/genes/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
	return html;
  }


  function diseaseFormatter(index, row) {
	var html = '<a href="/conditions/' + row.mondo + '">' + row.disease + '</a>';
	return html;
  }
    function mondoFormatter(index, row) {
	var html = '<a href="/conditions/' + row.mondo + '">' + row.mondo.replace('_', ':') + '</a>';
	return html;
  }

  function badgeFormatter(index, row) {

	html = '<a class="btn btn-default btn-xs" href="/gene-validity/' + row.perm_id + '">'
            + '<i class="glyphicon glyphicon-file"></i> <strong>' + row.classification + '</strong></a>';

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
        },{
			title: 'HGNC',
			field: 'hgnc',
			formatter: hgncFormatter,
			sortable: true,
			visible: false
        },
        {
			title: 'Disease',
			field: 'disease',
			formatter: diseaseFormatter,
      sortable: true
        },
        {
			title: 'MONDO',
			field: 'mondo',
			formatter: mondoFormatter,
      sortable: true,
			visible: false
        },
		{
			title: 'MOI',
			field: 'moi',
      sortable: true,
      //visible: false
        },
		{
			title: 'SOP',
			field: 'sop',
      sortable: true
        },
		{
			field: 'released',
			title: 'Released',
      sortable: true
        },
		{
			title: 'Classification',
			field: 'classification',
			formatter: badgeFormatter,
      sortable: true
        }
      ]
    })

    $table.on('all.bs.table', function (e, name, args) {
      console.log(name, args);
      // Helpers
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });
      $(function () {
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
