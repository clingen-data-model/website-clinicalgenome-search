<div class="modal" id="modalUnFollowGene" tabindex="-1" role="dialog" aria-labelledby="modalUnFollowGeneTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalUnFollowGeneTitle">Unfollow This Gene</h5>
			</div>
			<form id="unfollow_form" method="POST" action="">
				@csrf
				<div class="modal-body">
					<p>Click on submit to unfollow this gene.</p>
						<div class="input-group">
							<input type="hidden" name="gene" value="{{ $gene }}">
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
