<div class="modal" id="modalFollowGene" tabindex="-1" role="dialog" aria-labelledby="modalFollowGeneTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFollowGeneTitle">Follow This Gene</h5>
			</div>
			<form id="follow_form" method="POST" action="" class="form-horizontal">
				@csrf
				<div class="modal-body">
					<p>Click on submit to follow this gene.</p>
					<input type="hidden" name="gene" value="{{ $gene }}">
				</div>
				<div class="modal-footer">
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
