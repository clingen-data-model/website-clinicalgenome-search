@if ($validity_collection->isNotEmpty())
    @php global $currations_set; $currations_set = true; @endphp
    <h3  id="link-gene-validity" class=" mt-3 mb-0"><img src="/images/clinicalValidity-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Gene-Disease Validity</h3>
    <div class="card mb-3">
        <div class="card-body p-0 m-0">
            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                        <th class="col-sm-1 th-curation-group text-left">Gene</th>
                        <th class="col-sm-4 text-left"> Disease</th>
                        <th class="col-sm-2 text-left">MOI</th>
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
                            {{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }}
                            <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
                        </td>

                        <td class="text-center">
                            <a class="btn btn-default btn-block text-left mb-2 btn-classification" href="/kb/gene-validity/{{ $validity->assertion->curie }}">
                            {{ \App\GeneLib::validityClassificationString($validity->assertion->classification->label) }}
                            </a>
                        </td>

                        <td class="text-center">
                            <a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->assertion->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->assertion->report_date) }}</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endif
