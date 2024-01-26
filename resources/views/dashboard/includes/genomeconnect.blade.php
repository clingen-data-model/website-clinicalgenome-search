<div class="pl-3 pr-3 pr-3 pb-0 collapse" id="collapseGenCon">
    <div id="gencon-toolbar" class="text-right">
        <button class="btn action-gc-gene">Add New Gene</button>
    </div>

    <div class="row mb-3">
        <div class="col-md-12 native-table">
            <table class="table" id="gencon-table" data-toggle="table"
                    data-sort-name="symbol"
                    data-sort-order="asc"
                    data-locale="en-US"
                    data-classes="table table-hover"
                    data-toolbar="#gencon-toolbar"
                    data-toolbar-align="right"
                    data-addrbar="false"
                    data-sortable="true"
                    data-search="true"
                    data-header-style="background: white;"
                    data-filter-control="false"
                    data-filter-control-visible="false"
                    data-id-table="advancedTable"
                    data-search-align="left"
                    data-trim-on-search="false"
                    data-show-search-clear-button="true"
                    data-buttons="table_buttons"
                    data-show-align="left"
                    data-show-fullscreen="false"
                    data-show-columns="false"
                    data-show-columns-toggle-all="false"
                    data-search-formatter="false"
                    data-pagination="true"
                    data-detail-view="true"
                    data-detail-view-icon="false"
                    data-unique-id="ident"
                    data-page-list="[10, 25, 50, 100, 250, all]"
                    data-page-size="25"
                    data-show-footer="true"
                    data-side-pagination="client"
                    data-pagination-v-align="bottom"
                    data-show-extended-pagination="false"
                    data-response-handler="responseHandler"
                    data-header-style="headerStyle"
                    data-show-filter-control-switch="false"
                    data-row-attributes="rowAttributes"
                    >
                <thead>
                    <tr>
                        <th class="col-sm-3" data-field="symbol" data-sortable="true" data-cell-style="symbolClass" data-formatter="formatSymbol">Name</th>
                        <th class="col-sm-3" data-field="variant_count" data-sortable="true" data-align="center">Variant Count</th>
                        <th class="col-sm-2" data-field="display_last" data-sortable="true">Last Updated</th>
                        <th class="col-sm-3" data-field="remove" data-searchable="false" data-align="center">Remove</th>
                        <th data-field="hgnc" data-visible="false"></th>
                        <th data-field="ident" data-visible="false"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($gcs as $gc)
                    <tr >
                        <td scope="row" data-value="{{ $gc->gene->name }}">{{ $gc->gene->name }}</td>
                        <td>
                            {{ $gc->variant_count }}
                        </td>
                        <td>{{ $gc->displayDate($gc->updated_at) }}</td>
                        <td>
                            <span class="action-remove-gc"><i class="fas fa-trash" style="color:red"></i></span>
                        </td>
                        <td>{{ $gc->gene->hgnc_id }}</td>
                        <td>{{ $gc->ident }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
