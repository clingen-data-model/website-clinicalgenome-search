<div class="modal" id="modalSearchGenomeConnect" tabindex="-1" role="dialog" aria-labelledby="modalSearchGenomeConnectTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title text-white" id="modalSearchGenomeConnectTitle">Follow a new gene</h3>
			</div>
			<form id="gc_search_form" method="POST" action="{{ route('gene-search') }}">
				@csrf
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-md-12">
							<div id="section_search_wrapper_2" class="mt-2 mb-2 input-group input-group-md">
								<input type="hidden" class="buildtype" name="type" value="">
								<div class="form-group">
									<label for="search" class="col-sm-2 control-label">Gene</label>
									<div class="col-sm-10">
										<input type="text" class="form-control queryFindGenomeConnect" name="search[]" size="50" value="" placeholder="Begin typing a symbol name">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
