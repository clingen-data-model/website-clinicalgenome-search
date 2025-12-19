<!-- Display the variant/region listings for Actionability -->

<!-- THIS IS A CURRENTLY A STATIC REPRESENTATION OF A SINGLE CURATION -->
@if($disease->curie == 'MONDO:0006823')

    <h3 id="link-actionability" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Clinical Actionability</h3>
    <div class="card mb-3">
        <div class="card-body p-0 m-0">
            <table class="panel-body table mb-0">
                <thead class="thead-labels">
                    <tr>
                    <th class="col-sm-1 th-curation-group text-left">Variant</th>
                    <th class="col-sm-3 text-left"> Disease</th>
                    <th class="col-sm-1 text-left"> Report</th>
                    <th class="col-sm-2">Working Group</th>
                    <th class="col-sm-2">Assertions</th>
                    <th class="col-sm-1 text-center">Report &amp; Date</th>
                    </tr>
                </thead>

                <tbody class="">
                    <tr> <!-- Adult -->
                        <td class="pb-0">
                            47,XXY
                        </td>

                        <td class="pb-0">
                            Klinefelter syndrome
                        </td>

                        <td class="pb-0">
                            Klinefelter Syndrome
                        </td>

                        <td class="pb-0">
                            <a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
                                <i class="fas fa-external-link-alt ml-1"></i></a>
                        </td>

                        <td class="pb-0 text-center">
                                <a class="btn btn-default btn-block text-left mb-2 btn-classification" href="https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryRpt?doc=AC1065">
                                    <div class="text-muted small">Adult</div> Strong Actionability
                                    @include('gene.includes.actionability_assertion_label_info', array('assertion'=> 'Strong Actionability'))
                                </a>
                        </td>

                        <td class="pb-0 text-center">
                            <a class="btn btn-xs btn-success btn-block btn-report" href="https://actionability.clinicalgenome.org/ac/Adult/ui/stg2SummaryRpt?doc=AC1065"><i class="glyphicon glyphicon-file"></i>02/07/2025</a></td>
                    </tr>

                    <tr> <!-- Pediatric -->
                        <td class="border-0 pt-0">
                        </td>

                        <td class="border-0 pt-0">
                        </td>

                        <td class="border-0 pt-0">
                        </td>

                        <td class="border-0 pt-0">
                            <a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
                                <i class="fas fa-external-link-alt ml-1"></i></a>
                        </td>

                        <td class="border-0 pt-0 text-center">
                                <a class="btn btn-default btn-block text-left mb-2 btn-classification" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/stg2SummaryRpt?doc=AC1065">
                                <div class="text-muted small">Pediatric</div> Strong Actionability
                                @include('gene.includes.actionability_assertion_label_info', array('assertion'=> 'Strong Actionability'))
                                </a>
                        </td>

                        <td class="border-0 pt-0 text-center">
                            <a class="btn btn-xs btn-success btn-block btn-report" href="https://actionability.clinicalgenome.org/ac/Pediatric/ui/stg2SummaryRpt?doc=AC1065"><i class="glyphicon glyphicon-file"></i>02/07/2025</a></td>
                    </tr>
                                    
                </tbody>
            </table>
        </div>
    </div>
@endif