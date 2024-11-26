<div class="modal" id="modalSearchDisease" tabindex="-1" role="dialog" aria-labelledby="modalSearchDiseaseTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title text-white" id="modalSearchDiseaseTitle">Follow a new disease</h3>
			</div>
			<form id="search_disease_form" method="POST" action="{{ route('condition-search') }}">
				@csrf
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-md-12">
							<div id="section_search_wrapper_3" class="mt-2 mb-2 input-group input-group-md">
								<input type="hidden" class="buildtype" name="type" value="">
								<div class="form-group">
									<label for="search" class="col-sm-2 control-label">Disease</label>
									<div class="col-sm-10">
										<input type="text" class="form-control queryFindDisease" name="search[]" size="100" value="" placeholder="Begin typing a disease name">
										<!--<span class="text-muted"><small><i>(Enter * to select all diseases or @ to select a group)</i></small></span> -->
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
