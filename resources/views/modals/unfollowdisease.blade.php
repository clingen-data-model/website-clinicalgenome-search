<div class="modal" id="modalUnFollowDisease" tabindex="-1" role="dialog" aria-labelledby="modalUnFollowDiseaseTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalUnFollowDiseaseTitle">Unfollow This Disease</h5>
			</div>
			<form id="unfollow_disease_form" method="POST" action="">
				@csrf
				<div class="modal-body">
					<p>Click on submit to unfollow this disease.</p>
						<div class="input-group">
							<input type="hidden" id="unfollow-disease-field" name="disease" value="{{ $disease }}">
						</div>
				</div>
				<div class="modal-footer">
					@if (!Auth::check())
					<a href="/login" class="btn btn-outline-secondary float-left">
						Login
					</a>
					<a href="/register" type="button" class="btn btn-outline-secondary float-left">
							Create Account
					</a>
					@endif
					<button type="submit" class="btn btn-primary mr-auto">Submit
					</button>
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
					Close
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
