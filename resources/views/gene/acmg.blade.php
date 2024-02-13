@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-7">

      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/acmg.png" width="45" height="45"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0"> ACMG SF Genes and Diseases</h1>
          </td>
        </tr>
      </table>
		</div>

		<div class="col-md-5">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDiseases text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Diseases</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12 mb-2 border" style="background: #f2f7fc">
			<p class="p-2">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna 
			aliqua. Odio eu feugiat pretium nibh ipsum consequat nisl vel pretium. Natoque penatibus et magnis dis parturient. 
			Id venenatis a condimentum vitae. Pharetra sit amet aliquam id diam maecenas ultricies. Sit amet est placerat in egestas 
			erat. Nunc congue nisi vitae suscipit tellus. Eget gravida cum sociis natoque penatibus et. At erat pellentesque adipiscing 
			commodo. Odio eu feugiat pretium nibh ipsum consequat. Ut tristique et egestas quis ipsum. At quis risus sed vulputate odio. 
			Sed faucibus turpis in eu mi bibendum neque. Duis tristique sollicitudin nibh sit amet commodo nulla facilisi. Augue 
			interdum velit euismod in. Odio ut sem nulla pharetra.
			</p>
		</div>

		<div class="col-md-12 light-arrows dark-table">
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
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>

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
		$('.countGenes').html(res.ngenes);
		$('.countDiseases').html(res.ndiseases);

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
		sortName: 'symbol',
		columns: [
		{
			title: 'Gene Symbol',
			field: 'symbol',
			formatter: symbolFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true,
			searchFormatter: false,
			width: 150
		},
		{
			title: 'Curations',
			field: 'curation',
			formatter: badgeFormatter,
			cellStyle: cellFormatter,
			filterControl: 'select',
			sortable: true,
			searchFormatter: false,
			filterData: 'var:activelist',
			filterCustomSearch: checkactive,
          	width: 200
		},
		{
			title: 'Disease',
			field: 'disease_name',
			sortable: true,
			filterControl: 'input',
			formatter: diseaseFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
		},
		{
			title: 'Other Resources',
			field: 'clinvar_link',
			sortable: true,
			filterControl: 'input',
			formatter: acmglinkFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
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
				text: "The system could not retrieve data from server",
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
