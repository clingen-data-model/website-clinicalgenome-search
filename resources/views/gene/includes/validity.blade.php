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
                            <div class="badge badge-pill badge-light action-expand-curation" data-uuid="{{ $validity->assertion->curie }}">
                                @if (!empty($validity->assertion->las_included) || !empty($validity->assertion->las_excluded))
                                    <i class="fas fa-random fa-xs mr-1"></i>
                                @endif
                                @if (App\Validity::secondaryContributor($validity->assertion) != "NONE")
                                <i class="fas fa-users fa-xs mr-1"></i>
                                @endif
                                <i class="fas fa-caret-down text-muted"></i>
                            </div>
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
                        <td class="no-row-border"></td>
                        <td colspan="5" class="no-row-border">
                            <div class="shadow-none bg-light rounded">
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
                            <div class="tab-content">
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane fade in active" id="las-{{ $validity->key }}">
                                    @if (!empty($validity->assertion->las_included))
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3">Included Phenotypes:</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->assertion->las_included as $mim)
                                                    <div class="mb-1">
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim"># {{ $mim }} -  {{ $mims[$mim] }}</a>
                                                    <div>
                                                @endforeach
                                            <dd>
                                        </dl>
                                    @else
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">Included Phenotypes:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                <i>No Included Phenotypes were specified</i>
                                            <div>
                                        <dd>
                                    </dl>
                                    @endif
                                    @if (!empty($validity->assertion->las_excluded))
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3">Excluded Phenotypes:</dt>
                                            <dd class="col-sm-9">
                                                @foreach ($validity->assertion->las_excluded as $mim)
                                                    <div class="mb-1">
                                                        <a href="https://omim.org/entry/{{ $mim }}" target="_mim"># {{ $mim }} -  {{ $mims[$mim] }}</a>
                                                    </div>
                                                @endforeach
                                            </dd>
                                         </dl>
                                    @else
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3">Excluded Phenotypes:</dt>
                                            <dd class="col-sm-9">
                                                <div class="mb-1">
                                                    <i>No Excluded Phenotypes were specified</i>
                                                <div>
                                            <dd>
                                        </dl>
                                     @endif
                                     <dl class="row mb-0">
                                        <dt class="col-sm-3">Rationales:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['rationales']))
                                                    {{ implode(', ', $validity->assertion->las_rationale['rationales']) }}
                                            @else
                                                <i>No rationales were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-0">
                                        <dt class="col-sm-3">PMIDs:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['pmids']))
                                                    {{ implode(', ', $validity->assertion->las_rationale['pmids']) }}
                                            @else
                                                <i>No PMIDs were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                     <dl class="row mb-0">
                                        <dt class="col-sm-3">Notes:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                            @if (!empty($validity->assertion->las_rationale['notes']))
                                                     {{ $validity->assertion->las_rationale['pmids'] }}
                                            @else
                                                <i>No Notes were specified</i>
                                            @endif
                                            </div>
                                        </dd>
                                     </dl>
                                </div>
                                <div role="tabpanel" class="pt-3 pl-3 tab-pane" id="sec-{{ $validity->key }}">
                                    <dl class="row">
                                        <dt class="col-sm-3 pl-3">Expert Panel:</dt>
                                        <dd class="col-sm-9">
                                            <div class="mb-1">
                                                {{ App\Validity::secondaryContributor($validity->assertion) }}
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
                    <tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endif
