    <div class="form-group row bg-light border-1 border-muted ml-2 mr-2 p-2">
        <!--<label for="gene" class="col-sm-6 ml-5 col-form-label">Show Genes</label>
        <div class="col-sm-4">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-on fa-lg action-show-genes"></i>
                <span class="ml-4 hgnc text-muted action-show-genes-text">On</span>
            </div>
        </div>
        <label for="gene" class="col-sm-6 ml-5 col-form-label">Show Regions</label>
        <div class="col-sm-4">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-on fa-lg action-show-regions"></i>
                <span class="ml-4 hgnc text-muted action-show-regions-text">On</span>
            </div>
        </div>-->
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Genes/Regions with HI Score 3 (Sufficient Evidence) </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-hiknown" data-badge="Known HI"></i>
                <span class="ml-2 hgnc text-muted action-show-hiknown-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Genes/Regions with TS Score 3 (Sufficient Evidence) </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-tsknown" data-badge="Known TS"></i>
                <span class="ml-2 hgnc text-muted action-show-tsknown-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Genes/Regions with TS Score 3 <u>or</u> HI Score 3 </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-hitsknown" data-badge="Known HI or TS"></i>
                <span class="ml-2 hgnc text-muted action-show-hitsknown-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Genes/Regions with scores changed in the past year </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-new" data-badge="Score Change 365"></i>
                <span class="ml-2 hgnc text-muted action-show-new-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Genes/Regions reviewed in the past 90 days</label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-recent" data-badge="Show Recent"></i>
                <span class="ml-2 hgnc text-muted action-show-recent-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only Protein-Coding Genes</label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg advanced-filter action-show-protein" data-badge="Protein Coding"></i>
                <span class="ml-2 hgnc text-muted action-show-protein-text">Off</span>
            </div>
        </div>
        @if ($is_search ?? false)
            <label for="gene" class="col-sm-8 ml-5 col-form-label"> Show only Genes/Regions that have completed evaluations</label>
            <div class="col-sm-3">
                <div class="form-inline p-0 m-0 col-sm-12">
<<<<<<< HEAD
                    <i class="fas fa-toggle-off fa-lg advanced-filter action-show-completed" data-badge="Completed Review"></i>
=======
                    <i class="fas fa-toggle-off fa-lg dosage-filter action-show-completed" data-badge="Completed Evaluations"></i>
>>>>>>> master
                    <span class="ml-2 hgnc text-muted action-show-completed-text">Off</span>
                </div>
            </div>
            <label for="gene" class="col-sm-8 ml-5 col-form-label"> Show Pseudogenes</label>
            <div class="col-sm-3">
                <div class="form-inline p-0 m-0 col-sm-12">
                    <i class="fas fa-lg fa-toggle-on advanced-filter action-show-pseudogenes" data-badge="Show Pseudogenes"></i>
                    <span class="ml-2 hgnc text-muted action-show-pseudogenes-text">On</span>
                </div>
            </div>
        @endif
    </div>
