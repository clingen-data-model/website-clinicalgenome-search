<!-- The toolbar -->					
<div id="toolbar">
    {!! $tools ?? '' !!}
</div>
<!-- The table -->
<table
    id="table"
    data-classes="table table-borderless table-striped table-hover"
    data-toolbar="#toolbar"
    data-addrbar="true"
    data-search="true"
    data-show-refresh="true"
    data-show-toggle="true"
    data-show-fullscreen="true"
    data-show-columns="true"
    data-show-columns-toggle-all="true"
    data-detail-view="true"
    data-show-export="true"
    data-click-to-select="true"
    data-detail-formatter="detailFormatter"
    data-minimum-count-columns="2"
    data-show-pagination-switch="true"
    data-pagination="true"
    data-id-field="id"
    data-page-list="[10, 25, 50, 100, all]"
    data-page-size="100"
    data-show-footer="false"
    data-side-pagination="server"
    data-url="{{  $apiurl }}"
    data-response-handler="responseHandler">
</table>