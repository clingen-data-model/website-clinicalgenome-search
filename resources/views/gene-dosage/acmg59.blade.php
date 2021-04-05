@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">

      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Curations of the ACMG 59 Genes</h1>
          </td>
        </tr>
      </table>
		</div>

		<div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countHaplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Haplo<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countTriplo text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Triplo<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12 dark-table">
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

	var $table = $('#table')
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";

	window.ajaxOptions = {
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

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
		locale: 'en-US',
		columns: [
		{
			title: 'Disorder',
			field: 'pheno',
			//formatter: phenoFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true,
			searchFormatter: false,
			visible: false
		},
		{
			title: 'OMIM',
			field: 'omims',
			sortable: true,
			filterControl: 'input',
			formatter: acmomimsFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
		},
		{
			title: 'GeneReviews',
			field: 'pmids',
			sortable: true,
			filterControl: 'input',
			formatter: acmpmidsFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
        },
		{
			title: 'Typical Age of Onset',
			field: 'age',
			sortable: true,
			filterControl: 'input',
			//formatter: locationFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter,
			visible: false
        },
		{
			title: 'Gene',
			field: 'gene',
			formatter: acmsymbolFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			searchFormatter: false,
			sortable: true
		},
		{
			title: 'Gene (OMIM)',
			field: 'omimgene',
			formatter: acmomimFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			searchFormatter: false,
			sortable: true
		},
        {
			title: 'HI Score <i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Haploinsufficiency Score"></i>',
			field: 'haplo_assertion',
			filterControl: 'select',
			formatter: acmhaploFormatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        },
		{
			title: 'TS Score <i class="fas fa-info-circle color-white ml-1" data-toggle="tooltip" data-placement="top" title="Triplosensitivity Score"></i>',
			field: 'triplo_assertion',
			filterControl: 'select',
			formatter: acmtriploFormatter,
			cellStyle: cellFormatter,
			searchFormatter: false,
			sortable: true
        }/*,
		{
			field: 'date',
			title: 'Reviewed',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			formatter: reportFormatter,
			sortName: 'rawdate'
        }*/
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

  })

</script>
@endsection
