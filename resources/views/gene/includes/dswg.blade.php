@if($record->ndosage > 0 )
    <div class="card cardeffect mb-3">
        <div class="card-header bg-white">
            <h2 class=""><a href="https://www.clinicalgenome.org/working-groups/dosage-sensitivity-curation">Dosage Sensitivity Curation Working Group</a></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="https://www.clinicalgenome.org/working-groups/dosage-sensitivity-curation/#heading_membership" class="badge badge-pill badge-info p-2 mr-2">Membership <i class="fas fa-external-link-alt"></i></a>
                    <a href="https://www.clinicalgenome.org/working-groups/dosage-sensitivity-curation/#heading_documents" class="badge badge-pill badge-info p-2 mr-2">Documents <i class="fas fa-external-link-alt"></i></a>
                    <div class="mt-4">
                        <img src="/images/dosageSensitivity-on.png" width="60" height="60">
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="text-muted">
                        <p>
                            The Dosage Sensitivity Curation task team uses a systematic process by which to evaluate the evidence supporting or refuting the dosage sensitivity of individual genes and genomic regions. This information can ultimately be used to inform future cytogenomic microarray designs and clinical interpretation decisions.
                        </p>
                        <p>The The Dosage Sensitity Curation Working Group has published curation assessments on <span class="badge">{{ $record->label }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <p>The Dosage Sensitity Working Group is also reviewing other genes and regions:</p>
                <a href="{{ route('dosage-index') }}"><span class="badge mr-1">Click here to see all the genes and regions</span></a>
        </div>
    </div>
@endif

