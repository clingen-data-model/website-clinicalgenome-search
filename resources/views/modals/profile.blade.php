<div class="modal" id="modalProfile" tabindex="-1" role="dialog" aria-labelledby="modalRegisterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div id="profile_modal" class="modal-content">
			<div class=" modal-body-background-primary">
				<div class="modal-header section-heading-groups">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="far fa-times-circle" style="color:#fff !important;"></i></span>
					</button>
					<h3 class="modal-title text-white">Profile</h3>
				</div>
				<form class="form-horizontal" id="profile-form" method="POST" action="/dashboard/profile">
					@csrf
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col-md-12">
									<!--<div class="form-group">
										<label for="inputEmail3" class="col-sm-2 control-label">ClinGen ID</label>
										<div class="col-sm-3">
											pweller@member.clinicalgenome.org
										</div>
									</div> -->
									<div class="form-group">
										<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
										<div class="col-sm-3">
											<input type="text" class="form-control" name="firstname" value="{{ $user->firstname ?? '' }}" placeholder="First Name...">
										</div>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="lastname" value="{{ $user->lastname ?? '' }}" placeholder="Last name...">
										</div>
										<div class="col-sm-3">
											<input type="text" class="form-control" name="credentials" value="{{ $user->credentials ?? '' }}" placeholder="Credentials">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail3" class="col-sm-2 control-label">Organization</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="organization" value="{{ $user->organization ?? '' }}" placeholder="Organization or Institution">
										</div>
									</div>
									<div class="form-group">
										<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
										<div class="col-sm-10">
											<input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" placeholder="Email" disabled >
										</div>
									</div>
									<div class="form-group">
										<label for="inputPassword3" class="col-sm-2 control-label">Password</label>
										<div class="col-sm-5">
											<input type="password" class="form-control" name="password" placeholder="Password">
										</div>
										<div class="col-sm-5">
											<input type="password" class="form-control" name="password_confirm" placeholder="Confirm Password">
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
