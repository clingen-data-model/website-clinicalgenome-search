<div class="modal" id="modalSearchGene" tabindex="-1" role="dialog" aria-labelledby="modalSearchGeneTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalSearchGeneTitle">Find a gene</h5>
			</div>
			<form id="search_form" method="POST" action="{{ route('gene-search') }}">
				@csrf
				<div class="modal-body">
					<div id="section_search_wrapper" class="mt-2 mb-2 input-group input-group-md">
						<input type="hidden" class="buildtype" name="type" value="">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class='typeQueryLabel'>Gene: </span></button>
						</div>
						<span class="inputQueryGene">
							<input type="text" class="form-control queryGene " aria-label="..." value="" name="search[]" placeholder="Start typing a gene symbol...">
						</span>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
