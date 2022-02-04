@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">


	  <div class="col-md-7">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/clinicalActionability-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Clinical Actionability </h1>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Assertions</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique<br />Genes</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br /> Working<br />Groups</li>
          </ul>
				</div>
			</div>
    </div>


    <div class="col-md-12 light-arrows dark-table">
			@include('_partials.genetable', ['customload' => true])
		</div>
	</div>
</div>

@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
      {{-- {{ $col_search }} --}}
    </div>
</div>
@endsection

@section('modals')

@include('modals.bookmark')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
    <link href="/css/bootstrap-table-sticky-header.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>
<script src="/js/bookmark.js"></script>

<script>

  /**
	**
	**		Globals
	**
	*/

  var $table = $('#table');
  var bookmarksonly = true;
  window.scrid = {{ $display_tabs['scrid'] }};
  window.token = "{{ csrf_token() }}";

  window.ajaxOptions = {
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

  function responseHandler(res) {

    $('.countCurations').html(res.nassert);
    $('.countGenes').html(res.total);
    $('.countEps').html(res.npanels);

    return res
  }

  var choices=[
                'Definitive',
                'Strong',
                'Moderate',
                'Limited',
                'N/A - Insufficient evidence: expert review',
                'N/A - Insufficient evidence: early rule-out'
  ];

  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        stickyHeader: true,
    stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
    stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
      locale: 'en-US',
      sortName:  "symbol",
			sortOrder: "asc",
      filterControlVisible: {{ $col_search['col_search'] === null ? "false" : "true" }},
      columns: [
        {
          title: 'Gene',
          field: 'symbol',
          formatter: geneFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          widthUnit: '%',
          width: "20",
          valign: "top",
          sortable: true
        },
        {
          title: 'HGNC',
          field: 'hgnc_id',
          formatter: ashgncFormatter,
          cellStyle: cellFormatter,
          filterControl: 'input',
          searchFormatter: false,
          sortable: true,
          valign: "top",
          visible: false
        },
        {
          title: 'Diseases',
          field: 'diseases',
          formatter: ardiseaseFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          widthUnit: '%',
          width: "40",
          sortable: false
        },
        {
          title: 'Adult Assertion',
          field: 'adults',
          formatter: adultFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          widthUnit: '%',
          width: "20",
          sortable: false
        },
        {
            title: 'Pediatric Assertion',
          field: 'pediatrics',
          formatter: pedFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          widthUnit: '%',
          width: "20",
          sortable: false
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
      window.update_addr();

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
