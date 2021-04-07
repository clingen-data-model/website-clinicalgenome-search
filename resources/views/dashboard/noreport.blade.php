@extends('layouts.report')

@section('content-heading')

<div class="row mb-1 mt-1">
	<div class="col-md-12">
        <h1>This report has expired or been deleted by the owner</h1>
    </div>
</div>

@endsection

@section('content')

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
