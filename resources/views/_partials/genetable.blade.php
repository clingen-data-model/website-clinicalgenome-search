<!-- The toolbar -->
<div id="toolbar">
    {!! $tools ?? '' !!}
</div>
<!-- The table -->
<table
    id="table"
    data-classes="table table-striped table-hover"
    data-toolbar="#toolbar"
    data-addrbar="true"
    data-search="true"
    data-search-align="left"
    data-trim-on-search="false"
    {{-- data-show-refresh="true" --}}
    data-show-toggle="true"
    data-show-align="left"
    data-show-fullscreen="true"
    data-show-columns="true"
    data-show-columns-toggle-all="true"
    {{-- data-detail-view="true" --}}
    data-show-export="true"
    {{-- data-click-to-select="true" --}}
    {{-- data-detail-formatter="detailFormatter" --}}
    data-minimum-count-columns="2"
    {{-- data-show-pagination-switch="true" --}}
    data-pagination="true"
    data-id-field="id"
    data-page-list="[10, 25, 50, 100, all]"
    data-page-size="100"
    data-show-footer="true"
    data-side-pagination="client"
    data-pagination-v-align="both"
    data-show-extended-pagination="false"
    data-url="{{  $apiurl }}"
    data-response-handler="responseHandler">
</table>