<div class="pl-3 pr-3 pr-3 pb-0 collapse" id="collapseReports">
    <div id="report-folders" class="row mb-4"> 
        <div class="col-md-3 pb-2 folder-effects action-select-folder active">
            <img class="size" src="/assets/images/folder.png">
            <div class="caption center-block" data-type="1">Notifications
                <span class="badge">{{ $system_reports ?? '0' }}</span>
            </div>
        </div>
        <div class="col-md-3 pb-2 folder-effects action-select-folder border">
            <img class="size" src="/assets/images/folder.png">
            <div class="caption center-block" data-type="10">Custom Reports
                <span class="badge">{{ $user_reports ?? '0' }}</span>
            </div>
        </div>
        <div class="col-md-3 pb-2 folder-effects action-select-folder">
            <img class="size" src="/assets/images/folder.png">
            <div class="caption center-block" data-type="20">Shared Reports
                <span class="badge">{{ $shared_reports ?? '0' }}</span>
            </div>
        </div>
        <div class="col-md-3 mt-4 text-center">
            <i class="fas fa-plus-circle fa-4x ml-4 action-new-report" style="color:#ff0066"></i>
            <div class="center-block mt-2 ml-4">
                Create New Report
            </div>
        </div>
    </div>

    <hr>
    
    <div id="report-toolbar" class="text-right">
        
    </div>

    <div id="report-view" class="row mb-3">  
        <div class="col-md-12 native-table">
            <table class="reports-table" id="table" data-toggle="table"
                    data-sort-name="symbol"
                    data-sort-order="asc"
                    data-locale="en-US"
                    data-classes="table table-hover"
                    data-toolbar="#report-toolbar"
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
                    data-detail-view="true"
                    {{-- data-click-to-select="true" --}}
                    {{-- data-detail-view-icon="false" --}}
                    {{-- data-detail-view-by-click="true" --}}
                    data-detail-formatter="reportDetailFormatter"
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
                    >
                <thead>
                    <tr>
                        <th class="col-sm-2" data-field="title" data-sortable="true">Title</th>
                        <th class="col-sm-3" data-field="type" data-sortable="true">Type</th>
                        <th class="col-sm-2" data-field="display_created" data-sortable="true">Created</th>
                        <th class="col-sm-2" data-field="display_last" data-sortable="true">Last Ran</th>
                        <th class="col-sm-2" data-field="remove" data-align="center">Actions</th>
                        <th data-field="ident" data-visible="false"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                    <tr>
                        <td scope="row" class="table-symbol"><a href="{{ route('dashboard-show-report', ['id' => $report->ident]) }}" target="_report" >{{ $report->title }}</a></td>
                        <td>{{ $report->display_type }}</td>
                        <td>{{ $report->display_created_date }}</td>
                        <td>{{ $report->display_last_date }}</td>
                        <td>
                            @if ($report->type == App\Title::TYPE_USER)
                                <span class="action-edit-report mr-2" data-uuid="{{ $report->ident }}" title="Edit Report"><i class="fas fa-edit" style="color:black"></i></span>
                            @endif
                            @if ($report->status == App\Title::STATUS_ACTIVE)
                                <span class="action-lock-report mr-2" data-uuid="{{ $report->ident }}" title="Lock Report"><i class="fas fa-unlock" style="color:lightgray"></i></span>
                            @else
                                <span class="action-unlock-report mr-2" data-uuid="{{ $report->ident }}" title="UnLock Report"><i class="fas fa-lock" style="color:red"></i></span>
                            @endif
                            <span class="action-share-report mr-2" data-uuid="{{ $report->ident }}" title="Share Report"><i class="fas fa-share" style="color:lightgray"></i></span>                            
                            <span class="action-remove-report" data-uuid="{{ $report->ident }}" title="Delete Report"><i class="fas fa-trash" style="color:red"></i></span>
                        </td>
                        <td>{{ $report->ident }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>