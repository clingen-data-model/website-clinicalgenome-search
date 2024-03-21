<div class="modal" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilterTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalFilterTitle">Advanced Filters</h5>
			</div>
			<div class="modal-body">
				<p>Select the appropriate view filters to modify the displayed rows: </p>
				@csrf
				<input type="hidden" name="ident" value="">	
					@include('gene.panels.resultsfilter')
					<div id="dosage-view-filters" class="hidden">
						@include('gene-dosage.panels.filter')
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
				Close
				</button>
			</div>
		</div>
	</div>
</div>
