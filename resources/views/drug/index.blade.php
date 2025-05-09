@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-left: -100px; margin-right: -100px">
        <div class="col-md-9 curated-genes-table">

            <table class="mt-3 mb-2">
                <tr>
                    <td class="valign-top"><img src="/images/drugmed.png" width="40" height="40"> </td>
                    <td class="pl-2 pb-3">
                        <h1 class="h2 p-0 m-0">Drugs & Medications</h1>
                    </td>
                    @if ($search == "")
                    <td class="text-xl text-gray-600 pl-3 pt-0">Search results for all Drugs</td>
                    @else
                    <td class="text-xl text-gray-600 pl-3 pb-1"><i>Search results for all Drugs containing: </i><span
                            class="h5 badge badge-secondary matchphrase mb-3 ml-2">"{{ $search }}"</span></td>
                    @endif
                </tr>
            </table>
        </div>

        <div class="col-md-3">
            <div class="">
                <div class="text-right p-2">
                    <ul class="list-inline pb-0 mb-0 small">
                        <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDrugs text-18px"><i
                                    class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total
                            Drugs & Medications<br />Matched by Search</li>
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

        <div class="col-md-12 light-arrows dark-table dark-detail">

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
    var currentsearch = "{{ $search }}";

    function queryParams(params) {
        params.search = currentsearch;
        return params
    }

  window.ajaxOptions = {
    beforeSend: function (xhr) {
      if (Cookies.get('clingen_dash_token') != undefined)
        xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

  function responseHandler(res) {

    $('.countDrugs').html(res.total);

    return res
  }

  var activelist=['Actionability', 'Dosage Sensitivity', 'Gene Validity', 'Variant Pathogenicity', 'Pharmacogenomics'];

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
            case 'variant pathogenicity':
                return value.indexOf('R') != -1;
            case 'pharmacogenomics':
                return value.indexOf('P') != -1;
            default:
                return true;
        }

    }


  function inittable() {
    $table.bootstrapTable('destroy').bootstrapTable({
        stickyHeader: true,
    stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
    stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
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
          title: 'Curations',
          field: 'application',
          formatter: drbadgeFormatter,
          searchFormatter: false,
          sortable: true,
          width: 170,
          filterControl: 'select',
					filterData: 'var:activelist',
					filterCustomSearch: checkactive,
          cellStyle: cellFormatter
        },
        {
          title: 'BioPortal',
          field: 'curie',
          formatter: drPortalFormatter,
          searchFormatter: false,
          cellStyle: cellFormatter,
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

  $('.search-input').on('keyup', function(e) {

        var url = "{{ $apiurl }}";

        var newsearch = $(this).val();

        if (newsearch.indexOf(currentsearch) !== 0)
        {
            $("body").css("cursor", "progress");
            $table.bootstrapTable('showLoading')

            $.get(url + "?search=" + newsearch, function(response)
                {
                    responseHandler(response)

                    $table.bootstrapTable('load', response.rows);
                    //$('#follow-table').bootstrapTable("resetSearch","");

                    currentsearch = newsearch;

                    $('.matchphrase').html('"' + currentsearch + '"');

                    $table.bootstrapTable('hideLoading')
                    $("body").css("cursor", "default");

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });
        }
    })


    $('button[name="clearSearch"]').on('click', function(e) {

        var url = "{{ $apiurl }}";

        // only clear on real clears
        if (currentsearch != "")
        {
            $("body").css("cursor", "progress");
            $table.bootstrapTable('showLoading')

            $.get(url + "?search=", function(response)
                {
                    responseHandler(response)

                    $table.bootstrapTable('load', response.rows);
                    $table.bootstrapTable("resetSearch","");

                    currentsearch = "";

                    $('.matchphrase').html('"' + currentsearch + '"');

                    $table.bootstrapTable('hideLoading')
                    $("body").css("cursor", "default");

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });
        }
    })

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
