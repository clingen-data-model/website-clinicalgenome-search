@if ($actionability_collection->isNotEmpty())
	@php global $adult_set; $adult_set = true; @endphp
    @php global $show_gene; $show_gene = true; @endphp
    @php global $show_label; $show_label = true; @endphp
    @php global $show_border; $show_border = false; @endphp
    @php global $currations_set; $currations_set = true; @endphp

<h3 id="link-actionability" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Clinical Actionability</h3>
	<div class="card mb-4">
		<div class="card-body p-0 m-0">
			<!--
			<div class="p-2 text-muted small bg-light">The following <strong>{{ $record->naction }} curations</strong> were completed by <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">Actionability's Adult &amp; Pediatric Working Group</a>. <a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a></div>
			-->
			<table class="panel-body table mb-0">
				<thead class="thead-labels">
					<tr>
						<th class="col-sm-1 th-curation-group text-left">Gene</th>
						<th class="col-sm-3 text-left"> Disease</th>
                        <th class="col-sm-1 text-left">Report</th>
						<th class="col-sm-2">Working Group</th>
						<th class="col-sm-2">Assertions</th>
						<th class="col-sm-1 text-center">Report &amp; Date</th>
					</tr>
				</thead>

				<tbody class="">

				@foreach($actionability_collection as $actionability)
                    @php global $show_gene; $show_gene = true; @endphp
                    @php global $show_report; $show_report = true; @endphp
                    @php global $show_border; $show_border = false; @endphp
                @foreach($actionability->reports as $label => $report)
					@if ($report['adult'] !== null)
                    @php global $show_border; $show_border = $loop->last && $report['pediatric'] === null; @endphp
					<tr>
						<td class="@if(!$show_border && !$show_gene) border-0 @endif ">
                            @if ($show_gene)
                            <div>
							{{ $record->label }}
                            </div>
                            @endif
						</td>

						<td class="@if(!$show_border && !$show_gene) border-0 @endif ">
                            @if($show_gene)
							<a href="{{ route('condition-show', $record->getMondoString($actionability->disease->iri, true)) }}">{{ displayMondoLabel($actionability->disease->label) }}</a>
							<div class="text-muted small">{{ $record->getMondoString($actionability->disease->iri, true) }} {!! displayMondoObsolete($actionability->disease->label) !!}</div>
                            @endif
                        </td>

                        <td class="@if(!$show_border && !$show_gene) border-0 @endif ">
                            @if ($show_report)
							<span class="small">{{ App\Genelib::actionabilityReportString($label) }}</span>
                            @endif
                        </td>

						<td class="@if(!$show_border && !$show_gene) border-0 @endif ">
                            <a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
                                <i class="fas fa-external-link-alt ml-1"></i></a>
						</td>

						<td class="text-center @if(!$show_border && !$show_gene) border-0 @endif ">
								@if ($actionability->adult_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report['adult']->source }}">
									<div class="text-muted small">Adult</div>{{ App\Genelib::actionabilityAssertionString($report['adult']->classification->label) }}
									@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report['adult']->classification->label)))
									</a>
								@endif
						</td>

						<td class="text-center @if(!$show_border && !$show_gene) border-0 @endif ">
							@if ($actionability->adult_assertion)
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report['adult']->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report['adult']->report_date) }}</a>
							@endif
						</td>

					</tr>
                    @php global $show_gene; $show_gene = false; @endphp
                    @php global $show_report; $show_report = false; @endphp
					@endif

					@if ($report['pediatric'] !== null)
                    @php global $show_border; $show_border = $loop->last; @endphp

					<tr>
						<td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif ">
							@if($show_gene)
							{{ $record->label }}
							@endif
						</td>

						<td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif ">
							@if($show_gene)
							<a href="{{ route('condition-show', $record->getMondoString($actionability->disease->iri, true)) }}">{{ $actionability->disease->label }}</a>
							<div class="text-muted small">{{ $record->getMondoString($actionability->disease->iri, true) }}</div>
							@endif
						</td>

                        <td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif ">
                            @if ($show_report)
							<span class="small">{{ App\Genelib::actionabilityReportString($label ?? '') }}</span>
                            @endif
                        </td>

						<td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif ">
                            <a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
                                <i class="fas fa-external-link-alt"></i></a>
						</td>

						<td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif text-center">
								@if ($actionability->pediatric_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report['pediatric']->source }}">
										<div class="text-muted small">Pediatric</div>{{ App\Genelib::actionabilityAssertionString($report['pediatric']->classification->label) }}
									@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report['pediatric']->classification->label)))
									</a>
								@endif
						</td>

						<td class="@if($show_border) pb-0 border-top-0 @else border-0 pt-0 @endif text-center">
							@if ($actionability->pediatric_assertion)
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report['pediatric']->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report['pediatric']->report_date) }}</a>
							@endif
						</td>

					</tr>
                    @php global $show_gene; $show_gene = false; @endphp
                    @php global $show_report; $show_report = true; @endphp
					@endif
                    @endforeach
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endif
