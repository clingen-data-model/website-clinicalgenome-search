@if ($somatic_collection->isNotEmpty())
    @php global $currations_set; $currations_set = true; @endphp

    <h3 id="link-gene-validity" class=" mt-6 mb-0">
        <img src="/images/clingen-somatic-icon.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Somatic Cancer
    </h3>
    <div class="card mb-4">
         <div class="card-body p-0 m-0">
             <!--
             <div class="p-2 text-muted small bg-light">The following <strong>{{ $record->nvariant }} variant classifications</strong> were completed by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">{{ implode(', ', $variant_panels) }}</a>. <a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a></div>
             -->
             <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                        <th class="col-sm-1 th-curation-group text-left">Gene</th>
                        <th class="col-sm-4">Disease</th>
                        <th class="col-sm-2">Expert Panel</th>
                        <th class="col-sm-2">Level</th>
                        <th class="col-sm-1 text-center">Type</th>
                        <th class="col-sm-1 text-center">Significance</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach($somatic_collection as $somatic)
                    <tr class="">
                        <td class="border-0 pb-1 ">{{ $somatic["gene"]  }}</td>
                        <td class="border-0 pb-1 ">
                            <a href="">{{ $somatic["disease"] }}</a>
                            <div class="text-muted small">{{ $classes['id'] ?? '' }}
                            </div>
                        </td>
                        <td class="border-0 pb-1 "><a href="https://clinicalgenome.org/affiliation/}">{{ $somatic["ep"] }} <i class="fas fa-external-link-alt ml-1"></i></a></td>
                        <td class="text-center border-0 pb-1 ">
                                <div class="mb-0"><a class="btn btn-default btn-block text-left pt-1 btn-classification" target="_civic" href="">
                                    {{ $somatic["level"] }}  <span class="badge pull-right"><small>1</small></span><br>
								</a>
                                </div>
                        </td>
                        <td class=" text-center border-0 pb-1 ">
                                <a class="btn btn-xs btn-success btn-block" target="_civic" href="">
                                    <span class=""><i class="glyphicon glyphicon-file"></i>{{ $somatic["type"] }}</span>
                                </a>

                        </td>
                        <td class=" text-center border-0 pb-1 ">
                                <a class="btn btn-xs btn-success btn-block" target="_civic" href="">
                                    <span class=""><i class="glyphicon glyphicon-file"></i>{{ $somatic["significance"] }}</span>
                                </a>

                        </td>

                    </tr>
                    @endforeach
                    <tr class="">
                        <td colspan=6 class="mb-3 border-0"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endif
