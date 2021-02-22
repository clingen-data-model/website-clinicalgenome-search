@if ($variant_collection->isNotEmpty())
    @php global $currations_set; $currations_set = true; @endphp
    
    <h3 id="link-gene-validity" class=" mt-3 mb-0">
        <img src="/images/variantPathogenicity-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Variant Pathogenicity
    </h3>
    <div class="card mb-4">
         <div class="card-body p-0 m-0">
            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                        <th class="col-sm-1 th-curation-group text-left">Gene</th>
                        <th class="col-sm-4"></th>
                        <th class="col-sm-2"></th>
                        <th class="col-sm-2">Classification</th>
                        <th class="col-sm-1 text-center">Date</th>
                    </tr>
                </thead>
                <tbody class="">
                    <tr>
                        <td class="">{{ $record->label  }}</td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="text-center">
                            @foreach($variant_collection as $variant => $variant_count)
                                <a class="btn btn-default btn-block text-left mb-2 btn-classification" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">
                                    {{ $variant }}  ({{ $variant_count }}) <br>
								</a>
                            @endforeach
                        </td>
                        <td class=" text-center">
                            @foreach($variant_collection as $variant => $variant_count)
                                <a class="btn btn-xs btn-success btn-block" target="_erepo" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?assertion={{ $variant }}&matchMode=exact&gene={{ $record->label }}">  
                                    <span class=""><i class="glyphicon glyphicon-file"></i>  Evidence</span>
                                </a>
                            @endforeach
                        </td>
                            
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif