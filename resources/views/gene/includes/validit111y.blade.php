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
                            {{ $record->label }}
                        </td>

                        <td class="">
                            <a href="{{ route('condition-show', $record->getMondoString($validity->disease->iri, true)) }}">{{ displayMondoLabel($validity->disease->label) }}</a>
                            <div class="text-muted small">{{ $record->getMondoString($validity->disease->iri, true) }} {!! displayMondoObsolete($validity->disease->label) !!}</div>
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
                            <div class="action-expand-curation" data-uuid="{{ $validity->assertion->curie }}" data-toggle="tooltip" data-placement="top" title="Click to view additional information" ><span class="text-muted"><i><small>show more  </small></i><i class="fas fa-caret-down text-muted"></i></span></div>
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
                            <div class="row">
                                <div class="col-sm-12 m-1">
                                    <table class="table m-0">
                                            <tr class="noborder no-row-border">
                                                <td class="col-sm-1 text-left">&nbsp;</td>
                                                <td class="col-sm-3 text-left"> Lumping & Splitting</td>
                                                <td class="col-sm-1 text-left">&nbsp;</td>
                                                <td class="col-sm-2 text-left">Secondary Contributors</td>
                                                <td class="col-sm-2 text-left">&nbsp;</td>
                                                <td class="col-sm-1 text-left">&nbsp;</td>
                                            </tr>
                                            <tr class="noborder no-row-border">
                                                <td>&nbsp;</td>
                                                <td valign="top" class="text-muted pr-2">
                                                    @if (!empty($validity->assertion->las_included))
                                                        {{ "Included: " . implode(', ', $validity->assertion->las_included) }}
                                                    @endif
                                                    @if (!empty($validity->assertion->las_excluded))
                                                        {{ "Excluded: " . implode(', ', $validity->assertion->las_excluded) }}
                                                    @endif
                                                </td>
                                                <td >&nbsp;</td>
                                                <td class="text-muted pr-2">
                                                    {{ App\Validity::secondaryContributor($validity->assertion) }}
                                                </td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>

                                            </tr>
                                    </table>
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
