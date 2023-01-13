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
					<input type="hidden" name="link" value="{{ Request::url() }}">
                    <input type="hidden" name="gene" value="{{ $record->gene->label ?? '' }}">
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-md-12">
                                <div class="mx-2 mb-3 alert alert-danger" role="alert">
									<b>Note:  All fields are required and must be completed in full before submitting.</b>
								</div>
                                <div class="form-group">
									<label for="gcep" class="col-sm-8 control-label">I am a member of the GCEP that approved this curation:</label>
                                    <div class="col-sm-3">
                                            <div class="radio pull-right mr-5">
                                                <label>
                                                  <input type="radio" name="gcep" value="yes"> Yes
                                                </label>
                                            </div>
                                            <div class="radio ml-2">
                                                <label>
                                                  <input type="radio" name="gcep" value="no" checked> No
                                                </label>
                                            </div>
									</div>
                                </div>
                                <div class="form-group">
									<label for="name" class="col-sm-4 control-label">Full Name:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="name" value="" placeholder="Your First and Last Name" required>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-4 control-label">Email:</label>
									<div class="col-sm-7">
										<input type="text" class="form-control" name="email" value="" placeholder="Your Email Address" required>
									</div>
								</div>
                                <div class="form-group">
                                    <label for="company" class="col-sm-4 control-label">Institution:</label>
                                    <div class="col-sm-7">
                                        <input id="startdate" type="text" class="form-control" name="company" value="" placeholder="Your Company or Institution" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="position" class="col-sm-4 control-label">Position/Title:</label>
                                    <div class="col-sm-7">
                                        <input id="stopdate" type="text" class="form-control" name="position" value="" placeholder="Your Position or Title" required>
                                    </div>
                                </div>
                                <hr \>
                                <div class="ml-5 mb-2 text-primary">Select from the checklist below all categories pertaining to your feedback.  Please include all relevent details in the Feedback field.</div>
                                <div class="form-group">
                                    <!-- Please describe your feedback in more detail in the “Feedback/Comments” field. If you believe evidence is missing or misrepresented, please describe what you expected to see. -->
                                    <label for="input_feedbackType" class="col-sm-4 control-label">Categories:</label>
                                    	<div class="col-sm-7">
											<div>
											<input id="screen" name="type_incorrect" type="checkbox" class="custom-control-input" value="Evidence is incorrect">
											<label class="custom-control-label text-normal" for="screen">I believe some evidence is incorrectly represented</label>
											</div><div>
											<input id="incorrect" name="type_missing" type="checkbox" class="custom-control-input" value="Evidence is missing">
											<label class="custom-control-label text-normal" for="incorrect">I am aware of other evidence not included in this curation</label>
											</div><div>
											<input id="missing" name="type_classification" type="checkbox" class="custom-control-input" value="Disagree with classification">
											<label class="custom-control-label text-normal" for="missing">I don’t agree with the final gene-disease validity classification</label>
											</div><div>
											<input id="assessment" name="type_typo" type="checkbox" class="custom-control-input" value="Typo or grammatical error">
											<label class="custom-control-label text-normal" for="assessment">There is a typographical or grammatical error in this curation</label>
											</div><div>
                                            <input id="assessment" name="type_other" type="checkbox" class="custom-control-input" value="Other">
                                            <label class="custom-control-label text-normal" for="assessment">Other <i>(Please elaborate in your comments below)</i></label>
                                            </div>
										</div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="page" value="{{ Request::url()  }}">
                                </div>
                                <div class="form-group">
                                    <label for="comment" class="col-sm-4 control-label">Feedback:</label>
                                    <div class="col-sm-7">
										<textarea id="selected-regions" name="comment" rows="7" style="width: 100%; max-width: 100%;" required></textarea>
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
