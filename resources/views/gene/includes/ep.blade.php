
<h3  id="link-gene-validity" class=" mt-3 mb-0">{{ $gcep->title_abbreviated }}</h3>
<div class="card mb-4 mt-0">
    <div class="card-header bg-darkblue text-white">
        <p>
            The ClinGen RASopathy Expert Panel seeks to evaluate the current evidence for each gene-condition assertion in order to provide a comprehensive review of Ras/MAPK pathway genes and their causality of a RASopathy condition.
        </p><p>The {{ $gcep->title_abbreviated }} is currently reviewing the following genes:
            @foreach($gcep->genes as $gene)
            {{ $gene->name }},
            @endforeach
        </p>

    </div>
    <div class="card-body p-0 m-0">

        <div class="p-2 text-muted small bg-light">The following <strong>{{ $record->nvalid }} curations</strong> were
            completed by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge
                border-primary text-primary px-1">{{ $gcep->title_abbreviated }}</a>.
            <!--in partnership with <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">2 expert panels</a>. {{ $record->symbol }} is also under review by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">3 GCEPs</a>. -->
            <a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a>
        </div>

        <table class="panel-body table mb-0">
            <thead class="thead-labels">
                <tr>
                    <th class="col-sm-1 th-curation-group text-left">Gene</th>
                    <th class="col-sm-3 text-left"> Disease</th>
                    <th class="col-sm-2 text-left">MOI</th>
                    <th class="col-sm-1 text-left">Activity</th>
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
                        <a href="{{ route('condition-show', $record->getMondoString($validity->disease->iri, true)) }}">{{
                            displayMondoLabel($validity->disease->label) }}</a>
                        <div class="text-muted small">{{ $record->getMondoString($validity->disease->iri, true) }} {!!
                            displayMondoObsolete($validity->disease->label) !!}</div>
                    </td>

                    <td class="">
                        {{
                        \App\GeneLib::validityMoiAbvrString($validity->assertion->mode_of_inheritance->website_display_label)
                        }}
                        <span class="cursor-pointer" data-toggle="tooltip" data-placement="top"
                            title="{{ \App\GeneLib::validityMoiString($validity->assertion->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i
                                class="fas fa-info-circle text-muted"></i></span>
                    </td>

                    <td class="text-center">
                        <img class="" src="/images/variantPathogenicity-on.png" title="Variant Pathogenicity" style="width:30px">
                    </td>

                    <td class="text-center">
                        <a class="btn btn-default btn-block text-left mb-2 btn-classification"
                            href="/kb/gene-validity/{{ $validity->assertion->curie }}">
                            {{ \App\GeneLib::validityClassificationString($validity->assertion->classification->label)
                            }}
                        </a>
                    </td>

                    <td class="text-center">
                        <a class="btn btn-xs btn-success btn-block btn-report"
                            href="/kb/gene-validity/{{ $validity->assertion->curie }}"><i
                                class="glyphicon glyphicon-file"></i> {{
                            $record->displayDate($validity->assertion->report_date) }}</a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
<hr>
