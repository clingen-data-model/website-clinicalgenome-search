@php ($header_dos = true) @endphp
				@forelse ($record->genetic_conditions as $disease)
				@if(count($disease->gene_dosage_assertions))
				@php global $currations_set; $currations_set = true; @endphp
				@if($header_dos == true)
					<h3 class="mb-0">Dosage Sensitivity Curation Working Group</h3>
					<p>
                        The Dosage Sensitivity Curation task team uses a systematic process by which to evaluate the evidence supporting or refuting the dosage sensitivity of individual genes and genomic regions. This information can ultimately be used to inform future cytogenomic microarray designs and clinical interpretation decisions.
                    </p>
                            <p>The {{ $gcep->title_abbreviated }} is currently reviewing the following genes:
                                @foreach([] as $gene)
                                {{ $gene->name }},
                                @endforeach
                            </p>
                    <div class="card mb-4">
						<div class="card-body p-0 m-0">
							<div class="p-2 text-muted small">The following <strong>{{ $record->ndosage }} curations</strong> were completed by the <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">Dosage Working Group</a>. <a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a></div>
						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left"> Disease</th>
								<th class="col-sm-2 text-center">Activity</th>
								<th class="col-sm-2">HI Score &amp; TS Score</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>

							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@forelse($disease->gene_dosage_assertions as $i => $dosage)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $record->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												<a href="{{ route('condition-show', $record->getMondoString($disease->disease->iri, true)) }}">{{ displayMondoLabel($disease->disease->label) }}</a>
												<div class="text-muted small">{{ $record->getMondoString($disease->disease->iri, true) }} {!! displayMondoObsolete($disease->disease->label) !!}</div>
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
                                                <img class="" src="/images/dosageSensitivity-on.png" title="Variant Pathogenicity" style="width:30px">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if($dosage->assertion_type == "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-default btn-block text-left  mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">
														{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
													@if($dosage->assertion_type != "HAPLOINSUFFICIENCY_ASSERTION")
													<a class="btn btn-default btn-block text-left   mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">
														{{ $dosage->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($dosage->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $record->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($dosage->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@empty

								@php ($first = true) @endphp
								@foreach($record->dosage_curation_map as $key => $value)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												{{ $record->label }}
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												No evidence for
												@if ($key == "haploinsufficiency_assertion")
												 	haploinsufficiency
												@else
													triplosensitivity
												@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if ($key == "haploinsufficiency_assertion")
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@else
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $record->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@endforelse
								@php ($header_dos = false) @endphp

				@endisset
				@empty
				@endforelse
				{{-- CHeck if no diseases for dosage and loop through --}}
					@if(!empty($record->dosage_curation ) && !empty($record->dosage_curation_map))
					@if($header_dos == true)
					@php global $currations_set; $currations_set = true; @endphp
					<h3 class="mb-0">Dosage Sensitivity Curation Working Group</h3>
					<p>
                        The Dosage Sensitivity Curation task team uses a systematic process by which to evaluate the evidence supporting or refuting the dosage sensitivity of individual genes and genomic regions. This information can ultimately be used to inform future cytogenomic microarray designs and clinical interpretation decisions.
                    </p>
                            <p>The {{ $gcep->title_abbreviated }} is currently reviewing the following genes:
                                @foreach([] as $gene)
                                {{ $gene->name }},
                                @endforeach
                            </p>
                    <div class="card mb-4">
						<div class="card-body p-0 m-0">
							<div class="p-2 text-muted small bg-light">The following <strong>2 curations</strong> were completed by the <a href='{{ route('gene-groups', $record->hgnc_id) }}' class="border-1 bg-white badge border-primary text-primary px-1   ">Dosage Working Group</a>. <a href="{{ route('gene-groups', $record->hgnc_id) }}">Learn more</a></div>

						<table class="panel-body table mb-0">
							<thead class="thead-labels">
								<tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 text-left">Disease</th>
								<th class="col-sm-2 text-center"></th>
								<th class="col-sm-2">HI Score &amp; TS Score</th>
								<th class="col-sm-1 text-center">Report &amp; Date</th>
								</tr>
							</thead>

							<tbody class="">
							@endif
								@php ($first = true) @endphp
								@foreach($record->dosage_curation_map as $key => $value)
										<tr>
											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												@if($loop->first)
												{{ $record->label }}
												@endif
											</td>

											<!--<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
												No evidence for
												@if ($key == "haploinsufficiency_assertion")
												 	haploinsufficiency
												@else
													triplosensitivity
												@endif
											</td>-->

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif ">
											</td>

											<td class="  @if($first != true) border-0 pt-0 @else pb-0 @endif text-center">
													@if ($key == "haploinsufficiency_assertion")
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}"> {{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::haploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@else
													<a class="btn btn-default btn-block text-left mb-2 btn-classification" href="{{ route('dosage-show', $record->hgnc_id) }}">{{ $record->dosage_curation->$key->dosage_classification->ordinal ?? null }}
														({{ \App\GeneLib::triploAssertionString($record->dosage_curation->$key->dosage_classification->ordinal ?? null) }})
													</a>
													@endif
											</td>

											<td class=" @if($first != true) border-0 pt-0 @else pb-0 @endif text-center"><a class="btn btn-xs btn-success btn-block btn-report" href="{{ route('dosage-show', $record->hgnc_id) }}"><i class="glyphicon glyphicon-file"></i> {{ $record->displayDate($record->dosage_curation->report_date) }}</a></td>
										</tr>
								@php ($first = false) @endphp
								@endforeach
								@php ($header_dos = false) @endphp
								@endisset


				@if($header_dos == false)
							</tbody>
						</table>
					</div>
				</div>
				@endisset
