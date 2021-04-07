@php ($header_val = true) @endphp
@forelse ($record->genetic_conditions as $key => $disease)
@if(count($disease->gene_validity_assertions))
@if($header_val == true)
@php ($currations_set = true) @endphp
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
            @endif
                @php ($first = true) @endphp
                @foreach($disease->gene_validity_assertions as $i => $validity)
                        <tr>
                            <td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif ">

                                <a href="{{ route('gene-show', $disease->gene->hgnc_id) }}">{{ $disease->gene->label }}</a>
                            </td>

                            <td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                {{ $record->label }}
                            </td>

                            <td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                {{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }}
                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ \App\GeneLib::validityMoiString($validity->mode_of_inheritance->website_display_label) }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
                            </td>

                            <td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
                                <a class="btn btn-default btn-block text-left mb-2 btn-classification" href="/kb/gene-validity/{{ $validity->curie }}">
                                {{ \App\GeneLib::validityClassificationString($validity->classification->label) }}
                                </a>
                            </td>
                            <td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/{{ $validity->curie }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($validity->report_date) }}</a></td>
                        </tr>
                @php ($first = false) @endphp
                @endforeach
                @php ($header_val = false) @endphp
@endisset
@empty
@endforelse
@if($header_val == false)
            </tbody>
        </table>
    </div>
</div>
@endisset