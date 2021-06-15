@extends('layouts.report')

@section('content-heading')

<div class="row mb-1 mt-1">
	<div class="col-md-12">
        <h1>{{ $title->title }}</h1>
        <h5>{{ $title->description }}</h5>
        <h4>Report Parameters:</h4><div class="row">
            @foreach ($params as $param)
                <div class="col-md-4 border-right">
                    <dl>
                        <dt>Start Date</dt>
                        <dd>{{ $param['start_date'] }}</dd>
                        <dt>Stop Date</dt>
                        <dd>{{ $param['stop_date'] }}</dd>
                        <dt>Genes</dt>
                        <dd>
                            {{ implode(', ', $param['genes']) == '*' ? 'All Genes' :  implode(', ', $param['genes']) }}
                        </dd>
                    </dl>
                </div>
                @endforeach
    </div>
</div>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-3">
            <div id="toolbar" class="text-right">

            </div>
            <div class="row mb-3">
                <div class="col-md-12 native-table">
                    <table class="table" id="table" data-toggle="table"
                                    data-sort-name="symbol"
                                    data-sort-order="asc"
                                    data-locale="en-US"
                                    data-classes="table table-hover"
                                    data-toolbar="#toolbar"
                                    data-toolbar-align="right"
                                    data-addrbar="true"
                                    data-search="true"
                                    data-header-style="background: white;"
                                    data-filter-control="true"
                                    data-filter-control-visible="false"
                                    data-id-table="advancedTable"
                                    data-search-align="left"
                                    data-trim-on-search="false"
                                    data-show-search-clear-button="true"
                                    data-buttons="table_buttons"
                                    data-show-align="left"
                                    data-show-fullscreen="true"
                                    data-show-columns="true"
                                    data-show-columns-toggle-all="true"
                                    data-search-formatter="false"
                                    data-show-export="true"
                                    data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'xlsx', 'pdf']"
                                    data-minimum-count-columns="2"
                                    data-pagination="true"
                                    data-id-field="id"
                                    data-page-list="[10, 25, 50, 100, 250, all]"
                                    data-page-size="50"
                                    data-show-footer="true"
                                    data-side-pagination="client"
                                    data-pagination-v-align="both"
                                    data-show-extended-pagination="false"
                                    data-response-handler="responseHandler"
                                    data-header-style="headerStyle"
                                    data-show-filter-control-switch="true"
                                    data-group-by="true"
                                    data-group-by-field="pheno"
                                    >
                    <thead>
                        <tr>
                            <th class="col-sm-2" data-field="symbol" data-sortable="true" data-filter-control="input" data-formatter="formatSymbol">Gene</th>
                            <th class="col-sm-2" data-field="curation" data-sortable="true" data-filter-control="input">Curations</th>
                            <th class="col-sm-2" data-field="date" data-sortable="true" data-filter-control="input">Date of Change</th>
                            <th class="col-sm-2" data-field="actiivity" data-sortable="true" data-filter-control="input">Activity</th>
                            <th class="col-sm-2" data-field="notes" data-sortable="true" data-filter-control="input">Change Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr data-hgnc="{{ $report->element->hgnc_id }}">
                            <td scope="row" class="table-symbol">{{ $report->new->gene_label }}</td>
                            <td>
                                @if(!empty($report->element_id))
                                <img src="/images/clinicalValidity-{{ $report->element->hasActivity('validity') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/dosageSensitivity-{{ $report->element->hasActivity('dosage') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/clinicalActionability-{{ $report->element->hasActivity('actionability') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/variantPathogenicity-{{ $report->element->hasActivity('varpath') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/Pharmacogenomics-{{ $report->element->hasActivity('pharma') ? 'on' : 'off' }}.png" width="22" height="22">
                                @endif
                            </td>
                            <td>{{ $report->change_date }}</td>
                            <td>{{ $report->activity }}</td>
                            <td>{{ $report->description == null ? '' :  implode(',', $report->description )}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
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

<script src="/js/genetable.js"></script>

<script>

/**
**
**		Globals
**
*/

var $table = $('#table');
var lightstyle = true;

$(function() {

    // make some mods to the search input field
    var search = $('.fixed-table-toolbar .search input');
    search.attr('placeholder', 'Search in table');

    $( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $("button[name='filterControlSwitch']").attr('title', 'Column Search');
    $("button[aria-label='Columns']").attr('title', 'Show/Hide More Columns');

});

function formatSymbol(value, row, index)
{
    //console.log(row._data.hgnc);

    return '<a href="/kb/genes/' + row._data.hgnc + '">' + value + '</a></td>';
}

</script>

@endsection
