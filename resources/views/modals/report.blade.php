<div class="modal" id="modalReport" tabindex="-1" role="dialog" aria-labelledby="modalRegisterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div id="report_modal" class="modal-content">
			<div class=" modal-body-background-primary">
				<div class="modal-header section-heading-groups">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="far fa-times-circle" style="color:#fff !important;"></i></span>
					</button>
					<h3 id="edit-report-title" class="modal-title text-white">New User Report</h3>
				</div>
				<form class="form-horizontal" id="report-form" method="POST" action="/dashboard/reports">
					@csrf
					<input type="hidden" name="ident" value="">
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-md-12">
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Report Title</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="title" value="" placeholder="">
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail3" class="col-sm-2 control-label">Description</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="description" value="" placeholder="">
									</div>
								</div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                                    <div class="col-sm-3">
                                        <input id="startdate" type="text" class="form-control" name="startdate" value="" placeholder="">
                                    </div>
                                    <label for="inputEmail3" class="col-sm-2 control-label">Stop Date</label>
                                    <div class="col-sm-3">
                                        <input id="stopdate" type="text" class="form-control" name="stopdate" value="" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Genes</label>
                                    <div class="col-sm-9">
                                        <input id="selected-genes" type="text" class="form-control selector" data-role="tagsinput abc" name="genes" value="" placeholder="">
                                        <span class="text-muted"><small><i>(Enter * to select all genes or @ to select a group)</i></small></span>
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label for="inputEmail6" class="col-sm-2 control-label">Regions</label>
                                    <div class="col-sm-9">
                                        <input id="selected-regions" type="text" class="form-control selector" name="regions" value="" placeholder="">
                                        <span class="text-muted"><small><i>(Use ; to separate multiple regions)</i></small></span>
                                    </div>
                                </div>-->
                                <div class="form-group mb-0">
                                    <label for="i7" class="col-sm-2 control-label">Regions</label>
                                    <div class="col-sm-8 input-group">
                                        <div class="input-group-btn input-btn-region-prefix pl-3">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="action-select-text">{{  $type ?? 'GRCh37' }}</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                            <li><a href="#" class="action-select-grch" data-uuid="GRCh37">GRCh37</a></li>
                                            <li><a href="#" class="action-select-grch" data-uuid="GRCh38">GRCh38</a></li>
                                            </ul>
                                            <input id="select-gchr" type="hidden" name="type" value="GRCh37">
                                        </div>
                                        <input id="selected-regions" type="text" class="form-control selector search-input" name="regions" value="" type="search" placeholder="Enter genomic coordinates" autocomplete="off">
                                    </div>
                                </div>
							</div><!-- /.col-lg-8 -->
                            <div class="col-md-12">
                                <div class="col-sm-2"></div>
                                <div class="offset-sm-2 col-sm-9">
                                    <span class="text-muted"><small><i>(Use ; to separate multiple regions)</i></small></span>
                                </div>
                            </div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
