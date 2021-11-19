
    <div class="card mb-4">
    <div class="card-body p-0 m-0">

        <div class="p-2 text-muted pb-3 bg-light">
            <h3  id="link-gene-validity" class="">{{ $vcep->title_abbreviated }}</h3>
            <p>
                The ClinGen RASopathy Expert Panel seeks to evaluate the current evidence for each gene-condition assertion in order to provide a comprehensive review of Ras/MAPK pathway genes and their causality of a RASopathy condition.
            </p><p>The {{ $vcep->title_abbreviated }} is currently reviewing the following genes:</p>
                @foreach($vcep->genes as $gene)
                <span class="badge mr-1">{{ $gene->name }}</span>
                @endforeach
        </div>

            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                        <th class="col-sm-1 th-curation-group text-left">Gene</th>
                        <th class="col-sm-5"></th>
                        <th class="col-sm-1">Activity</th>
                        <th class="col-sm-2">Classification</th>
                        <th class="col-sm-1 text-center">Date</th>
                    </tr>
                </thead>
            <tbody class="">

                @php $variant_key = 0 @endphp
                    @foreach($variant_collection as $gene => $classes)
                    @foreach($classes['classifications'] as $variant => $variant_count)
                    <tr class="">
                        <td class="@if($variant_key != 0) border-0 pt-0 @endif pb-1 ">@if($variant_key == 0){{ $record->label  }}@endif</td>
                        <td class="@if($variant_key != 0) border-0 pt-0   @endif pb-1 ">@if($variant_key == 0) Variants approved by <a href="{{ route('gene-groups', $record->hgnc_id) }}">{{  implode(', ', $classes['panels']) }}</a> @endif</td>
                        <td class="@if($variant_key != 0) border-0 pt-0  @endif pb-1 ">
                            <img class="" src="/images/variantPathogenicity-on.png" title="Variant Pathogenicity" style="width:30px">
                        </td>
                        <td class="text-center @if($variant_key != 0) border-0 pt-0 @endif pb-1 ">
                                <div class="mb-0"><a class="btn btn-default btn-block text-left pt-1 btn-classification" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                                    {{ $variant }}  <span class="badge pull-right"><small>{{ $variant_count }}</small></span><br>
								</a>
                                </div>
                        </td>
                        <td class=" text-center @if($variant_key != 0) border-0 pt-0  @endif  pb-1 ">
                                <a class="btn btn-xs btn-success btn-block" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                                    <span class=""><i class="glyphicon glyphicon-file"></i>  Evidence</span>
                                </a>

                        </td>

                    </tr>
                    @php $variant_key++ @endphp
                    @endforeach
                    @endforeach

            </tbody>
        </table>
    </div>
</div>
