<div class="modal" id="modalReport" tabindex="-1" role="dialog" aria-labelledby="modalRegisterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div id="report_modal" class="modal-content">
			<div class=" modal-body-background-primary">
				<div class="modal-header section-heading-groups">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="far fa-times-circle" style="color:#fff !important;"></i></span>
					</button>
					<h3 class="modal-title text-white">New User Report</h3>
				</div>
				<form class="form-horizontal" id="report-form" method="POST" action="/dashboard/reports">
					@csrf
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
											<input id="selected-genes" type="text" class="form-control selector" data-role="tagsinput" name="genes" value="" placeholder="">
										</div>
									</div>
							</div><!-- /.col-lg-8 -->				
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
