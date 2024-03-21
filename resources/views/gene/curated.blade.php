@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-md-3 pr-0">
        <table class="mt-3 mb-2">
          <tr>
            <td class="valign-top"><span id="gene-count"></span><span class='hidden-sm hidden-xs'><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></span></td>
            <td class="pl-2"><h1 class="h2 p-0 m-0"> Curated Genes </h1>
            </td>
          </tr>
        </table>
      </div>

      <div class="col-md-9 pl-0">
        <div class="">
          <div class="text-right p-2">
            <ul class="list-inline pb-0 mb-0 small">
                <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Unique Curated<br />Genes</li>
                <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countValidity text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Gene-Disease<br />Validity Genes</li>
                <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDosage text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Dosage<br />Sensitivity Genes</li>
                <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countActionability text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Actionability<br />Genes</li>
                <li class="text-stats line-tight text-center pl-3 pr-3" data-toggle="tooltip" data-placement="top" title="Genes within scope for approved ClinGen Variant Curation Expert Panels (VCEPs) are indicated in the 'Variant Pathogenicity' column below"><span class="countVariant text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Genes Included on<br />Approved VCEPs</li>
                <li class="text-stats line-tight text-center pl-3 pr-3" data-toggle="tooltip" data-placement="top" title="Data provided by PharmGKB and CPIC"><span class="countPharma text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Pharmacogenomics<br />Genes</li>
                </ul>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#modalFilter">
          <span class="text-muted font-weight-bold mr-1"><small><i class="glyphicon glyphicon-tasks" style="top: 2px"></i> Advanced Filters:  </small></span><span class="filter-container"><span class="badge action-af-badge">None</span></span>
        </button>
        <span class="text-info font-weight-bold mr-1 float-right action-hidden-columns hidden"><small>Click on <i class="glyphicon glyphicon-th icon-th" style="top: 2px"></i> below to view hidden columns</small></span>
      </div>
      <div class="col-md-12 dark-table">
        @include('_partials.genetable', ['customload' => true, 'expand' => true])
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

@section('modals')

@include('modals.curatedfilter')
@include('modals.bookmark')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
  <link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
  <link href="/css/select2.css" rel="stylesheet">
  <link href="/css/bootstrap-table-sticky-header.css" rel="stylesheet">
@endsection

@section('script_js')
<script src="/js/select2.full.min.js"></script>

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>
<script src="/js/filters.js"></script>
<script src="/js/bookmark.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

  var $table = $('#table');
  var showadvanced = true;
  var lightstyle = true;
  window.scrid = {{ $display_tabs['scrid'] }};
  window.token = "{{ csrf_token() }}";


  window.ajaxOptions = {
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

  function responseHandler(res) {
    $('.countGenes').html(res.total);
    $('.countValidity').html(res.nvalid);
    $('.countActionability').html(res.naction);
    $('.countDosage').html(res.ndosage);
    $('.countPharma').html(res.npharma);
    $('.countVariant').html(res.nvariant);

    return res
  }

  /*
  **  Filter control for follow mode
  */
	$('#curated-filter-dashboard').on('login', function() {
    $(this).show();
  });


  /*
  **  Filter control for follow mode
  */
	$('#curated-filter-dashboard').on('logout', function() {
    $(this).hide();
  });


  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
    stickyHeader: true,
    stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
    stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
      locale: 'en-US',
      sortName:  "symbol",
	  sortOrder: "asc",
      columns: [
        {
          title: 'Gene',
          field: 'symbol',
          formatter: symbol2Formatter,
          cellStyle: cellFormatter,
          searchFormatter: false,
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'HGNC ID',
          field: 'hgnc_id',
          filterControl: 'input',
          cellStyle: cellFormatter,
          searchFormatter: false,
          sortable: true,
          visible: false
        },
		    {
          title: '<span data-toggle="tooltip" data-placement="top" title="Can variation in this gene cause disease?" aria-describedby="tooltip"><div><img src="/images/clinicalValidity-on.png" width="40" height="40"></div>Gene Disease Validity <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          field: 'has_validity',
          formatter: hasvalidityFormatter,
          cellStyle: cellFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?" aria-describedby="tooltip"><div><img src="/images/dosageSensitivity-on.png" width="40" height="40"></div>Dosage Sensitivity <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Dosage Sensitivity',
          field: 'has_dosage',
          formatter: hasdosageFormatter,
          cellStyle: cellFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="Does this genetic diagnosis impact clinical management in the context of secondary findings?"><div><img src="/images/clinicalActionability-on.png" width="40" height="40"></div>Clinical Actionability <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Clinical Actionability',
          field: 'has_actionability',
          formatter: hasactionabilityFormatter,
          cellStyle: cellFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="Which variants within this gene cause disease? (Denotes genes included on approved VCEPs)"><div><img src="/images/variantPathogenicity-on.png" width="40" height="40"></div>Variant Pathogenicity <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Variant Pathogenicity',
          field: 'has_variant',
          formatter: hasVariantFormatter,
          cellStyle: cellFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        },
        {
          title: '<span data-toggle="tooltip" data-placement="top" title="How do variations in this gene affect variations in drug response?  (Data provided by PharmGKB and CPIC)"><div><img src="/images/Pharmacogenomics-on.png" width="40" height="40"></div>Pharmacogenomics <div style="display:inline-block"><i class="fas fa-info-circle text-muted"></i></div></span>',
          //title: 'Phamogenetics',
          field: 'has_pharma',
          formatter: hasPharmaFormatter,
          cellStyle: cellFormatter,
          align: 'center',
          filterControl: 'input',
          searchFormatter: false,
          sortable: true
        }
      ]
    })

    $table.on('load-error.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");

      swal({
            title: "Load Error",
            text: "The system could not retrieve data at this time",
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

      var hidden = $table.bootstrapTable('getHiddenColumns');

			if (hidden.length > 0)
				$('.action-hidden-columns').removeClass('hidden');
			else
				$('.action-hidden-columns').addClass('hidden');
    })


    $table.on('column-switch.bs.table', function (e, name, args) {
			var hidden = $table.bootstrapTable('getHiddenColumns');

			if (hidden.length > 0)
				$('.action-hidden-columns').removeClass('hidden');
			else
				$('.action-hidden-columns').addClass('hidden');
		});

    $table.on('post-body.bs.table', function (e, name, args) {
			$('[data-toggle="tooltip"]').tooltip();
		})


    $table.on('expand-row.bs.table', function (e, index, row, $obj) {

      $obj.attr('colspan',12);

      var t = $obj.closest('tr');

      var stripe = t.prev().hasClass('bt-even-row');

      t.addClass('dosage-row-bottom').addClass('dosage-row-left').addClass('dosage-row-right');

      if (stripe)
          t.addClass('bt-even-row');
      else
          t.addClass('bt-odd-row');

      t.prev().addClass('dosage-row-top').addClass('dosage-row-left').addClass('dosage-row-right');

      $obj.load( "/api/search/expand/" + row.symbol_id , function() {
          $(this).find('[data-toggle="tooltip"]').tooltip();
      });

      // change the icon
      var far = t.prev().find('.action-acmg-expand');
      far.removeClass('fa-caret-square-down').addClass('fa-caret-square-up');

      $('[data-toggle="tooltip"]').tooltip();


      return false;
    })

    $table.on('collapse-row.bs.table', function (e, index, row, $obj) {

      $obj.closest('tr').prev().removeClass(['dosage-row-top', 'dosage-row-left', 'dosage-row-right']);

      return false;
    });


    $table.on('column-search.bs.table', function (e, index, row, $obj) {

      return false;
    });

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
	$("button[aria-label='Columns']").attr('title', 'Show/Hide More Columns');

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
