@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">
			<h1><img src="/images/clinicalValidity-on.png" width="50" height="50">  Gene-Disease Validity</h1>
      {{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
    </div>

    <div class="col-md-5">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique<br />Genes</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br /> Expert<br />Panels</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><div class="btn-group p-0 m-0" style="display: block"><a class="dropdown-toggle pointer text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-download text-18px"></i><br />Download<br />Options</a>
                <ul class="dropdown-menu dropdown-menu-left">
                  <li><a href="/gene-validity/download">Summary Data (CSV)</a></li>
                </ul>
            </li>
          </ul>
         </div>
      </div>
    </div>

    <div class="col-md-12 light-arrows">
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

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>

<script>

  /**
	**
	**		Globals
	**
	*/

  var $table = $('#table');

  function responseHandler(res) {

    $('.countCurations').html(res.total);
    $('.countGenes').html(res.ngenes);
    $('.countEps').html(res.npanels);

    return res
  }

  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        {
          title: 'Gene',
          field: 'symbol',
          formatter: geneFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'HGNC',
          field: 'hgnc',
          formatter: ashgncFormatter,
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: false,
          sortable: true,
          visible: false
        },
        {
          title: 'Disease',
          field: 'disease',
          formatter: asdiseaseFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'MONDO',
          field: 'mondo',
          formatter: asmondoFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true,
          visible: false
        },
        {
          title: 'MOI',
          field: 'moi',
          sortable: true,
          filterControl: 'input',
          searchFormatter: false,
          formatter: moiFormatter,
          cellStyle: cellFormatter,
        },
        {
          title: 'EP',
          field: 'ep',
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'select',
          sortable: true
        },
        {
          title: 'SOP',
          field: 'sop',
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'select',
          sortable: true
        },
		    {
          field: 'released',
          title: 'Released',
          cellStyle: cellFormatter,
          searchFormatter: false,
          sortable: true,
          filterControl: 'input',
          sortName: 'date'
        },
		    {
          title: 'Classification',
          field: 'classification',
          formatter: asbadgeFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
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

    $table.on('post-body.bs.table', function (e, name, args) {

			$('[data-toggle="tooltip"]').tooltip();
		})
  }

$(function() {

  // Set cursor to busy prior to table init
  $("body").css("cursor", "progress");

  // initialize the table and load the data
  inittable();

  // make some mods to the search input field
  var search = $('.fixed-table-toolbar .search input');
  search.attr('placeholder', 'Search in table');

  $( ".fixed-table-toolbar" ).show();
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

});

</script>
@endsection
