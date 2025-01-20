<div class="pl-3 pr-3 pr-3 pb-0 collapse" id="collapseDiseases">
    <div id="disease-toolbar" class="text-right">
        <button class="btn action-new-disease">Follow New Disease</button>
    </div>

    <div class="row mb-3">
        <div class="col-md-12 native-table">
            <table class="table" id="disease-table" data-toggle="table"
                    data-sort-name="symbol"
                    data-sort-order="asc"
                    data-locale="en-US"
                    data-classes="table table-hover"
                    data-toolbar="#disease-toolbar"
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
                        <th class="col-sm-2" data-field="symbol" data-sortable="true" data-cell-style="symbolClass" data-formatter="">Name</th>
                        <th class="col-sm-3" data-field="curations" data-searchable="false" data-align="center">Curation Status</th>
                        <th class="col-sm-2" data-field="display_last" data-sortable="true" data-formatter="ldateFormatter">Last Updated</th>
                        <th class="col-sm-2" data-field="notify" data-searchable="false" data-align="center">Notify</th>
                        <th class="col-sm-2" data-field="unfollow" data-searchable="false" data-align="center">Unfollow</th>
                        <th data-field="curie" data-visible="false"></th>
                        <th data-field="ident" data-visible="false"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($diseases as $disease)
                    <tr >
                        <td scope="row" data-value="{{ $disease->label }}">{{ $disease->label }} {{ $disease->curie }} </td>
                        <td>
                            <img src="/images/clinicalValidity-{{ $disease->hasActivity('validity') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-{{ $disease->hasActivity('dosage') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/clinicalActionability-{{ $disease->hasActivity('actionability') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-{{ $disease->hasActivity('varpath') ? 'on' : 'off' }}.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-{{ $disease->hasActivity('pharma') ? 'on' : 'off' }}.png" width="22" height="22">
                        </td>
                        <td>{{ $disease->last_curated_date }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="text-left btn btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="selection">{{ $notification->setting($disease->curie) }}</span><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu action-disease-frequency">
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
                            <span class="action-follow-disease"><i class="fas fa-star" style="color:green"></i></span>
                        </td>
                        <td>{{ $disease->curie }}</td>
                        <td>{{ $disease->ident }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
