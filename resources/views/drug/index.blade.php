@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
    <div class="col-md-7 curated-genes-table">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/drugmed.png" width="40" height="40">  </td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">Drugs & Medications</h1>
          </td>
          <td class="text-xl text-gray-600 pl-3 pt-2">matching search term "{{ $search }}"</td>
        </tr>
      </table>
    </div>

    <div class="col-md-5">
      <div class="">
        <div class="text-right p-2">
          <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDrugs text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total Drugs & Medications<br />Matched by Search</li>
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

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>

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

    $('.countDrugs').html(res.total);

    return res
  }

  var activelist=['Actionability', 'Dosage Sensitivity', 'Gene Validity'];

function checkactive(text, value, field, data)
{
  switch (text)
  {
    case 'actionability': 
      return value.indexOf('A') != -1;
    case 'dosage sensitivity':
      return value.indexOf('D') != -1;
    case 'gene validity':
      return value.indexOf('V') != -1;
    default:
      return true;
  }

}
  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        {
          title: 'Drug',
          field: 'label',
          formatter: drugFormatter,
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },{
          title: 'RXNORM',
          field: 'curie',
          formatter: drsymbolFormatter,
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },
        {
          title: 'Application',
          field: 'application',
          formatter: drbadgeFormatter,
          searchFormatter: false,
          filterControl: 'select',
					filterData: 'var:activelist',
					filterCustomSearch: checkactive,
          cellStyle: cellFormatter,
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

  $("button[name='filterControlSwitch']").attr('title', 'Column Search');
	$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

});

</script>

@endsection
