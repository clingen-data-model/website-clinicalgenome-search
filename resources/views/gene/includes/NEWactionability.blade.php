@if ($actionability_collection->isNotEmpty())
	@php global $adult_set; $adult_set = true; @endphp
    @php global $show_gene; $show_gene = true; @endphp
    @php global $show_label; $show_label = true; @endphp
    @php global $show_border; $show_border = false; @endphp
    @php global $currations_set; $currations_set = true; @endphp

<h3 id="link-actionability" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Clinical Actionability</h3>
	<div class="card mb-4">
		<div class="card-body p-0 m-0">
			<table class="panel-body table mb-0">
				<thead class="thead-labels">
					<tr>
						<th class="col-sm-1 th-curation-group text-left">Gene</th>
						<th class="col-sm-2 text-left"> Report</th>
                        <th class="col-sm-2 text-left">Working Group</th>
						<th class="col-sm-3">Disease</th>
						<th class="col-sm-2">Assertions</th>
						<th class="col-sm-1 text-center">Report &amp; Date</th>
					</tr>
				</thead>

				<tbody class="">

                @foreach($actionability_reports as $label => $groups)
					@foreach($groups as $group => $reports)
						@foreach($reports as $index => $report)
							@if ($group == 'adult')
							<tr>
								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @elseif ($loop->first) border-top @else border-0 @endif">
									<div>
										@if ($loop->first)
											{{ $record->label }}
										@endif
									</div>
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										@if ($loop->first)
											<span class="small">{{ App\Genelib::actionabilityReportString($report->title) }}</span>
										@endif
									</div>
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div class="">
										@if ($loop->first)
											<a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
											<i class="fas fa-external-link-alt ml-1"></i></a>
										@endif
									</div>
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										<a href="{{ route('condition-show', $report->conditions[0]) }}">{{ $report->condition_info->label }}</a>
										<div class="text-muted small">{{ $report->conditions[0] }} {!! displayMondoObsolete($report->condition_info->label) !!}</div>
									</div>
								</td>

								<td class="text-center @if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report->url['scoreDetails'] }}">
										<div class="text-muted small">Adult</div>{{ App\Genelib::actionabilityAssertionString($report->assertions['assertion']) }}
										@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report->assertions['assertion'])))
										</a>
									</div>
								</td>

								<td class="text-center @if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										@if ($loop->first)
											<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report->url['scoreDetails'] }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report->events['searchDates'][array_key_last($report->events['searchDates'])]) }}</a>
										@endif
									</div>
								</td>

							</tr>
							@endif
							@if ($group == 'ped')
							<tr>
								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0  @else border-0 @endif">
									<div>
										@if ($loop->first)
											{{ $record->label }}
										@endif
									</div>
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									@if ($loop->first)
										<span class="small">{{ App\Genelib::actionabilityReportString($report->title) }}</span>
									@endif
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div class="">
										@if ($loop->first)
											<a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
											<i class="fas fa-external-link-alt ml-1"></i></a>
										@endif
									</div>
								</td>

								<td class="@if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										<a href="{{ route('condition-show', $report->conditions[0]) }}">{{ $report->condition_info->label }}</a>
										<div class="text-muted small">{{ $report->conditions[0] }} {!! displayMondoObsolete($report->condition_info->label) !!}</div>
									</div>
								</td>

								<td class="text-center @if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report->url['scoreDetails'] }}">
										<div class="text-muted small">Pediatric</div>{{ App\Genelib::actionabilityAssertionString($report->assertions['assertion']) }}
										@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report->assertions['assertion'])))
										</a>
									</div>
								</td>

								<td class="text-center @if ($loop->first) border-top @elseif ($loop->last) border-top-0 @else border-0 @endif">
									<div>
										@if ($loop->first)
											<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report->url['scoreDetails'] }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report->events['searchDates'][array_key_last($report->events['searchDates'])]) }}</a>
										@endif
									</div>
								</td>
							</tr>
							@endif
						@endforeach
                    @endforeach
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endif
