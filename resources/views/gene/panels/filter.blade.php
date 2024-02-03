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
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only ACMG Secondary Findings Genes (ACMG SF v3.2) </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg action-show-acmg59"></i>
                <span class="ml-2 hgnc text-muted action-show-acmg59-text">Off</span>
            </div>
        </div>
        @if ($user === null)
        <div id="curated-filter-dashboard" style="display:none">
        @endif
        <label for="gene" class="col-sm-8 ml-5 col-form-label">Show only the genes you are following </label>
        <div class="col-sm-3">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg action-show-follow"></i>
                <span class="ml-2 hgnc text-muted action-show-follow-text">Off</span>
            </div>
        </div>
        @if ($user === null)
        </div>
        @endif
    </div>
