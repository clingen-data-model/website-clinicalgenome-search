<div class="pl-3 pr-3 pr-3 pb-0 collapse in" id="collapseFollow">
    <div id="follow-toolbar" class="text-right">
        <button class="btn btn-block action-new-gene">Add New Gene To Follow</button>  
    </div>

    <div class="row mb-3">  
        <div class="col-md-12 native-table">
            <table class="table" id="follow-table" data-toggle="table"
                    data-sort-name="symbol"
                    data-sort-order="asc"
                    data-locale="en-US"
                    data-classes="table table-hover"
                    data-toolbar="#follow-toolbar"
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
                    data-id-field="id"
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
                        <th class="col-sm-2" data-field="symbol" data-sortable="true" data-cell-style="symbolClass" data-formatter="formatSymbol">Name</th>
                        <th class="col-sm-3" data-field="curations" data-searchable="false" data-align="center">Curation Status</th>
                        <th class="col-sm-2" data-field="display_last" data-sortable="true">Last Updated</th>
                        <th class="col-sm-2" data-field="notify" data-searchable="false" data-align="center">Notify</th>
                        <th class="col-sm-2" data-field="unfollow" data-searchable="false" data-align="center">Unfollow</th>
                        <th data-field="hgnc" data-visible="false"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($genes as $gene)
                    <tr >
                        <td scope="row" data-value="{{ $gene->name }}">{{ $gene->name }}</td>
                        <td>
                            <img src="/images/clinicalValidity-{{ $gene->hasActivity('validity') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-{{ $gene->hasActivity('dosage') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/clinicalActionability-{{ $gene->hasActivity('actionability') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-{{ $gene->hasActivity('varpath') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-{{ $gene->hasActivity('pharma') ? 'on' : 'off' }}.png" width="22" height="22">
                        </td>
                        <td>{{ $gene->displayDate($gene->date_last_curated) }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="text-left btn btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="selection">{{ $gene->hgnc_id == '*' || $gene->hgnc_id[0] == '@' ? $notification->setting($gene->hgnc_id) : $notification->setting($gene->name) }}</span><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a data-value="Daily">Daily</a></li>
                                    <li><a data-value="Weekly">Weekly</a></li>
                                    <li><a data-value="Monthly">Monthly</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a data-value="Default">Default</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a data-value="Pause">Pause</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <span class="action-follow-gene"><i class="fas fa-star" style="color:green"></i></span>
                        </td>
                        <td>{{ $gene->hgnc_id }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>