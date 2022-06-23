@if ($validity_collection->isNotEmpty())
    @php global $currations_set; $currations_set = true; @endphp
    <h3  id="link-gene-validity" class=" mt-3 mb-0"><img src="/images/clinicalValidity-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Gene-Disease Validity</h3>

    <div class="card mb-4">
        <div class="card-body p-0 m-0">

            <!--
            <div class="p-2 text-muted small bg-light">The following <strong>{{ $record->nvalid }} curations</strong> were completed by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1">{{ $validity_eps }} GCEP</a>.
                <!--in partnership with <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">2 expert panels</a>. {{ $record->symbol }} is also under review by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">3 GCEPs</a>. -->
                <!--<a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a></div>-->

            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                        <th class="col-sm-1 th-curation-group text-left">Gene</th>
                        <th class="col-sm-3 text-left"> Disease</th>
                        <th class="col-sm-1 text-left">MOI</th>
                        <th class="col-sm-2 text-left">Expert Panel</th>
                        <th class="col-sm-2  ">Classification</th>
                        <th class="col-sm-1 text-center">Report &amp; Date</th>
                    </tr>
                </thead>
                <tbody class="">

                @foreach($validity_collection as $validity)
                    <tr>
                        <td class="">
                            <div>
                            {{ $record->label }}
                            </div>
                            @if (App\Validity::hasLumpingContent($validity->assertion) || App\Validity::secondaryContributor($validity->assertion) != "NONE")
                            <div class="badge badge-pill badge-light border-1 border-secondary action-expand-curation" data-uuid="{{ $validity->assertion->curie }}">
                                @if (App\Validity::hasLumpingContent($validity->assertion))
                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Lumping & Splitting"><i class="fas fa-random fa-sm mr-1"></i></span>
                                @endif
                                @if (App\Validity::secondaryContributor($validity->assertion) != "NONE")
                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Secondary Contributor"><i class="fas fa-users fa-sm mr-1"></i></span>
                                @endif
                                <i class="fas fa-caret-down text-muted"></i>
                            </div>
                            @endif
                        </td>

                        <td class="">
                            <a href="{{ route('condition-show', $record->getMondoString($validity->disease->iri, true)) }}">{{ displayMondoLabel($validity->disease->label) }}</a>
                            <div class="text-muted small">{{ $record->getMondoString($validity->disease->iri, true) }} {!! displayMondoObsolete($validity->disease->label) !!}
                                <!-- @if (!empty($validity->assertion->las_included) || !empty($validity->assertion->las_excluded))
                                <span class="badge badge-pill badge-light action-expand-curation" data-uuid="{{ $validity->assertion->curie }}">Lumping & Splitting  <i class="fas fa-caret-down text-muted"></i></span>
                                @endif -->
                            </div>
                        </td>

                        <td class="">
                            {{ \App\GeneLib::validityMoiAbvrString($validity->assertion->mode_of_inheritance->website_display_label) }}
                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
                        </td>

                        <td class="text-left">
                            <a class="" href="https://clinicalgenome.org/affiliation/{{ App\Panel::gg_map_to_panel($validity->assertion->attributed_to->curie, true) }}">
                            {{ $validity->assertion->attributed_to->label }} GCEP
                            <i class="fas fa-external-link-alt ml-1"></i>
                            </a>
                            <!-- @if (App\Validity::secondaryContributor($validity->assertion) != "NONE")
                            <div class="action-expand-curation" data-uuid="{{ $validity->assertion->curie }}" data-toggle="tooltip" data-placement="top" title="Click to view additional information" ><span class="text-muted"><i><small>show more  </small></i><i class="fas fa-caret-down text-muted"></i></span></div>
                            @endif  -->
                        </td>

                        <td class="">
                            <a class="btn btn-default btn-block text-left btn-classification" href="/kb/gene-validity/{{ $validity->assertion->curie }}">
                            {{ \App\GeneLib::validityClassificationString($validity->assertion->classification->label) }}
                            </a>
                            @if ($validity->assertion->animal_model_only)
                            <div class='badge badge-warning text-left -'>
                                Animal Model Only
                            </div>
                            @endif
                        </td>

                        <td class="text-center">
                            <a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->assertion->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->assertion->report_date) }}</a>
                        </td>
                    </tr>
                    @if ($mimflag && (in_array($mimflag, $validity->assertion->las_included) || in_array($mimflag, $validity->assertion->las_excluded)))
                    <tr>
                    @else
                    <tr class="hide-element">
                    @endif
                        <td colspan="6" class="no-row-border">
                            <div class="ml-2 mr-2 shadow-none bg-lumping rounded">
                            <ul class="nav nav-pills border-bottom">
                                <li role="presentation" class="active">
                                    <a href="#las-{{ $validity->key }}" aria-controls="las-{{ $validity->key }}" role="tab" data-toggle="pill"><i class="fas fa-random mr-2"></i>Lumping & Splitting</a>
                                </li>
                                <li role="presentation">
                                    <a href="#sec-{{ $validity->key }}" aria-controls="sec-{{ $validity->key }}" role="tab" data-toggle="pill"><i class="fas fa-users mr-2"></i>Secondary Contributors</a>
                                </li>
                               <!-- <li role="presentation">
                                    <a href="#history-{{ $validity->key }}" aria-controls="history-{{ $validity->key }}" role="tab" data-toggle="pill"><i class="fas fa-history mr-2"></i>History</a>
                                </li>
                                <li role="presentation">
                                    <a href="#three-{{ $validity->key }}" aria-controls="three-{{ $validity->key }}" role="tab" data-toggle="pill"><i class="fas fa-disease mr-2"></i>Other Stuff</a>
                                </li>-->
                            </ul>
                            <div class=" ml-2 mr-2 mb-2 tab-content">
                                <div role="tabpanel" class="pt-3 pl-3 pb-2 tab-pane fade in active" id="las-{{ $validity->key }}">
                                    <div class="bg-white border border-2 border-warning mr-3 p-2 mt-1 mb-3 rounded">
                                        Lumping and Splitting is the process by which ClinGen curation groups determine which disease entity they will use for evaluation.
                                        Groups review current disease and/or phenotype assertions (e.g. OMIM MIM phenotypes) and select the included and excluded phenotypes according to <a href="https://www.clinicalgenome.org/working-groups/lumping-and-splitting/" target="_doc">current guidelines</a>.
                                        MIM phenotypes represented below are those that were available on the stated evaluation date
                                    </div>
                                    @if (!empty($validity->assertion->las_included))
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Included MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are part of the disease entity used for curation."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->assertion->las_included as $mim)
                                                    <div class="mb-1">
                                                        @if ($mimflag == $mim)
                                                        <a class="highlight" href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @else
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @endif
                                                    <div>
                                                @endforeach
                                            <dd>
                                        </dl>
                                    @else
                                    <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Included MIM Phenotypes
                                            <span class="cursor-pointer" style="white-space: nowrap;" data-toggle="tooltip" data-placement="top" title="These phenotypes are part of the disease entity used for curation."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1 text-muted">
                                                <i>No Included MIM Phenotypes were specified</i>
                                            <div>
                                        <dd>
                                    </dl>
                                    @endif
                                    @if (!empty($validity->assertion->las_excluded))
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Excluded MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are not part of the disease entity used for curation.  This does not mean that these are not valid assertions, and could be curated separately."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->assertion->las_excluded as $mim)
                                                    <div class="mb-1">
                                                        @if ($mimflag == $mim)
                                                        <a class="highlight" href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @else
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim">MIM:{{ $mim }} -  {{ $mims[$mim] ?? '' }}</a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </dd>
                                         </dl>
                                    @else
                                        <dl class="row mb-2">
                                            <dt class="col-sm-3 text-right">Excluded MIM Phenotypes
                                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="These phenotypes are not part of the disease entity used for curation.  This does not mean that these are not valid assertions, and could be curated separately."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                                :</dt>
                                            <dd class="col-sm-9">
                                                <div class="mb-1 text-muted">
                                                    <i>No Excluded MIM Phenotypes were specified</i>
                                                <div>
                                            <dd>
                                        </dl>
                                     @endif
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Evaluation Date
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="The date Lumping and Splitting assessment was performed."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_date))
                                                    {{ date('m/d/Y', strtotime($validity->assertion->las_date)) }}
                                            @else
                                                <i class="text-muted">No Date was specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Curation Type
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_curation))
                                                    {{ $validity->assertion->las_curation }}
                                            @else
                                                <i class="text-muted">No curation type was specified</i>
                                            @endif
                                            <a href="https://www.clinicalgenome.org/docs/lumping-and-splitting" class="ml-2 small" target="_doc">(Read more about curation type)</a>
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Rationales
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['rationales']))
                                                    {{ implode(', ', $validity->assertion->las_rationale['rationales']) }}
                                            @else
                                                <i class="text-muted">No rationales were specified</i>
                                            @endif
                                            <a href="https://www.clinicalgenome.org/docs/lumping-and-splitting" class="ml-2 small" target="_doc">(Read more about curation type)</a>
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">PMIDs
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Literature supporting the Lumping and Splitting decisions."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['pmids']))
                                                @foreach ($validity->assertion->las_rationale['pmids'] as $pmid)
                                                @if (isset($pmids[$pmid]))
                                                    <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $pmid }}" target="_pmid" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="{{ $pmids[$pmid]['title'] }}">{{ $pmid }}</a>@if(!$loop->last), @endif
                                                @else
                                                    <a href="https://pubmed.ncbi.nlm.nih.gov/{{ $pmid }}" target="_pmid">{{ $pmid }}</a>@if(!$loop->last), @endif
                                               @endif
                                                    @endforeach
                                            @else
                                                <i class="text-muted">No PMIDs were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-2">
                                        <dt class="col-sm-3 text-right">Notes
                                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="Optional free text explanation of the Lumping and Splitting decision."><i class="fas fa-info-circle mr-1 ml-1 text-muted"></i></span>
                                            :</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['notes']))
                                                     {{ $validity->assertion->las_rationale['notes'] }}
                                            @else
                                                <i class="text-muted">No Notes were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 pb-2 tab-pane" id="sec-{{ $validity->key }}">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3 text-right">Expert Panel:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                @if (App\Validity::secondaryContributor($validity->assertion) == "NONE")
                                                    <i class="text-muted">No Secondary Contributors were specified</i>
                                                @else
                                                    {{ App\Validity::secondaryContributor($validity->assertion) }}
                                                @endif
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane" id="history-{{ $validity->key }}">
                                    <dl class="row">
                                        <dt class="col-sm-3">History:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                None
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane" id="three-{{ $validity->key }}">
                                    <dl class="row">
                                        <dt class="col-sm-3">Other Stuff:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                Lorem Epsum
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                            </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endif
