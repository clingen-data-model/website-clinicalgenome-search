@if (!empty($record->pharmagkb))
@php global $currations_set; $currations_set = true; @endphp
				<div class="row justify-content-center">
					<div class="col-md-12">
					  <h3 id="link-gene-validity" class=" mt-3 mb-0"><img
						  src="/images/Pharmacogenomics-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Pharmacogenomics  - <a href="https://www.pharmgkb.org/"><img src="/images/pharmgkb.png" height="25"></a> </h3>
					  <div class="card mb-4">
						<div class="card-body p-0 m-0">
						  <table class="panel-body table mb-0">
							<thead class="thead-labels">
							  <tr>
								<th class="col-sm-1 ml-3 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 ">Drug</th>
								<th class="col-sm-2">Highest Level of Evidence</th>
								<th class="col-sm-2">Last Curated</th>
								<th class="col-sm-1 text-center">Information</th>
							  </tr>
							</thead>
							<tbody class="">
							  @foreach($record->pharmagkb as $idx => $entry)
							  @php $border = (isset($first) && $first  ? "border-0" : ""); @endphp
							  @if ($entry['pharmgkb_level_of_evidence'] == null)
								@continue;
							  @else
								@php $first = true; @endphp
							  @endif
							  <tr>
								<td class="border-0">{{ isset($border) && $border == "" ? $entry['gene'] : ''  }}</td>
								<td class="border-0"><a href="https://www.pharmgkb.org/chemical/{{ $entry['pa_id_drug'] }}">{{ $entry['drug'] }}</a></td>
								<td class="border-0">
									<a href="https://www.pharmgkb.org/page/clinAnnLevels">Level {{ $entry['pharmgkb_level_of_evidence'] }}</a></td>
									<td class="{{ $border ?? '' }}">{{ $record->displayDate($entry['notes'])  }}</td>
								@if (isset($border) && $border == "")
									<td class="border-0 text-center"><a class="btn btn-xs btn-success btn-block" target="_pharma" href="{{ $entry['guideline'] }}"><span class="pl-3 pr-3"><i class="glyphicon glyphicon-file"></i>  View</span></a></td>
								@else
								<td class="{{ $border ?? '' }} text-center"><a class="btn btn-xs btn-success btn-block" target="_pharma" href="{{ $entry['guideline'] }}"><span class="pl-3 pr-3"><i class="glyphicon glyphicon-file"></i>  View</span></a></td>
								@endif
							  </tr>
							  @endforeach
							</tbody>
						  </table>
						</div>
					  </div>
					</div>
				  </div>
					@endif