<div class="card cardeffect mb-3">
    <div class="card-header bg-white">
        <h2 class=""><a href="https://www.clinicalgenome.org/affiliation/{{ $gcep->affiliate_id }}">
            {{ $gcep->title_abbreviated != "" ? $gcep->title_abbreviated : $gcep->title_short }}
        </a></h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <a href="https://www.clinicalgenome.org/affiliation/{{ $gcep->affiliate_id }}/#heading_membership" class="badge badge-pill badge-info p-2 mr-2">Membership <i class="fas fa-external-link-alt"></i></a>
                <a href="https://www.clinicalgenome.org/affiliation/{{ $gcep->affiliate_id }}/#heading_documents" class="badge badge-pill badge-info p-2 mr-2">Documents <i class="fas fa-external-link-alt"></i></a>
            </div>
            <div class="col-md-9">
                <div class="text-muted">
                    <p>
                        The ClinGen RASopathy Expert Panel seeks to evaluate the current evidence for each gene-condition assertion in order to provide a comprehensive review of Ras/MAPK pathway genes and their causality of a RASopathy condition.
                    </p>
                    <p>The {{ $gcep->title_abbreviated }} is in the process of reviewing <span class="badge">{{ $record->label }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
