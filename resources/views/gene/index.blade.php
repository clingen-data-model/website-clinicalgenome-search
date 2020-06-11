@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<h2>Genes</h2>

			<!-- The table -->					
			<div id="toolbar">
			</div>
			<table
			id="table"
			data-toolbar="#toolbar"
			data-addrbar="true"
			data-search="true"
			data-show-refresh="true"
			data-show-toggle="true"
			data-show-fullscreen="true"
			data-show-columns="true"
			data-show-columns-toggle-all="true"
			data-detail-view="true"
			data-show-export="true"
			data-click-to-select="true"
			data-detail-formatter="detailFormatter"
			data-minimum-count-columns="2"
			data-show-pagination-switch="true"
			data-pagination="true"
			data-id-field="id"
			data-page-list="[10, 25, 50, 100, all]"
			data-show-footer="false"
			data-side-pagination="server"
			data-url="/api/genes"
			data-response-handler="responseHandler">
			</table>

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
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/addrbar/bootstrap-table-addrbar.js"></script>

<script>
	var $table = $('#table')
  var selections = []


  function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })
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
			title: 'Gene Symbol',
			field: 'symbol',
			formatter: symbolFormatter,
			sortable: true
        },
        {
			title: 'HGNC ID',
			field: 'hgnc_id',
			sortable: true
        },
		{
			title: 'Gene Name',
			field: 'name'
        },
		{
			title: 'Curations',
			field: 'curations',
			align: 'center',
			formatter: badgeFormatter
        },
		{
			field: 'date',
			title: 'Last Curation Date',
			align: 'right'
        }
      ]
    })
    
    $table.on('all.bs.table', function (e, name, args) {
      console.log(name, args)
    })
   
  }

  $(function() {
    initTable()

  })
</script>
@endsection
