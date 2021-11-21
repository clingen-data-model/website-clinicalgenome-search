<div class="card cardeffect mb-3">
    <div class="card-header bg-white">
        <h2 class=""><a href="https://www.clinicalgenome.org/affiliation/{{ $gcep->affiliate_id }}">
            {{ $gcep->smart_title }}
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
                        {{ strip_tags($gcep->summary) }}
                    </p>
                    <p>The {{ $gcep->smart_title }} is in the process of reviewing <span class="badge">{{ $record->label }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
