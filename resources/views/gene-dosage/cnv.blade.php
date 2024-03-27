@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">


      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Curations of Recurrent CNVs</h1>
          </td>
        </tr>
			</table>

		</div>

		<div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Regions</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
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
				@include('_partials.genetable', ['expand' => true])

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
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";
    var bookmarksonly = true;
    window.scrid = {{ $display_tabs['scrid'] }};
    window.token = "{{ csrf_token() }}";

	window.ajaxOptions = {
		beforeSend: function (xhr) {
		xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
		}
	}

	var hapChoices= {
		'0': 'No Evidence',
		'1': 'Little Evidence',
		'2': 'Emerging Evidence',
		'3': 'Sufficient Evidence',
		'30': 'Autosomal Recessive',
		'40': 'Dosage Sensitivity Unlikely',
		'-5': 'Not Yet Evaluated',
	};


	function responseHandler(res) {
		//$('#gene-count').html(res.total);
		$('.countCurations').html(res.total);
		$('.countGenes').html(res.total);
		$('.countHaplo').html(res.nhaplo);
		$('.countTriplo').html(res.ntriplo);

    	return res
  	}


  	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
        stickyHeader: true,
        stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
        stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
		locale: 'en-US',
		sortName:  "location",
		sortOrder: "asc",
		rowStyle:  function(row, index) {
				if (index % 2 === 0) {
     				return {
						classes: 'bt-even-row bt-hover-row'
					}
				}
				else {
     				return {
						classes: 'bt-odd-row bt-hover-row'
					}
				}
     		},
		columns: [
		{
			title: 'Region Name',
			field: 'name',
			formatter: regionFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			searchFormatter: false,
			sortable: true
		},
		{
			title: 'GRCh37',
			field: 'location',
			sortable: true,
			filterControl: 'input',
			formatter: cnvlocationFormatter,
			cellStyle: cellFormatter,
			sorter: locationSorter,
			searchFormatter: false
        },
		{
			title: 'GRCh38',
			field: 'location38',
			sortable: true,
			filterControl: 'input',
			formatter: cnvlocation38Formatter,
			cellStyle: cellFormatter,
			sorter: locationSorter,
			searchFormatter: false
        },
        {
			title: '<div><i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Haploinsufficiency Score"></i></div>HI Score',
			field: 'haplo_assertion',
			filterControl: 'select',
			formatter: haplo2Formatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			filterData: 'var:hapChoices',
			filterStrictSearch: true,
			sortable: true
        },
		{
			title: '<div><i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Triplosensitivity Score"></i></div>TS Score',
			field: 'triplo_assertion',
			filterControl: 'select',
			formatter: triplo2Formatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			filterData: 'var:hapChoices',
			filterStrictSearch: true,
			sortable: true
        },
		{
			field: 'date',
     		title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Last Evaluated"></i></div> Last Eval.',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			formatter: cnvreportFormatter,
			sortName: 'rawdate'
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

	$table.on('click-cell.bs.table', function (event, field, value, row, $obj) {
		//console.log(e);
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();

	});

	$table.on('expand-row.bs.table', function (e, index, row, $obj) {

		$obj.attr('colspan',12);

		var t = $obj.closest('tr');

		var stripe = t.prev().hasClass('bt-even-row');

		t.addClass('dosage-row-bottom');

		if (stripe)
			t.addClass('bt-even-row');
		else
			t.addClass('bt-odd-row');

		t.prev().addClass('dosage-row-top');

		if (row.hasOwnProperty('hgnc_id'))
			$obj.load( "/api/dosage/expand/" + row.hgnc_id );
		else
			$obj.load( "/api/dosage/expand/" + row.key );

		return false;
	})

	$table.on('collapse-row.bs.table', function (e, index, row, $obj) {

		$obj.closest('tr').prev().removeClass('dosage-row-top');

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
