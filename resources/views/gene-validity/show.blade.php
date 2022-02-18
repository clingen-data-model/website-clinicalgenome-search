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


<div class="row">
    <div class="col-sm-12">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#gdvt1" aria-controls="gdvt1" role="tab" data-toggle="tab">Classifications</a></li>
            <li role="presentation"><a href="#gdvt2" aria-controls="gdvt2" role="tab" data-toggle="tab">Scored Genetic Evidence</a></li>
            <li role="presentation"><a href="#gdvt3" aria-controls="gdvt3" role="tab" data-toggle="tab">Genetic Evidence</a></li>
            <li role="presentation"><a href="#gdvt4" aria-controls="gdvt4" role="tab" data-toggle="tab">Experimental Evidence</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="gdvt1">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Classifications</h3>
                        <span class="pull-right">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="active"><a href="#tab1-1" data-toggle="tab">Classification Summary</a></li>
                                <li><a href="#tab1-2" data-toggle="tab">Classification Matrix</a></li>
                            </ul>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1-1">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At</div>
                            <div class="tab-pane" id="tab1-2">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Scored Genetic Evidence</h3>
                        <span class="pull-right">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="active"><a href="#tab2-1" data-toggle="tab">Case Level Variants</a></li>
                                <li><a href="#tab2-2" data-toggle="tab">Case Level Segregation</a></li>
                            </ul>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab2-1">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At</div>
                            <div class="tab-pane" id="tab2-2">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Genetic Evidence</h3>
                        <span class="pull-right">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="active"><a href="#tab3-1" data-toggle="tab">Case Level Family Segregation w/o Proband Data or Scored Proband</a></li>
                                <li><a href="#tab3-2" data-toggle="tab">Case-Control</a></li>
                            </ul>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab3-1">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At</div>
                            <div class="tab-pane" id="tab3-2">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="gdvt4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Experimental Evidence</h3>
                        <span class="pull-right">
                            <!-- Tabs -->
                            <ul class="nav panel-tabs">
                                <li class="active"><a href="#tab4-1" data-toggle="tab">Experimental Evidence</a></li>
                            </ul>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab4-1">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At</div>
                        </div>
                    </div>
                </div>
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
<style>
.panel-tabs {
    position: relative;
    bottom: 30px;
    clear:both;
    border-bottom: 1px solid transparent;
}

.panel-tabs > li {
    float: left;
    margin-bottom: -1px;
}

.panel-tabs > li > a {
    margin-right: 2px;
    margin-top: 4px;
    line-height: .85;
    border: 1px solid transparent;
    border-radius: 4px 4px 0 0;
    color: #ffffff;
}

.panel-tabs > li > a:hover {
    border-color: transparent;
    color: #ffffff;
    background-color: transparent;
}

.panel-tabs > li.active > a,
.panel-tabs > li.active > a:hover,
.panel-tabs > li.active > a:focus {
    color: #fff;
    cursor: default;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    background-color: rgba(255,255,255, .23);
    border-bottom-color: transparent;
}
</style>
    <link href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css" rel="stylesheet">
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.19.1/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script>

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


  function inittable() {
    $('#geclv').bootstrapTable();
    $('#gecls').bootstrapTable();
    $('#geclfs').bootstrapTable();
    $('#gecc').bootstrapTable();
    $table.bootstrapTable();


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

  function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find('i.fas')
        .toggleClass('fa-compress-arrows-alt fa-expand-arrows-alt');
}

$(function() {

  // Set cursor to busy prior to table init
  //$("body").css("cursor", "progress");

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

  $('#tag_genetic_evidence_case_level_with_proband').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_segregation').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_case_level_without_proband').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_genetic_evidence_case_control').on('hide.bs.collapse show.bs.collapse', toggleChevron);
  $('#tag_experimental_evidence').on('hide.bs.collapse show.bs.collapse', toggleChevron);

});

</script>
@endsection
