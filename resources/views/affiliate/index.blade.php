@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">

    <div class="col-md-8 curated-genes-table">
      <h1><img src="/images/monitor_200x200.600x600.png" width="50" height="50">  Expert Panels With Gene Curations</h1>
    </div>

    <div class="col-md-4">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="small line-tight text-center pl-3 pr-3"><span class="countPanels text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />EPs</li>
            <li class="small line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
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

    $('.countPanels').html(res.total);
    $('.countCurations').html(res.ncurations);
    /*
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })*/
    return res
  }


  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        {
            title: 'Expert Panel',
            field: 'label',
            formatter: affiliateFormatter,
            cellStyle: cellFormatter,
            filterControl: 'input',
            searchFormatter: false,
			      sortable: true
        },
        {
            title: 'Clingen Affiliate ID',
            field: 'agent',
            cellStyle: cellFormatter,
            filterControl: 'input',
            searchFormatter: false,
            visible: false
        },
		    {
          title: 'Number of Curations',
          field: 'count',
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: false,
          align: 'center'
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
			console.log("post body fired");

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

  })


</script>

@endsection
