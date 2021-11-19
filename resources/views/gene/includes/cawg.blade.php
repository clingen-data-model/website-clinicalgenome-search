@if ($actionability_collection->isNotEmpty())
		@php global $adult_set; $adult_set = false; @endphp
    @php global $currations_set; $currations_set = true; @endphp

	<div class="card mb-4">
		<div class="card-body p-0 m-0">
            <div class="p-2 text-muted pb-3 bg-light">
                <h3  id="link-gene-validity" class="">Clinical Actionability Working Group</h3>
                <p>
                    The overarching goal of the Clinical Actionability curation process is to identify those human genes that, when significantly altered, confer a high risk of serious disease that could be prevented or mitigated if the risk were known
                </p><p>The Clinical Actionabilty Working Group is currently reviewing the following genes:</p>
                    <span class="badge mr-1">Click here to vier the listing of all genes</span>
            </div>
			<table class="panel-body table mb-0">
				<thead class="thead-labels">
					<tr>
						<th class="col-sm-1 th-curation-group text-left">Gene</th>
						<th class="col-sm-5 text-left"> Disease</th>
						<th class="col-sm-1 text-left">Activity</th>
						<th class="col-sm-2">Assertions</th>
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
							<a href="{{ route('condition-show', $record->getMondoString($actionability->disease->iri, true)) }}">{{ displayMondoLabel($actionability->disease->label) }}</a>
							<div class="text-muted small">{{ $record->getMondoString($actionability->disease->iri, true) }} {!! displayMondoObsolete($actionability->disease->label) !!}</div>
						</td>

						<td class="">
                            <img class="" src="/images/clinicalActionability-on.png" title="Clinical Actionability" style="width:30px">
						</td>

						<td class="text-center">
								@if ($actionability->adult_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->adult_assertion->source }}">
									<div class="text-muted small">Adult</div>{{ App\Genelib::actionabilityAssertionString($actionability->adult_assertion->classification->label) }}
									@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($actionability->adult_assertion->classification->label)))
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
                            <img class="" src="/images/clinicalActionability-on.png" title="Clinical Actionability" style="width:30px">
						</td>

						<td class="@if($adult_set != true) pb-0 @else border-0 pt-0 @endif text-center">
								@if ($actionability->pediatric_assertion)
									<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ $actionability->pediatric_assertion->source }}">
										<div class="text-muted small">Pediatric</div>{{ App\Genelib::actionabilityAssertionString($actionability->pediatric_assertion->classification->label) }}
									@include('gene.includes.actionability_assertion_label_info', array('assertion'=> App\Genelib::actionabilityAssertionString($actionability->pediatric_assertion->classification->label)))
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
