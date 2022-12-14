<div class="modal" id="modalBeta" tabindex="-1" role="dialog" aria-labelledby="modalBetaTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div id="beta-modal" class="modal-content">
			<div class=" modal-body-background-primary">
				<div class="modal-header section-heading-groups">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="far fa-times-circle" style="color:#fff !important;"></i></span>
					</button>
					<h3 id="edit-beta-title" class="modal-title text-white">Evidence Feedback</h3>
				</div>
				<form class="form-horizontal" id="beta-form" method="" action="">
					@csrf
					<input type="hidden" name="ident" value="">
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-md-12">
                                <div class="mx-5 mb-3">
									Please complete all field.
								</div>
								<div class="form-group">
									<label for="name" class="col-sm-4 control-label">Full Name <i>(Required)</i></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="name" value="" placeholder="Your First and Last Name" required>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-4 control-label">Email <i>(Required)</i></label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="email" value="" placeholder="Your Email Address" required>
									</div>
								</div>
                                <div class="form-group">
                                    <label for="company" class="col-sm-4 control-label">Institution <i>(Required)</i></label>
                                    <div class="col-sm-7">
                                        <input id="startdate" type="text" class="form-control" name="company" value="" placeholder="Your Company or Institution" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="position" class="col-sm-4 control-label">Position/Title <i>(Required)</i></label>
                                    <div class="col-sm-7">
                                        <input id="stopdate" type="text" class="form-control" name="position" value="" placeholder="Your Position or Title" required>
                                    </div>
                                </div>
                                <div class="form-group">
									<label for="input_feedbackType" class="col-sm-4 control-label">Type <i>(Required)</i></label>
                                    	<div class="col-sm-7">
											<div>
											<input id="screen" name="input_feedbackType" type="radio" class="custom-control-input" value="1">
											<label class="custom-control-label text-normal" for="screen">Screen / Display Issue</label>
											</div><div>
											<input id="incorrect" name="input_feedbackType" type="radio" class="custom-control-input" value="2" checked>
											<label class="custom-control-label text-normal" for="incorrect">Evidence Data Incorrect</label>
											</div><div>
											<input id="missing" name="input_feedbackType" type="radio" class="custom-control-input" value="3">
											<label class="custom-control-label text-normal" for="missing">Evidence Data Missing</label>
											</div><div>
											<input id="assessment" name="input_feedbackType" type="radio" class="custom-control-input" value="4">
											<label class="custom-control-label text-normal" for="assessment">Curation Assessment</label>
											</div>
										</div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="page" value="{{ Request::url()  }}">
                                </div>
                                <div class="form-group">
                                    <label for="comment" class="col-sm-4 control-label">Feedback / Comments <i>(Requiired)</i></label>
                                    <div class="col-sm-7">
										<textarea id="selected-regions" name="comment" rows="8" cols="60" required></textarea>
                                    </div>
                                </div>

							</div><!-- /.col-lg-8 -->
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Submit Feedback</button>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
