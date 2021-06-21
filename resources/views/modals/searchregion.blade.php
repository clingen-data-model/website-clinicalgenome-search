<div class="modal" id="modalSearchRegion" tabindex="-1" role="dialog" aria-labelledby="modalSearchGeneTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header section-heading-groups">
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h3 class="modal-title text-white" id="modalSearchRegionTitle">Follow a new region</h3>
			</div>
			<form id="search_region_form" method="POST" action="{{ route('dashboard-region-search') }}">
				@csrf
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-md-12">
							<div id="section_search_wrapper_2" class="mt-2 mb-2 input-group input-group-md">
								<input type="hidden" name="type" value="region">
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Build</label>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="build" id="gridRadios1" value="GRCh37" checked>
                                            GRCh37

                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="build" id="gridRadios2" value="GRCh38">
                                                GRCh38
                                        </div>
                                    </div>
									<label for="search" class="col-sm-2 control-label">Region</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" name="gene" size="50" value="" placeholder="Enter genomic coordinates or cytoband range">
										<span class="text-muted pb-4"><small><i>(Ex.: chr22:21917117-24994433, chrX:30,195,000-30,355,000 )</i></small></span>
									</div>
                                    <label for="search" class="col-sm-2 control-label">Display Name <i>(optional)</i></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="display" size="50" value="" placeholder="Enter a name">
                                    </div>
							    </div>
						    </div>
					    </div>
				    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Region</button>
                </div>
			</form>
		</div>
	</div>
</div>
