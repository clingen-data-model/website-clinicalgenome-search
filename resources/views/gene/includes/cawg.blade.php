@if ($actionability_collection->isNotEmpty())
    <div class="card cardeffect mb-3">
        <div class="card-header bg-white">
            <h2 class=""><a href="https://www.clinicalgenome.org/working-groups/actionability">Clinical Actionability Working Group</a></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="https://www.clinicalgenome.org/working-groups/actionability/#heading_membership" class="badge badge-pill badge-info p-2 mr-2">Membership <i class="fas fa-external-link-alt"></i></a>
                    <a href="https://www.clinicalgenome.org/working-groups/actionability/#heading_documents" class="badge badge-pill badge-info p-2 mr-2">Documents <i class="fas fa-external-link-alt"></i></a>
                    <div class="mt-4">
                        <img src="/images/clinicalActionability-on.png" width="60" height="60">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="text-muted">
                        <p>
                            The overarching goal of the Clinical Actionability curation process is to identify those human genes that, when significantly altered, confer a high risk of serious disease that could be prevented or mitigated if the risk were known
                        </p>
                        <p>The The Clinical Actionability Group has published curation assessments on <span class="badge">{{ $record->label }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <p>The Clinical Actionability Working Group is also reviewing other genes and regions:</p>
                <a href="https://actionability.clinicalgenome.org/ac/"><span class="badge mr-1">Click here to see all the genes</span></a>
        </div>
    </div>
@endif
