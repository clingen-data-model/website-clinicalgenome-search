@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Curated Genes</h1>
                <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3>
    
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
<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/addrbar/bootstrap-table-addrbar.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
	var $table = $('#table')
	var selections = []


  function responseHandler(res) {

    $('#gene-count').html(res.total);
    
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


  function validityFormatter(index, row) { 
    var html = '';
    
    if (row.has_validity)
    {
        html = '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
    }

	return html;
  }


  function actionabilityFormatter(index, row) { 
	var html = '';
    
    if (row.has_actionability)
    {
        html = '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id 
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
    }

	return html;
  }


  function haploFormatter(index, row) { 
	var html = '';
    
    if (row.has_dosage_haplo)
    {
        html = '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym='
             + row.symbol + '&subject' 
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">'
            + row.has_dosage_haplo + '</span></a>';
    }

	return html;
  }


  function triploFormatter(index, row) { 
	var html = '';
    
    if (row.has_dosage_triplo)
    {
        html = '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym='
             + row.symbol + '&subject' 
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">'
            + row.has_dosage_triplo + '</span></a>';
    }

	return html;
  }


  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        
        [{
			title: 'Gene',
            field: 'symbol',
            rowspan: 2,
            valign: 'bottom',
			formatter: symbolFormatter,
			sortable: true
        },
        {
            title: '<img src="/images/clinicalValidity-on.png" width="40" height="40"><div>Gene-Disease Validity</div>',
			align: 'center'
        },
		{
			title: '<img src="/images/clinicalActionability-on.png" width="40" height="40"><div>Clinical Actionability</div>',
			align: 'center'
        },
		{
			title: '<img src="/images/dosageSensitivity-on.png" width="40" height="40"><div>Gene Dosage Sensitivity</div>',
            colspan: 2,
			align: 'center',
        }],
		[{
            title: 'Clinical Validity Classifications',
            field: 'has_validity',
            formatter: validityFormatter,
			align: 'center'
        },
        {
            title: 'Evidence-Based Summary',
            field: 'has_actionability',
            formatter: actionabilityFormatter,
			align: 'center'
        },
        {
            title: 'Haploinsufficiency Score',
            field: 'has_dosage_haplo',
            formatter: haploFormatter,
			align: 'center'
        },
        {
            title: 'Triplosensitivity Score',
            field: 'has_dosage_triplo',
            formatter: triploFormatter,
			align: 'center'
        }]
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
