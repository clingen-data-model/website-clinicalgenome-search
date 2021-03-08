@extends('layouts.report')

@section('content-heading')

<div class="row mb-1 mt-1">
	<div class="col-md-12 pl-0">
        <h1>{{ $title->title }}</h1>
        <h4>{{ $title->description }}</h4>
    </div>
</div>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-3">
            <div class="row mb-3">  
                <div class="col-md-12 native-table">
                    <div id="toolbar" class="text-right">
                        ;
                    </div>
                    <table class="table" id="table" data-toggle="table"
                                    data-sort-name="symbol"
                                    data-sort-order="asc"
                                    data-locale="en-US"
                                    data-classes="table table-hover"
                                    data-toolbar="#toolbar"
                                    data-toolbar-align="right"
                                    data-addrbar="true"
                                    data-sortable="true"
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
                            <th class="col-sm-2" data-field="symbol" data-sortable="true">Gene</th>
                            <th class="col-sm-2" data-sortable="true">Change Date</th>
                            <th class="col-sm-2" data-sortable="true">Change Type</th>
                            <th class="col-sm-2" data-sortable="true">Change Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr>
                            <td scope="row" class="table-symbol">{{ $report->new->gene_label }}</td>
                            <td>{{ $report->change_date }}</td>
                            <td>{{ $report->change_type }}</td>
                            <td></td>
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

<script src="/js/bootstrap-table-filter-control.js"></script>

<script src="/js/genetable.js"></script>

<script>
	
    $(function() {
       // var $table = $('#table')
    
    });

</script>

@endsection
