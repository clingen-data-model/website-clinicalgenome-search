@extends('layouts.app')

@section('content')
<div id="gene_validity_show" class="container">

<div class="row">
	    <div class="col-md-8">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/clinicalValidity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Gene-Disease Validity Classification Summary</h1>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('validity-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Listing</a></li>
					</ul>
				</div>
			</div>
    </div>
</div>


	<div class="row geneValidityScoresWrapper">
		<div class="col-sm-12">
			<div class="content-space content-border">
				@if($record->json_message_version == "GCI.8.1")
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop8-1')
				@elseif(strpos($record->specified_by->label,"SOP8"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop7')
				@elseif(strpos($record->specified_by->label,"SOP7"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop7')
				@elseif (strpos($record->specified_by->label,"SOP6"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop6')
				@elseif (strpos($record->specified_by->label,"SOP5") && $record->origin == true)
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop5-legacy')
				@elseif (strpos($record->specified_by->label,"SOP5"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop5')
				@elseif (strpos($record->specified_by->label,"SOP4"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop4-legacy')
				@else
					ERROR - NO SOP SET
				@endif

                @if ($extrecord !== null)
                @include('gene-validity.partial.rich_data_table')
                @endif

				{{-- @if (!empty($score_string))
					@if ($assertion->jsonMessageVersion == "GCI.6")
						@include('validity.gci6')
					@else
						@include('validity.gci')
					@endif
				@elseif (!empty($score_string_sop5))
					@include('validity.sop5')
				@else
					@include('validity.sop4')
				@endif --}}
			</div>
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

    $('.countCurations').html(res.total);
    $('.countGenes').html(res.ngenes);
    $('.countEps').html(res.npanels);

    return res
  }

  var choices=[
                'Definitive',
                'Strong',
                'Moderate',
                'Limited',
                'Animal Model Only',
                'Disputed',
                'Refuted',
                'No Known Disease Relationship'
  ];

  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
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
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Mondo Disease Ontology"></i></div> MONDO',
          field: 'mondo',
          formatter: asmondoFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true,
          visible: false
        },
        {
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Mode Of Inheritance"></i></div> MOI',
          field: 'moi',
          sortable: true,
          filterControl: 'select',
          searchFormatter: false,
          //align: 'center',
          formatter: moiFormatter,
          cellStyle: cellFormatter,
        },
        {
            title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="ClinGen Gene Curation Expert Panel (GCEP)"></i></div> Expert Panel',
          field: 'ep',
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'select',
          sortable: true
        },
        {
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Gene Curation Standard Operating Procedure"></i></div> SOP',
          field: 'sop',
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'select',
          sortable: true
        },
		    {
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Clinical Validity Classification"></i></div> Classification',
          field: 'classification',
          formatter: asbadgeFormatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'select',
          filterData: 'var:choices',
          filterDefault: "{{ $col_search['col_search'] === "classification" ? $col_search['col_search_val'] : "" }}",
          sortable: true,
          sortName: 'order'
        },
		    {
          field: 'released',
          title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
          cellStyle: cellFormatter,
          formatter: datebadgeFormatter,
          searchFormatter: false,
          sortable: true,
          filterControl: 'input',
          sortName: 'date'
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
