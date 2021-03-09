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
    {{-- data-advanced-search="true" --}}
    data-filter-control="true"
    data-filter-control-visible="false"
    data-id-table="advancedTable"
    data-search-align="left"
    data-trim-on-search="false"
    data-show-search-clear-button="true"
    data-buttons="table_buttons"
    {{-- data-show-refresh="true" --}}
    {{-- data-show-toggle="true" --}}
    data-show-align="left"
    data-show-fullscreen="true"
    data-show-columns="true"
    data-show-columns-toggle-all="true"
    data-search-formatter="false"
    data-show-export="true"
    data-export-types="['json', 'xml', 'csv', 'txt', 'sql', 'xlsx', 'pdf']"
    {{-- data-click-to-select="true" --}}
    @if (isset($expand))
    data-detail-view="true"
    data-detail-view-icon="false"
    data-detail-view-by-click="true"
    @endif
    {{-- data-detail-formatter="detailFormatter" --}}
    data-minimum-count-columns="2"
    {{-- data-show-pagination-switch="true" --}}
    data-pagination="true"
    data-id-field="id"
    data-ajax-options="ajaxOptions"
    data-page-list="[10, 25, 50, 100, 250, all]"
    data-page-size="{{ $display_list ?? 25 }}"
    data-show-footer="true"
    data-side-pagination="client"
    data-pagination-v-align="both"
    data-show-extended-pagination="false"
    data-url="{{  $apiurl }}"
    data-response-handler="responseHandler"
    data-header-style="headerStyle"
    data-show-filter-control-switch="true"
    data-group-by="true"
    data-group-by-field="pheno">
</table>