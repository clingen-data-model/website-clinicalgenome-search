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
						<th class="col-sm-3 text-left"> Disease</th>
                        <th class="col-sm-1 text-left">Report</th>
						<th class="col-sm-2">Working Group</th>
						<th class="col-sm-2">Assertions</th>
						<th class="col-sm-1 text-center">Report &amp; Date</th>
					</tr>
				</thead>

				<tbody class="">

                @foreach($actionability_reports as $label => $report)
					@if ($report['adult'])
					<tr>
						<td class="">
                            <div>
							{{ $record->label }}
                            </div>
						</td>

						<td class="">
							<div>
								<a href="{{ route('condition-show', $report['adult']->conditions[0]) }}">{{ $report['adult']->condition_info->label }}</a>
								<div class="text-muted small">{{ $report['adult']->conditions[0] }} {!! displayMondoObsolete($report['adult']->condition_info->label) !!}</div>
							</div>
						</td>

                        <td class="">
							<div>
							{{ $report['adult']->document }}
							</div>
                        </td>

						<td class="">
							<div class="">
                        		<a href="https://clinicalgenome.org/working-groups/actionability/adult-actionability-working-group/">Adult Actionability WG
                                <i class="fas fa-external-link-alt ml-1"></i></a>
							</div>
						</td>

						<td class="text-center">
							<div>
								<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report['adult']->url['scoreDetails'] }}">
								<div class="text-muted small">Adult</div>{{ App\Genelib::actionabilityAssertionString($report['adult']->assertions['assertion']) }}
								@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report['adult']->assertions['assertion'])))
								</a>
							</div>
						</td>

						<td class="text-center @if(!$show_border && !$show_gene) border-0 @endif ">
							<div>
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report['adult']->url['scoreDetails'] }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report['adult']->events['searchDates'][array_key_last($report['adult']->events['searchDates'])]) }}</a>
							</div>
						</td>

					</tr>
					@endif
					@if ($report['ped'])
					<tr>
						<td class="">
                            <div>
							 {{ $record->label }} 
                            </div>
						</td>

						<td class="">
							<div>
								<a href="{{ route('condition-show', $report['ped']->conditions[0]) }}">{{ $report['ped']->condition_info->label }}</a>
								<div class="text-muted small">{{ $report['ped']->conditions[0] }} {!! displayMondoObsolete($report['ped']->condition_info->label) !!}</div>
							</div>
						</td>

                        <td class="">
							{{ $report['ped']->document }}
                        </td>

						<td class="">
							<div class="">
								<a href="https://clinicalgenome.org/working-groups/actionability/pediatric-actionability-working-group/">Pediatric Actionability WG
                                <i class="fas fa-external-link-alt ml-1"></i></a>
							</div>
						</td>

						<td class="text-center">
							<div>
								<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $report['ped']->url['scoreDetails'] }}">
								<div class="text-muted small">Pediatric</div>{{ App\Genelib::actionabilityAssertionString($report['ped']->assertions['assertion']) }}
								@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($report['ped']->assertions['assertion'])))
								</a>
							</div>
						</td>

						<td class="text-center @if(!$show_border && !$show_gene) border-0 @endif ">
							<div>
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $report['ped']->url['scoreDetails'] }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($report['ped']->events['searchDates'][array_key_last($report['ped']->events['searchDates'])]) }}</a>
							</div>
						</td>
					</tr>
					@endif
                    
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endif
