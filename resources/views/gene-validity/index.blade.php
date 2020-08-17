@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<h1>Gene Disease Validity</h1>
      <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3>

			@include('_partials.genetable', 
					['tools' => '<a href="/gene-dosage/download"><i class="fas fa-file-download"></i> Download Summary Data</a>'])

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
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/addrbar/bootstrap-table-addrbar.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
	var html = '<a href="/genes/' + row.hgnc_id + '">' + row.symbol + '</a>';
	return html;
  }


  function diseaseFormatter(index, row) { 
	var html = '<a href="/conditions/' + row.mondo + '">' + row.disease + '</a>';
	html += '<div><a href="/conditions/' + row.mondo + '">' + row.mondo.replace('_', ':') + '</a></div>';
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
        },
        {
			title: 'Disease',
			field: 'disease',
			formatter: diseaseFormatter
        },
		{
			title: 'MOI',
			field: 'moi'
        },
		{
			title: 'SOP',
			field: 'sop',
			align: 'center',
        },
		{
			title: 'Classification',
			field: 'classification',
			formatter: badgeFormatter
        },
		{
			field: 'released',
			title: 'Released',
			align: 'right'
        }
      ]
    })
    
    $table.on('all.bs.table', function (e, name, args) {
      console.log(name, args)
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
