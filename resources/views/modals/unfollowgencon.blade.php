<div class="modal" id="modalUnFollowGenCon" tabindex="-1" role="dialog" aria-labelledby="modalUnFollowGenConTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalUnFollowGenConTitle">Unfollow This Gene</h5>
			</div>
			<form id="unfollow_gencon_form" method="POST" action="">
				@csrf
				<div class="modal-body">
					<p>Click on submit to remove this gene.</p>
						<div class="input-group">
							<input type="hidden" id="unfollow-gencon-field" name="ident" value="{{ $ident }}">
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
