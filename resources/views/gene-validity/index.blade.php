@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<h1><img src="/images/clinicalValidity-on.png" width="50" height="50">  Gene Disease Validity</h1>
      {{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
    </div>

    <div class="col-md-4">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="small line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
            <li class="small line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique<br />Genes</li>
            <li class="small line-tight text-center pl-3 pr-3"><span class="countEps text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br /> Expert<br />Panels</li>
            <li class="small line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-download text-18px"></i><br />Download<br />Options</a>
                <ul class="dropdown-menu dropdown-menu-left">
                  <li><a href="/gene-validity/download">Summary Data (CSV)</a></li>
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


  function responseHandler(res) {

    $('#gene-count').html(res.total);
    $('.countCurations').html(res.total);
    $('.countGenes').html(res.ngenes);
    //$('.countDiseases').html(res.total);
    $('.countEps').html(res.npanels);
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
	  return '<a href="/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
  }

  function hgncFormatter(index, row) {
	  return '<a href="/genes/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
  }

  function diseaseFormatter(index, row) {
	  return '<a href="/conditions/' + row.mondo + '"><b>' + row.disease + '</b></a>';
  }

  function mondoFormatter(index, row) {
	  return '<a href="/conditions/' + row.mondo + '">' + row.mondo.replace('_', ':') + '</a>';
  }

  function moiFormatter(index, row) {
	  return '<span class="pointer" data-toggle="tooltip" data-placement="top" title="' + row.moi +' full text" ">' + row.moi + '</span>';
  }

  function badgeFormatter(index, row) {
	  return '<a class="btn btn-default btn-xs" href="/gene-validity/' + row.perm_id + '">'
            + '<i class="glyphicon glyphicon-file"></i> <strong>' + row.classification + '</strong></a>';
  }

  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        {
          title: 'Gene',
          field: 'symbol',
          formatter: symbolFormatter,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'HGNC',
          field: 'hgnc',
          formatter: hgncFormatter,
          filterControl: 'input',
          sortable: true,
          visible: false
        },
        {
          title: 'Disease',
          field: 'disease',
          formatter: diseaseFormatter,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'MONDO',
          field: 'mondo',
          formatter: mondoFormatter,
          filterControl: 'input',
          sortable: true,
          visible: false
        },
        {
          title: 'MOI',
          field: 'moi',
          sortable: true,
          filterControl: 'input',
          formatter: moiFormatter,
        },
        {
          title: 'EP',
          field: 'ep',
          filterControl: 'select',
          sortable: true
        },
        {
          title: 'SOP',
          field: 'sop',
          filterControl: 'select',
          sortable: true
        },
		    {
          field: 'released',
          title: 'Released',
          sortable: true,
          filterControl: 'input',
          sortName: 'date'
        },
		    {
          title: 'Classification',
          field: 'classification',
          formatter: badgeFormatter,
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

    initTable();

	  var $search = $('.fixed-table-toolbar .search input');
	  $search.attr('placeholder', 'Search in table');
	  //$search.css('border', '1px solid red');
    $( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip()
    $('[data-toggle="popover"]').popover()

  })
</script>
@endsection
