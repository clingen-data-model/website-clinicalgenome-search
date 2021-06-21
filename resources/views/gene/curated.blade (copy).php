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

      <div class="col-md-9">
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
      </div>
      <div class="col-md-12 dark-table">
        @include('_partials.genetable', ['customload' => true])
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

<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>
<script src="/js/filters.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

  var $table = $('#table');
  var showadvanced = true;
  var lightstyle = true;
  var scrid = {{ $display_tabs['scrid'] }};
  var scrid_display = "{{ $display_tabs['display'] }}";
  window.token = "{{ csrf_token() }}";
  var originalurl = window.location.search;

  var params = {}

  function queryParams(_params) {
    params = _params
    return _params
  }

  function update_addr(){

    var current = originalurl;

    const parms = new URLSearchParams(current);

    // set page size
    var size = parms.get('size');
    //$table.bootstrapTable('selectPage', parseInt(page))

    // set Search
    var search = parms.get('search');
    $table.bootstrapTable('resetSearch', search);

    // set column sort and order
    var sort = parms.get('sort');
    var order = parms.get('order');

    // once for asc
    $("th[data-field='" + sort + "'] .sortable").click();

    // again for desc
    if (order == "desc")
        $("th[data-field='" + sort + "'] .sortable").click();

    // set page-list
    //var target = $('.page-list').find('a:contains("25")');
    var target = $('.page-list').find('button').first();
    // set page
    var page = parms.get('page');
    $table.bootstrapTable('selectPage', parseInt(page))

  }

  function set_addr(field, value)
  {
    var current = window.location.search;

    var parms = new URLSearchParams(current);

    parms.set(field, value);
    var newurl = parms.toString();

    window.history.replaceState('', 'ClinGen Curated Genes',
        '/kb/genes/curations?' + newurl);
  }

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


  /*
  **
  */
	$('.action-remove-bookmark').on('click', function() {

    var uuid = $('select[name="bookmark"]').val();

    $.ajaxSetup({
        cache: true,
        contentType: "application/x-www-form-urlencoded",
        processData: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : window.token,
            'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
        }
    });

    var url = "/api/filters/" + uuid;

    //submits to the form's action URL
    $.ajax({
        type: "DELETE",
        url: url,
        data: {_method: 'delete', _token : window.token, ident: uuid }
    }).done(function(response)
    {
        var current = $('#modal-current-bookmark').html();

        if (name == current)
            $('#modal-current-bookmark').html('');

        //refresh select list

        var data = $.map(response.list, function (obj) {
            obj.id = obj.ident;
            obj.text = obj.name;

            return obj;
        });


        $(".bookmark-modal-select").empty().select2({
            data: data,
            dropdownParent: $('#modalBookmark'),
            dropdownAutoWidth: true,
            width: '100%'
        });

        $('#modal-bookmark-status').html('Preference removed.');

    }).fail(function(response)
    {
        swal({
            title: "Error",
            text: "An error occurred while deleting the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
            icon: "error",
        });
    });

  });


  /*
  **
  */
	$('.action-default-bookmark').on('click', function() {


     // alert($table.bootstrapTable('getOptions').url + '?' + $.param(params));
    // return;

    var uuid = $('select[name="bookmark"]').val();

    var name = $('select[name="bookmark"] option:selected').text();

    $.ajaxSetup({
        cache: true,
        contentType: "application/x-www-form-urlencoded",
        processData: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : window.token,
            'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
        }
    });

    var url = "/api/filters/" + uuid;

    //submits to the form's action URL
    $.ajax({
      type: "PUT",
      url: url,
      data: {_method: 'put', _token : window.token, ident: uuid, name: name, screen: scrid, default: 1 }
    }).done(function(response)
    {
        var current = $('#modal-current-bookmark').html();

        //if (name == current)
        //    $('#modal-current-bookmark').html('');

        //refresh select list

        var data = $.map(response.list, function (obj) {
            obj.id = obj.ident;
            obj.text = obj.name;

            return obj;
        });


        $(".bookmark-modal-select").empty().select2({
            data: data,
            dropdownParent: $('#modalBookmark'),
            dropdownAutoWidth: true,
            width: '100%'
        });

        $('#modal-bookmark-status').html('Set as default.');

    }).fail(function(response)
    {
        swal({
            title: "Error",
            text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
            icon: "error",
        });
    });
  });


  /*
  **
  */
	$('.action-save-bookmark').on('click', function() {

    var uuid = 0;

    var name = $('#modal-new-bookmark').val();

    var settings = window.location.href;

    $.ajaxSetup({
        cache: true,
        contentType: "application/x-www-form-urlencoded",
        processData: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : window.token,
            'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
        }
    });

    var url = "/api/filters/" + uuid;

    //submits to the form's action URL
    $.ajax({
      type: "PUT",
      url: url,
      data: {_method: 'put', _token : window.token, ident: uuid, name: name, screen: scrid, settings: settings }
    }).done(function(response)
    {
        var current = $('#modal-current-bookmark').html();

        //if (name == current)
         //   $('#modal-current-bookmark').html('');

        //refresh select list

        var data = $.map(response.list, function (obj) {
            obj.id = obj.ident;
            obj.text = obj.name;

            return obj;
        });


        $(".bookmark-modal-select").empty().select2({
            data: data,
            dropdownParent: $('#modalBookmark'),
            dropdownAutoWidth: true,
            width: '100%'
        });

        var name = $('#modal-new-bookmark').val('');

        $('#modal-bookmark-status').html('Preference saved.');

    }).fail(function(response)
    {
        swal({
            title: "Error",
            text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
            icon: "error",
        });
    });
  });


  /*
  **
  */
	$('.action-update-bookmark').on('click', function() {

        var uuid = $('select[name="bookmark"]').val();

        var name = $('select[name="bookmark"] option:selected').text();

        var settings = window.location.href;

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + uuid;

        //submits to the form's action URL
        $.ajax({
            type: "PUT",
            url: url,
            data: {_method: 'put', _token : window.token, ident: uuid, name: name, screen: scrid, settings: settings }
        }).done(function(response)
        {
            var current = $('#modal-current-bookmark').html();

            //if (name == current)
             //   $('#modal-current-bookmark').html('');

            //refresh select list

            var data = $.map(response.list, function (obj) {
                obj.id = obj.ident;
                obj.text = obj.name;

                return obj;
            });


            $(".bookmark-modal-select").empty().select2({
                data: data,
                dropdownParent: $('#modalBookmark'),
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('#modal-bookmark-status').html('Preference updated.');

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


  /*
  **
  */
	$('.action-restore-bookmark').on('click', function() {

    var uuid = $('select[name="bookmark"]').val();

    var name = $('select[name="bookmark"] option:selected').text();

    var settings = window.location.href;

    $.ajaxSetup({
        cache: true,
        contentType: "application/x-www-form-urlencoded",
        processData: true,
        headers:{
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN' : window.token,
            'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
        }
    });

    var url = "/api/filters/" + uuid;

    //submits to the form's action URL
    $.ajax({
      type: "GET",
      url: url,
      data: {_method: 'get', _token : window.token, ident: uuid }
    }).done(function(response)
    {
      var url = window.location.origin + window.location.pathname + '?';

      for (const property in response.data.settings)
      {
        url = url + property + '=' + response.data.settings[property] + '&';
      }

      window.location.href = url;

    }).fail(function(response)
    {
        swal({
            title: "Error",
            text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
            icon: "error",
        });
    });
  });


  function inittable() {
    $.extend($.fn.bootstrapTable.defaults, {
      sortName:  "symbol",
			sortOrder: "asc",
      pageSize: 10,
      pageNumber: 4
    });

    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      //sortName:  "symbol",
			//sortOrder: "asc",
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
            text: "The system could not retrieve data from GeneGraph",
            icon: "error"
      });
    })

    $table.on('refresh-options.bs.table', function (e, name, args) {
    })

    $table.on('page-change.bs.table', function (e, pagenum, pagesize) {
      set_addr("page", pagenum);
      set_addr("size", pagesize);
    })

    $table.on('sort.bs.table', function (e, name, order) {
        set_addr("sort", name);
        set_addr("order", order);
    })

    $table.on('search.bs.table', function (e, text) {
        set_addr("search", text);
    })

    $table.on('load-success.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");
      update_addr();

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


    $table.on('collapse-row.bs.table', function (e, index, row, $obj) {

      $obj.closest('tr').prev().css('border-top', '1px solid #ddd');

      return false;
    });

    $table.on('column-search.bs.table', function (e, index, row, $obj) {
      e.preventDefault();
    });

  }

function loadtable(params) {

    var url = "{{ $apiurl }}";

    // compare the params to the factory setting.
    var p = params.data;

    $.get(url + '?' + $.param(params.data)).then(function (res) {
      params.success(res)
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

  $(".bookmark-modal-select").select2({
    dropdownParent: $('#modalBookmark'),
    dropdownAutoWidth: true,
    width: '100%'

  });

  //$(".bookmark-modal-select").select2('val', '0');

  $( ".fixed-table-toolbar" ).show();
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

  $("button[name='filterControlSwitch']").attr('title', 'Column Search');
	$("button[aria-label='Columns']").attr('title', 'Show/Hide More Columns');

});


</script>
@endsection
