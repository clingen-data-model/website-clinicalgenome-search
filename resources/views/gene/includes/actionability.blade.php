@if ($actionability_collection->isNotEmpty())
    @php global $currations_set; $currations_set = true; @endphp
				
	<h3 id="link-actionability" class="mb-0"><img style="margin-top:-4px" src="/images/clinicalActionability-on.png" width="40" height="40" class="hidden-sm hidden-xs"> Clinical Actionability</h3>
	<div class="card mb-3">
		<div class="card-body p-0 m-0">
			<table class="panel-body table mb-0">
				<thead class="thead-labels">
					<tr>
						<th class="col-sm-1 th-curation-group text-left">Gene</th>
						<th class="col-sm-4 text-left"> Disease</th>
						<th class="col-sm-2"></th>
						<th class="col-sm-2">Adult &amp; Pediatric Reports</th>
						<th class="col-sm-1 text-center">Report &amp; Date</th>
					</tr>
				</thead>

				<tbody class="">
					
				@foreach($actionability_collection as $actionability)
					<tr>
						<td class="">
							{{ $record->label }}
						</td>

						<td class="">
							<a href="{{ route('condition-show', $record->getMondoString($actionability->disease->iri, true)) }}">{{ $actionability->disease->label }}</a>
							<div class="text-muted small">{{ $record->getMondoString($actionability->disease->iri, true) }}</div>
						</td>

						<td class="">
						</td>

						<td class="text-center">
								<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->assertion->source }}">
								{{ $record->displayActionType($actionability->assertion->source) }}{{ App\Genelib::actionabilityAssertionString($actionability->assertion->classification->label) }}
								</a>
						</td>

						<td class=" text-center">
							<a class="btn btn-xs btn-success btn-block btn-report" href="{{ $actionability->assertion->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($actionability->assertion->report_date) }}</a>
						</td>

					</tr>
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endif