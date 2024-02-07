<div class="modal" id="modalUploadGenomeConnect" tabindex="-1" role="dialog" aria-labelledby="modalUploadGenomeConnectTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title text-white" id="modalUploadGenomeConnectTitle">Follow a new gene</h3>
			</div>
			<form id="upload_gencon_form" method="POST" action="/kb/genomeconnect/upload" class="form-horizontal" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<p>Click on browse to select a file.</p>
					<div>
						<input type="file" name="file">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary mr-auto">Submit
						
					</button>
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Cancel">
					Close
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
