@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">


	  <div class="col-md-7">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/clinicalValidity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Gene-Disease Validity </h1>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Curations</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique<br />Genes</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br /> Expert<br />Panels</li>
          </ul>
				</div>
			</div>
    </div>

    <div class="col-md-12">
        <!--<button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#modalFilter">
         <span class="text-muted font-weight-bold mr-1"><small><i class="glyphicon glyphicon-tasks" style="top: 2px"></i> Advanced Filters:  </small></span><span class="filter-container"><span class="badge action-af-badge">None</span></span>
     </button>-->
     <span class="text-info font-weight-bold mr-1 float-right action-hidden-columns hidden"><small>Click on <i class="glyphicon glyphicon-th icon-th" style="top: 2px"></i> below to view hidden columns</small></span>

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
    <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
    <link href="https://unpkg.com/multiple-select@1.5.2/dist/themes/bootstrap.min.css" rel="stylesheet">
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
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>

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

  function checkclass(text, value, field, data)
	{
		if (text == 'animal model only')
        {
            return value == "No Known Disease Relationship*";
        }
        else if (text == 'no known disease relationship')
        {
            return value == "No Known Disease Relationship" || value == "No Known Disease Relationship*"
        }
		else
			return text == value.toLowerCase();
	}

  function customFilter(row,filter){
    const filterCities = filter['sops']
    return filterCities.length == 0 || filterCities.includes(row.sop)
  }

  function filterData() {
    $table.bootstrapTable('filterBy', {
      sops: $('select.bootstrap-table-filter-control-sop').multipleSelect('getSelects')
    }, {
      'filterAlgorithm': customFilter
    })
  }

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
          filterCustomSearch: checkclass,
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

    $table.on('column-switch.bs.table', function (e, name, args) {
        var hidden = $table.bootstrapTable('getHiddenColumns');

        if (hidden.length > 0)
            $('.action-hidden-columns').removeClass('hidden');
        else
            $('.action-hidden-columns').addClass('hidden');
    });

    $table.on('load-success.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");
      window.update_addr();

				var $select = $('select.bootstrap-table-filter-control-sop');
				$select.attr('multiple','multiple');
				$select.find('option[value=""]').remove();
				$select.multipleSelect({
					filter: true,
					selectAll:true
				});

   $('select.bootstrap-table-filter-control-sop').change(function () {
       console.log("trigger");
        filterData()
    })

      if (name.hasOwnProperty('error'))
      {
        swal({
            title: "Load Error",
            text: name.error,
            icon: "error"
        });
      }

      var hidden = $table.bootstrapTable('getHiddenColumns');

        if (hidden.length > 0)
            $('.action-hidden-columns').removeClass('hidden');
        else
            $('.action-hidden-columns').addClass('hidden');
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

    $('.fixed-table-toolbar').on('change', '.toggle-all', function (e, name, args) {

        var hidden = $table.bootstrapTable('getHiddenColumns');

        if (hidden.length > 0)
            $('.action-hidden-columns').removeClass('hidden');
        else
            $('.action-hidden-columns').addClass('hidden');
    });

});

</script>
@endsection
