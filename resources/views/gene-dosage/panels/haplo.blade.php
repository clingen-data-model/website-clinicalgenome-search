<!-- Haploinsufficiency Details -->
<div class="row" id="report_details_haploinsufficiency">
    <div class="col-sm-12 pt-3">
        <h3 class="h4 mb-1 border-bottom-2">Haploinsufficiency (HI) Score Details</h3>
    </div>
    @if ($record->resolution != "Complete")
    <div class="col-sm-12">
        <div class="row pb-3 pt-2">
            <div class="col-sm-3 text-muted text-right bold">Review not yet complete.</div>
        </div>
    </div>
    @else
    <div class="col-sm-12">
        <div class="row pb-3 pt-2">
            @if ($record->haplo_score != -5)
            <div class="col-sm-3 text-muted text-right bold">HI Score:</div>
            <div class="col-sm-9 border-left-4 bold">{{ $record->haplo_score }}</div>
            @endif
        </div>
        <div class="row pb-3">
            <div class="col-sm-3 text-muted text-right bold">HI Evidence Strength:</div>
            <div class="col-sm-9 border-left-4"><span class="bold">{{ $record->haplo_assertion }}</span>
                <a data-toggle="popover" title="DISCLAIMER" data-placement="bottom" data-trigger="hover">
                    (Disclaimer)</a>
            </div>
        </div>
        @if (!empty($record->loss_pheno_omim) || !empty($record->loss_pheno_ontology_id))
        <div class="row pb-3">
            <div class="col-sm-3 text-muted text-right bold">HI Disease:</div>
            <div class="col-sm-9 border-left-4">
                <ul class="list-unstyled">
                    @if (!empty($record->loss_pheno_omim))
                    @foreach($record->loss_pheno_omim as $item)
                    <li>{{ empty($record->loss_pheno_name) ? $item['titles'] : $record->loss_pheno_name }}
                        @switch ($item['type'])
                        @case (App\Disease::TYPE_MONDO)
                        <a target='external' href="{{env('CG_URL_MONARCH')}}{{ $item['id'] }}"
                            class="badge-info badge pointer ml-1">Monarch <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case (App\Disease::TYPE_OMIM)
                        <a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $item['no_prefix'] }}"
                            class="badge-info badge pointer ml-1">OMIM <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case (App\Disease::TYPE_ORPHANET)
                        <a target='external' href="{{env('CG_URL_ORPHANET')}}{{ $item['no_prefix'] }}"
                            class="badge-info badge pointer ml-1">OrphaNet <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case (App\Disease::TYPE_DOID)
                        <a target='external'
                            href="{{env('CG_URL_DISEASEONTOLOGY')}}{{ $item['no_prefix'] }}"
                            class="badge-info badge pointer ml-1">Disease Ontology <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case (App\Disease::TYPE_MEDGEN)
                        <a target='external' href="{{env('CG_URL_MEDGEN')}}{{ $$item['no_prefix'] }}"
                            class="badge-info badge pointer ml-1">MedGen <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @endswitch
                    </li>
                    @endforeach
                    @endif
                    {{--
                    @if (!empty($record->loss_pheno_ontology_id))
                    <li>{{ $record->loss_pheno_name }}
                        @switch ($record->loss_pheno_ontology)
                        @case ('Monarch')
                        <a target='external' href="{{env('CG_URL_MONARCH')}}{{ $record->loss_pheno_ontology_id }}"
                            class="badge-info badge pointer ml-1">Monarch <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case ('OrhaNet')
                        <a target='external' href="{{env('CG_URL_ORPHANET')}}{{ $record->loss_pheno_ontology_id }}"
                            class="badge-info badge pointer ml-1">OrphaNet <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case ('Disease Ontology')
                        <a target='external'
                            href="{{env('CG_URL_DISEASEONTOLOGY')}}{{ $record->loss_pheno_ontology_id }}"
                            class="badge-info badge pointer ml-1">Disease Ontology <i
                                class="fas fa-external-link-alt"></i> </a>
                        @break
                        @case ('MedGen')
                        <a target='external' href="{{env('CG_URL_MEDGEN')}}{{ $record->loss_pheno_ontology_id }}"
                            class="badge-info badge pointer ml-1">MedGen <i class="fas fa-external-link-alt"></i> </a>
                        @break
                        @endswitch
                    </li>
                    @endif
                    --}}
                </ul>
            </div>
        </div>
        @endif
        @if (!empty($record->loss_pmids))
        <div class="row pb-3">
            <div class="col-sm-3 text-muted text-right bold">HI Evidence:<div></div>
            </div>
            <div class="col-sm-9 border-left-4">
                <ul class="list-unstyled">
                    @foreach ($record->loss_pmids as $loss_pmid)
                    <li class="mb-3 pb-3 border-bottom-1">
                        <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $loss_pmid['pmid'] }}" class="">PUBMED:
                            {{ $loss_pmid['pmid'] }}</a>
                        <div class="summariesShow mt-1 prewrap" id="collapsesummary1">{{ $loss_pmid['desc'] ?? '' }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @if (!empty($record->loss_comments))
        <div class="row pb-3">
            <div class="col-sm-3 text-muted text-right bold">HI Evidence Comments:</div>
            <div class="col-sm-9 border-left-4"><span class="data_pre">{{ $record->loss_comments }}</span></div>
        </div>
        @endif
        @if (!empty($record->cytoband) && strtoupper(substr($record->cytoband, 0, 1)) == 'X')
        <div class="row pb-3">
            <div class="col-sm-3 text-muted text-right bold">NOTE:<div></div>
            </div>
            <div class="col-sm-9 border-left-4 bg-light p-3">
                <p>The loss-of-function and triplosensitivity ratings for genes on the X chromosome are made in the
                    context of a male genome to account for the effects of hemizygous duplications or nullizygous
                    deletions. In contrast, disruption of some genes on the X chromosome causes male lethality and the
                    ratings of dosage sensitivity instead take into account the phenotype in female individuals. Factors
                    that may affect the severity of phenotypes associated with X-linked disorders include the presence
                    of variable copies of the X chromosome (i.e. 47,XXY or 45,X) and skewed X-inactivation in females.
                </p>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
