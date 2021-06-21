@extends('layouts.app')


@section('content-heading')

	<div class="row mb-1">
		@include('dashboard.includes.header', ['active' => 'more'])
	</div>

@endsection

@section('content')
<div class="container">

    <div id="dashboard-logout" class="row justify-content-center">

        <div class="col-md-9 mt-3 pl-0 pr-0 border">
			<div class="mb-2">
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseReports" role="button" aria-expanded="false" aria-controls="collapseReports">
                    <i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseReportsIcon"></i></a>
                <h4 class="m-0 p-2 text-white" style="background:#3c79b6">Reports</h4>
            </div>

			@include('dashboard.includes.reports')

            <div>
                <a class="float-right m-2" data-toggle="collapse" href="#collapseFollow" role="button" aria-expanded="true" aria-controls="collapseFollow">
					<i class="far fa-minus-square fa-lg" style="color:#ffffff" id="collapseFollowIcon"></i></a>
				<a class="float-right mt-2 mr-4 action-edit-settings" data-target-tab="#globals" data-toggle="tooltip" title="Global Notifications: On">
					<i class="far {{ $notification->frequency['global'] == "on" ? "fa-lightbulb" : '' }} fa-lg action-light-notification" style="color:#ffffff"></i></a>
				<h4 class="m-0 p-2 text-white" style="background:#55aa7f">Followed Genes</h4>
            </div>

            @include('dashboard.includes.follow')

        </div>
        <div class="col-md-3 mt-3">

            @include('dashboard.includes.profile')

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

	@include('modals.unfollowgene', ['gene' => ''])
	@include('modals.followgene', ['gene' => ''])
    @include('modals.profile')
    @include('modals.search')
    @include('modals.searchregion')
	@include('modals.settings')
	@include('modals.report')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
	<link href="/css/gijgo.min.css" rel="stylesheet">
	<link href="/css/bootstrap-tagsinput.css" rel="stylesheet">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">

    <style>
        .profile-background
        {
          position: relative;
          top: 0;
          left: 0;
        }
        .avatar
        {
          position: absolute;
          top: 0px;
          left: 12px;
        }
        .avatar-name
        {
          position: absolute;
          top: 148px;
          left: 30px;
          font-size: 18px;
        }
        .avatar-title
        {
          position: absolute;
          top: 172px;
          left: 30px;
          font-size: 14px;
        }
		.size {
			height: 100px;
			width: 100px;
			display: block;
			margin-left: auto;
			margin-right: auto;
			}

		.caption {
			font-size: 14px;
			color: black;
			text-align: center;
			width: 200px;
		}
		.folder-effects:hover .size {
			opacity: 0.7;
		}
		.selector {
			width: 100%;
		}
		.bootstrap-tagsinput {
 			 width: 100% !important;
		}
    </style>

@endsection

@section('script_js')

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/gijgo.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-tagsinput.min.js"></script>
<script src="/js/genetable.js"></script>
<script src="/js/edit.js"></script>

<script>

    $(function() {
		var $table = $('#follow-table');
		var $reporttable = $('#table');

		window.burl = '{{  url('api/genes/find/%QUERY') }}';

        // make some mods to the search input field
        var search = $('.fixed-table-toolbar .search input');
        search.attr('placeholder', 'Search in table');

        $( ".fixed-table-toolbar" ).show();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        $('#follow-table').on('click', '.action-region-expand', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('expandRowByUniqueId', uuid);

            $(this).removeClass('action-region-expand')
                .addClass('action-region-collapse')
                .html('Hide region');
        });

        $('#follow-table').on('click', '.action-region-collapse', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('collapseRowByUniqueId', uuid);

            $(this).addClass('action-region-expand')
                .removeClass('action-region-collapse')
                .html('Show region');
        });

        $table.on('expand-row.bs.table', function (e, index, row, $obj) {

            $obj.attr('colspan',12);

            console.log(row.hgnc.substring(1));

            $obj.load( "/api/home/dare/expand/" + row.hgnc.substring(1));

            return false;
        });
	});

</script>

<script src="/js/typeahead.js"></script>
<script src="/js/dashboard.js"></script>

<script>

	function symbolClass(value, row, index)
	{
		return {
			classes: 'table-symbol'
		}
	}

	function rowAttributes(row, index)
	{
	return {
		'data-hgnc': row.hgnc
	}
	}

	function formatSymbol(value, row, index)
	{
        if (row.hgnc.charAt(0) == '@')
            return value;
        else if (row.hgnc.charAt(0) == '%')
            return value;
        else
		    return '<a href="/kb/genes/' + row.hgnc + '">' + value + '</a></td>';
	}

</script>

@endsection
