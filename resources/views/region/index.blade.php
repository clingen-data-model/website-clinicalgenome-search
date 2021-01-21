@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
    <div class="col-md-8 curated-genes-table">
      <h1>

        Region</h1>
    </div>

    <div class="col-md-4">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDisease text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Diseases</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurated text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curated</li>
          </ul>
        </div>
      </div>
    </div>

		<div class="col-md-12 light-arrows">

			TDB

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

@section('script_css')
	<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.css">
	<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/group-by-v2/bootstrap-table-group-by.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/js-xlsx/xlsx.core.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/filter-control/bootstrap-table-filter-control.js"></script>

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
    $('.countDisease').html(res.total);
    $('.countCurated').html(res.ncurated);

    return res
  }


  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        {
          title: 'Name',
          field: 'label',
          formatter: conditionFormatter,
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: true,
          sortable: true
        },
        {
          title: 'Curations',
          field: 'has_actionability',
          filterControl: 'input',
          formatter: cbadgeFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false
        },
        {
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
          field: 'date',
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

    $("button[name='filterControlSwitch']").attr('title', 'Column Search');
		$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

  });

</script>

@endsection