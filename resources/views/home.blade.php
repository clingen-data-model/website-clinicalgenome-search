@extends('layouts.app')


@section('content-heading')

	<div class="row mb-1">
		@include('dashboard.includes.header', ['active' => 'more'])
	</div>

@endsection

@section('content')
<div class="container">

    <div id="dashboard-logout" class="row justify-content-center">

        <div class="col-md-9 mt-3 pl-0 pr-0">

            @if ($notification->frequency['global'] == "on")
                @if($notification->frequency['global_pause'] == "on")
                    <div class="alert alert-warning action-notification-alert" role="alert">
                        Global notifications are <b><span class="action-notification-text">PAUSED</span></b>
                    </div>
               @else 
                    <div class="alert alert-info action-notification-alert" role="alert">
                        Global notifications are <b><span class="action-notification-text">ON</span></b>
                    </div>
                @endif
            @else 
                <div class="alert alert-danger action-notification-alert" role="alert">
                    Global notifications are <b><span class="action-notification-text">OFF</span></b>
                </div>
            @endif

            @if ($user->isGenomeConnectAdmin())
            <div class="mb-2">
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseGenCon" role="button" aria-expanded="false" aria-controls="collapseGenCon">
                    <i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseGenConIcon"></i></a>
                <h4 class="m-0 p-2 text-white" style="background:#800080">GenomeConnect</h4>
            </div>

            @include('dashboard.includes.genomeconnect')

            @endif

			<div class="mb-2">
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseReports" role="button" aria-expanded="false" aria-controls="collapseReports">
                    <i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseReportsIcon"></i></a>
                <h4 class="m-0 p-2 text-white" style="background:#3c79b6">Reports</h4>
            </div>

			@include('dashboard.includes.reports')

            <div class="mb-2">
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseFollow" role="button" aria-expanded="true" aria-controls="collapseFollow">
					<i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseFollowIcon"></i></a>
				<!-- <a class="float-right mt-2 mr-4 action-edit-settings" data-target-tab="#globals" data-toggle="tooltip" title="Global Notifications: On">
					<i class="far {{ $notification->frequency['global'] == "on" ? "fa-lightbulb" : '' }} fa-lg action-toggle-notification" style="color:#ffffff"></i></a>
                <a class="float-right mt-2 mr-4 action-edit-settings" data-target-tab="#globals" data-toggle="tooltip" title="Pause All Notifications: On">
                    <i class="fas {{ $notification->frequency['global_pause'] == "on" ? "fa-pause" : '' }} fa-lg action-pause-notification" style="color:#ffffff"></i></a>
				-->
                    <h4 class="m-0 p-2 text-white" style="background:#55aa7f">Followed Genes</h4>
            </div>

            @include('dashboard.includes.follow')

            <div>
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseDiseases" role="button" aria-expanded="false" aria-controls="collapseDiseases">
                    <i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseDiseaseIcon"></i></a>
				<h4 class="m-0 p-2 text-white" style="background:#E67E22">Followed Diseases</h4>
            </div>

            @include('dashboard.includes.diseases')

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
    @include('dashboard.modals.followeps')
	@include('modals.report')
    @include('modals.followgencon', ['gene' => ''])
    @include('modals.unfollowgencon', ['ident' => ''])
    @include('modals.searchgenomeconnect')
    @include('modals.genconupload')
    @include('modals.searchdisease')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
	<link href="/css/gijgo.min.css" rel="stylesheet">
	<link href="/css/bootstrap-tagsinput.css" rel="stylesheet">

    <link href="/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">


	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">

    <link href="/css/dropzone.min.css" rel="stylesheet">

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

<script src="/js/bootstrap-datepicker.min.js"></script>

<script>

    $(function() {
		var $table = $('#follow-table');
		var $reporttable = $('#table');
        var $gencontable = $('#gencon-table');
        var $diseasetable = $('#disease-table')

		window.burl = '{{  url('api/genes/find/%QUERY') }}';
        window.dburl = '{{  url('api/conditions/find/%QUERY') }}';
        window.token = "{{ csrf_token() }}";


        // make some mods to the search input field
        var search = $('.fixed-table-toolbar .search input');
        search.attr('placeholder', 'Search in table');

        $( ".fixed-table-toolbar" ).show();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        //ds_pause_date
        $('#ds_pause_date').datepicker({ 
            startDate: new Date() 
        });

        $('#ds_pause_date').datepicker().on('changeDate', function (ev) {
            alert("b");
        });

        $('#follow-table').on('click', '.action-region-expand', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('expandRowByUniqueId', uuid);

            $(this).removeClass('action-region-expand')
                .addClass('action-region-collapse')
                .html('Hide Followed Genes');
        });

        $('#follow-table').on('click', '.action-region-collapse', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('collapseRowByUniqueId', uuid);

            $(this).addClass('action-region-expand')
                .removeClass('action-region-collapse')
                .html('Show Followed Genes');
        });

        $('#follow-table').on('click', '.action-panel-expand', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('expandRowByUniqueId', uuid);

            $(this).removeClass('action-panel-expand')
                .addClass('action-panel-collapse')
                .html('Hide Followed Genes');
        });

        $('#follow-table').on('click', '.action-panel-collapse', function() {

            var uuid = $(this).attr('data-uuid');

            $table.bootstrapTable('collapseRowByUniqueId', uuid);

            $(this).addClass('action-panel-expand')
                .removeClass('action-panel-collapse')
                .html('Show Follow Genes');
        });

        $table.on('expand-row.bs.table', function (e, index, row, $obj) {

            $obj.attr('colspan',12);

            console.log(row.hgnc.substring(1));

            if (row.hgnc.charAt(0) == '!')
                $obj.load( "/api/home/dape/expand/" + row.hgnc.substring(1));
            else
                $obj.load( "/api/home/dare/expand/" + row.hgnc.substring(1));

            return false;
        });

        $('.action-new-ep').on('click', function() {
            $('#modalFollowEp').modal('show');
        });

        $('.action-show-gcep').on('click', function() {

            if ($(this).hasClass('active'))
                return;

            $(this).addClass('active');
            $('.vcep-content').addClass('display-hide');
            $('.gcep-content').removeClass('display-hide');
            $('.action-show-vcep').removeClass('active');

        });

        $('.action-show-vcep').on('click', function() {

            if ($(this).hasClass('active'))
                return;

            $(this).addClass('active');
            $('.gcep-content').addClass('display-hide');
            $('.vcep-content').removeClass('display-hide');
            $('.action-show-gcep').removeClass('active');

        });
	});

</script>

<script src="/js/typeahead.js"></script>
<script src="/js/dashboard.js"></script>
<script src="/js/dropzone.min.js"></script>

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
        else if (row.hgnc.charAt(0) == '!')
        {
            console.log(row);
            return value;
        }
        else
		    return '<a href="/kb/genes/' + row.hgnc + '">' + value + '</a></td>';
	}

    /**
     * For a symbol or region cell
     *
     * @param {*} index
     * @param {*} row
     */
    function ldateFormatter(index, row) {

        if (row.display_last == null || row.display_last == '')
            return '';

        var d = new Date(row.display_last);

        return d.toLocaleDateString();
    }

</script>

@endsection
