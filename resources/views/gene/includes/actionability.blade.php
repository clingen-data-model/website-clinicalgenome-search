@if ($actionability_collection->isNotEmpty())
		@php global $adult_set; $adult_set = false; @endphp
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
					@if ($actionability->adult_assertion)
					@php global $adult_set; $adult_set = true; @endphp
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
								@if ($actionability->adult_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->adult_assertion->source }}">
									<div class="text-muted small">Adult</div>{{ App\Genelib::actionabilityAssertionString($actionability->adult_assertion->classification->label) }}
									@if(App\Genelib::actionabilityAssertionString($actionability->adult_assertion->classification->label) == "Assertion Pending")
										<span data-toggle="tooltip" data-placement="top" title="" data-original-title="'Assertion Pending' were generated prior to the implementation of the process for making actionability assertions. Topics needing assertions are actively being reviewed."><i class="fas fa-info-circle text-muted"></i></span>
										@else

										{{--
											NOTE, these are hidden because they MAY be used in the future
											<span data-toggle="tooltip" data-placement="top" title="" data-original-title="View the report to learn how Actionability assertions are determined."><i class="fas fa-info-circle text-muted"></i></span>
										<span data-toggle="tooltip" data-placement="top" title="" data-original-title="Actionability assertions are made in the context of a secondary finding and represent a known ability to intervene with specific clinical actions and thereby avert or mitigate a poor health outcome due to a previously unsuspected risk of disease. Actionability is NOT currently considered in the context of population-wide screening or the diagnostic setting. For more information, please see our protocol."><i class="fas fa-info-circle text-muted"></i></span>
										--}}


										@endif
									</a>
								@endif
						</td>

						<td class=" text-center">
							@if ($actionability->adult_assertion)
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $actionability->adult_assertion->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($actionability->adult_assertion->report_date) }}</a>
							@endif
						</td>

					</tr>
					@endif

					@if ($actionability->pediatric_assertion)
					<tr>
						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif ">
							@if($adult_set != true)
							{{ $record->label }}
							@endif
						</td>

						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif ">
							@if($adult_set != true)
							<a href="{{ route('condition-show', $record->getMondoString($actionability->disease->iri, true)) }}">{{ $actionability->disease->label }}</a>
							<div class="text-muted small">{{ $record->getMondoString($actionability->disease->iri, true) }}</div>
							@endif
						</td>

						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif ">
						</td>

						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif text-center">
								@if ($actionability->pediatric_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->pediatric_assertion->source }}">
										<div class="text-muted small">Pediatric</div>{{ App\Genelib::actionabilityAssertionString($actionability->pediatric_assertion->classification->label) }}
										@if(App\Genelib::actionabilityAssertionString($actionability->pediatric_assertion->classification->label) == "Assertion Pending")
										<span data-toggle="tooltip" data-placement="top" title="" data-original-title="'Assertion Pending' were generated prior to the implementation of the process for making actionability assertions. Topics needing assertions are actively being reviewed."><i class="fas fa-info-circle text-muted"></i></span>
										@else

										{{--
											NOTE, these are hidden because they MAY be used in the future
											<span data-toggle="tooltip" data-placement="top" title="" data-original-title="View the report to learn how Actionability assertions are determined."><i class="fas fa-info-circle text-muted"></i></span>
										<span data-toggle="tooltip" data-placement="top" title="" data-original-title="Actionability assertions are made in the context of a secondary finding and represent a known ability to intervene with specific clinical actions and thereby avert or mitigate a poor health outcome due to a previously unsuspected risk of disease. Actionability is NOT currently considered in the context of population-wide screening or the diagnostic setting. For more information, please see our protocol."><i class="fas fa-info-circle text-muted"></i></span>
										--}}


										@endif
									</a>
								@endif
						</td>

						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif text-center">
							@if ($actionability->pediatric_assertion)
								<a class="btn btn-xs btn-success btn-block btn-report" style="margin-bottom: 1.35rem;" href="{{ $actionability->pediatric_assertion->source }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($actionability->pediatric_assertion->report_date) }}</a>
							@endif
						</td>

					</tr>
					@endif
				@endforeach

				</tbody>
			</table>
		</div>
	</div>
@endif