    <div class="form-group row bg-light border-1 border-muted ml-2 mr-2 p-2">
        <label for="gene" class="col-sm-6 ml-5 col-form-label">Show Genes</label>
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
        </div>
        <label for="gene" class="col-sm-6 ml-5 col-form-label">Show only Genes/Regions with Updated Scores </label>
        <div class="col-sm-4">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg action-show-new"></i>
                <span class="ml-4 hgnc text-muted action-show-new-text">Off</span>
            </div>
        </div><label for="gene" class="col-sm-6 ml-5 col-form-label">Show only recently reviewed Genes/Regions </label>
        <div class="col-sm-4">
            <div class="form-inline p-0 m-0 col-sm-12">
                <i class="fas fa-toggle-off fa-lg action-show-recent"></i>
                <span class="ml-4 hgnc text-muted action-show-recent-text">Off</span>
            </div>
        </div>
        <label for="gene" class="col-sm-6 ml-5 col-form-label">GRCh37 Location Search</label>
        <div class="input-group col-sm-4">
            <form method="post" action="/gene-dosage/region_search">
                @csrf
                <input type="hidden" name="type" value="GRCh37">
                <input type="text" class="form-control" name="region">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go!</button>
                </span>
            </form>
        </div>
        <label for="gene" class="col-sm-6 ml-5 col-form-label">GRCh38 Location Search</label>
        <div class="input-group col-sm-4">
            <form method="post" action="/gene-dosage/region_search">
                @csrf
                <input type="hidden" name="type" value="GRCh38">
                <input type="text" class="form-control" name="region">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go!</button>
                </span>
            </form>
         </div>
    </div>
