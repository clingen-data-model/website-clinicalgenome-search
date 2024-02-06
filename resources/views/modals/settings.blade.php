<div class="modal" id="modalSettings" tabindex="-1" role="dialog" aria-labelledby="modalSettingsTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div id="settings_modal" class="modal-content">
			<div class=" modal-body-background-primary">
				<div class="modal-header section-heading-groups">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="far fa-times-circle" style="color:#fff !important;"></i></span>
					</button>
					<h3 class="modal-title text-white">Settings</h3>
				</div>
				<form class="form-horizontal">
					<div class="modal-body">
						<ul class="col-md-12 nav nav-tabs mt-1">
							<li role="presentation" class="active">
								<a id="settings-tabs-global" href="#globals" data-toggle="tab">Globals</a>
							</li>
							<li role="presentation" class="">
								<a href="#defaults" data-toggle="tab">Defaults</a>
							</li>
							<li role="presentation" class="">
								<a href="#lists"  data-toggle="tab">Lists</a>
							</li>
							<li role="presentation" class="">
								<a href="#profile"  data-toggle="tab">Profile</a>
							</li>
							<li role="interests" class="">
								<a href="#interests"  data-toggle="tab">Interests</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">

							<!-- Notificatiions Section -->
							<div class="tab-pane fade in active" id="globals" >
								<div class="row mb-3">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">Global Notifications</div>
											<div class="panel-body">
												<div class="form-group">
													<div class="col-sm-12">
														<div class="row my-2">
															<div class="col-sm-2 text-right pr-0">
																Notifications are:
															</div>
															<div class="col-sm-10 pl-3">
																<div class="form-inline p-0 m-0 col-sm-12 mt-1">
																	<i class="fas fa-toggle-{{ $notification->frequency['global'] }} fa-lg action-toggle-notifications"></i>
																	<span class="ml-2 hgnc text-muted action-toggle-notifications-text">{{ ucfirst($notification->frequency['global']) }}</span>
																</div>
																<div class="form-inline p-0 m-0 col-sm-12 mt-3">
																	<i class="fas fa-toggle-{{ $notification->frequency['global_pause'] }} fa-lg action-toggle-pause"></i>
																	<span class="ml-2 hgnc text-muted">Pause notifications until: </span>
																	<input name="pause_date" id="ds_pause_date" class="ml-2 api-update" type="text" placeholder="Choose date" value="{{ $notification->frequency['global_pause_date'] }}">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Defaults -->
							<div class="tab-pane fade in" id="defaults" >
								<div class="row mb-3">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">Defaults</div>
											<div class="panel-body">
												<div class="form-group">
													<div class="row my-2">
														<div class="col-sm-2 text-right mt-1 pr-0">
															Send Emails To:
														</div>
														<div class="col-sm-9 ml-1">
															<input type="text" class="form-control api-update" name="primary_email" value="{{  $notification->primary['email'] }}">
														</div>
													</div>
													<div class="row my-2">
														<div class="col-sm-2 text-right mt-1 pr-0">
															Additional Emails:
														</div>
														<div class="col-sm-9 ml-1">
															<input type="text" class="form-control api-update" placeholder="Type in emails..." name="secondary_email" value="{{  $notification->secondary['email'] }}">
															<small>Add as many emails you want with a comma (,) between each.</small>
														</div>
													</div>
												</div>
												<hr>
												<div class="form-group">
													<div class="col-sm-8">
														<h5>Change Notices</h5>
														Choose how often to check for changes.  You can override this on a per gene basis.
													</div>
													<div class="col-sm-4 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="frequency" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_DAILY) }}>
																Daily
															</label>
															</div>
															<div class="radio">
															<label>
																<input type="radio" class="api-update" name="frequency" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_WEEKLY) }}>
																Weekly
															</label>
															</div>
															<div class="radio">
															<label>
																<input type="radio" class="api-update" name="frequency" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_MONTHLY) }}>
																Monthly
															</label>
														</div>
													</div>
												</div>
												<hr />
												<div class="form-group">
													<div class="col-sm-8">
														<h5>First Curation</h5>
														Choose whether to be notified on the first curation of a gene, regardless of any other settings
													</div>
													<div class="col-sm-4 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="first" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('first', App\Notification::FREQUENCY_DAILY) }}>
																Notify
															</label>
															</div>
															<div class="radio">
															<label>
																<input type="radio" class="api-update" name="first" value="{{ App\Notification::FREQUENCY_NONE }}" {{ $notification->checked('first', App\Notification::FREQUENCY_NONE) }}>
																Do not notify
															</label>
															</div>
													</div>
												</div>
												<hr />
												<div class="form-group">
													<div class="col-sm-8">
														<h5>Summary Report</h5>
														Choose how often to receive summary reports in addition to the regular change notifications.
													</div>
													<div class="col-sm-4 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="summary" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_WEEKLY) }}>
																Weekly
															</label>
															</div>
															<div class="radio">
															<label>
																<input type="radio" class="api-update" name="summary" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_MONTHLY) }}>
																Monthly
															</label>
														</div>
															<div class="radio">
															<label>
																<input type="radio" class="api-update" name="summary" value="{{ App\Notification::FREQUENCY_QUARTERLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_QUARTERLY) }}>
																Quarterly
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="summary" value="{{ App\Notification::FREQUENCY_ANNUAL }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_ANNUAL) }}>
																Annual
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- /.col-lg-8 -->
								</div>
							</div>

							<!-- List Settings -->
							<div class="tab-pane fade" id="lists">
								<div class="row mb-3">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">List Size</div>
											<div class="panel-body">
												<div class="form-group">
													<div class="col-sm-8">
														Choose the global default list size for all pages.
													</div>
													<div class="col-sm-4 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="display_list" value="25" {{ $user->preferences['display_list'] == '25' ? 'checked' : '' }}>
																25
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="display_list" value="50" {{ $user->preferences['display_list'] == '50' ? 'checked' : '' }}>
																50
															</label>
														</div>
														<div class="radio">
																<label>
																	<input type="radio" class="api-update" name="display_list" value="100" {{ $user->preferences['display_list'] == '100' ? 'checked' : '' }}>
																	100
																</label>
														</div>
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="display_list" value="250" {{ $user->preferences['display_list'] == '250' ? 'checked' : '' }}>
																250
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="radio" class="api-update" name="display_list" value="All" {{ $user->preferences['display_list'] == 'All' ? 'checked' : '' }}>
																All
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Profile -->
							<div class="tab-pane fade" id="profile">
								<div class="row mb-3">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">Profile</div>
											<div class="panel-body">
												<!-- <div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">ClinGen ID</label>
													<div class="col-sm-3 mt-2">
														pweller@member.clinicalgenome.org
													</div>
												</div> -->
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
													<div class="col-sm-3">
														<input type="text" class="form-control api-update" name="firstname" value="{{ $user->firstname ?? '' }}" placeholder="First Name...">
													</div>
													<div class="col-sm-4">
														<input type="text" class="form-control api-update" name="lastname" value="{{ $user->lastname ?? '' }}" placeholder="Last name...">
													</div>
													<div class="col-sm-3">
														<input type="text" class="form-control api-update" name="credentials" value="{{ $user->credentials ?? '' }}" placeholder="Credentials">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">Organization</label>
													<div class="col-sm-10">
														<input type="text" class="form-control api-update" name="organization" value="{{ $user->organization ?? '' }}" placeholder="Organization or Institution">
													</div>
												</div>
												<div class="form-group">
													<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
													<div class="col-sm-10">
														<input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" placeholder="Email" disabled>
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
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Interests -->
							<div class="tab-pane fade" id="interests">
								<div class="row mb-3">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">Interests</div>
											<div class="panel-body">
												<div class="form-group">
													<div class="col-sm-6">
														<h5><img class="ma-4" src="/images/clinicalValidity-on.png" heigth="50" width="50">
															Gene-Disease Validity
														</h5>
													</div>
													<div class="col-sm-6 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="validity_interest" {{ in_array('validity', $user->profile['interests']) ? 'checked' : '' }}>
																Yes, I'm interested
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="validity_notify" {{ $user->notification->checkGroup('@AllValidity') ? 'checked' : '' }}>
																Notify on all Validity related updates
															</label>
														</div>
													</div>
												</div>
												<hr />
												<div class="form-group">
													<div class="col-sm-6">
														<h5><img class="ma-4" src="/images/dosageSensitivity-on.png" heigth="50" width="50">
															Dosage Sensitivity
														</h5>
													</div>
													<div class="col-sm-6 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="dosage_interest" {{ in_array('dosage', $user->profile['interests']) ? 'checked' : '' }}>
																Yes, I'm interested
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="hidden" name="dosage_notify" value="0">
																<input type="checkbox" class="api-update" name="dosage_notify" {{ $user->notification->checkGroup('@AllDosage') ? 'checked' : '' }}>
																Notify on all Dosage related updates
															</label>
														</div>
													</div>
												</div>
												<hr />
												<div class="form-group">
													<div class="col-sm-6">
														<h5><img class="ma-4" src="/images/clinicalActionability-on.png" heigth="50" width="50">
															Clinical Actionability
														</h5>
													</div>
													<div class="col-sm-6 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="actionability_interest" {{ in_array('actionability', $user->profile['interests']) ? 'checked' : '' }}>
																Yes, I'm interested
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="actionability_notify" {{ $user->notification->checkGroup('@AllActionability') ? 'checked' : '' }}>
																Notify on all Actionability related updates
															</label>
														</div>
													</div>
												</div>
												<hr />
												<div class="form-group">
													<div class="col-sm-6">
														<h5><img class="ma-4" src="/images/variantPathogenicity-on.png" heigth="50" width="50">
															Variant Pathogenicity
														</h5>
													</div>
													<div class="col-sm-6 border-left" style="border-left-width: 10px !important">
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="variant_interest" {{ in_array('variant', $user->profile['interests']) ? 'checked' : '' }}>
																Yes, I'm interested
															</label>
														</div>
														<div class="radio">
															<label>
																<input type="checkbox" class="api-update" name="variant_notify" {{ $user->notification->checkGroup('@AllVariant') ? 'checked' : '' }}>
																Notify on all Variant Pathogenicity related updates
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="modal-footer pb-0">
						<div class=" col-md-8 alert alert-info text-left">
							<span id="setting-alert-message"><strong>Auto-Update On:</strong>  All setting changes are automatically saved.</span>
						</div>
						<div class="col-md-4 mt-2">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
