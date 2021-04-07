@if (!empty($record->pharma))
	@php global $currations_set; $currations_set = true; @endphp
				<div class="row justify-content-center">
					<div class="col-md-12">
					  <h3 id="link-gene-validity" class=" mt-3 mb-0"><img
						  src="/images/Pharmacogenomics-on.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs"> Pharmacogenomics - <a href="https://cpicpgx.org/"><img src="/images/cpic-200.png" class="mb-2" height="30"></a></h3>
					  <div class="card mb-4">
						<div class="card-body p-0 m-0">
						  <table class="panel-body table mb-0">
							<thead class="thead-labels">
							  <tr>
								<th class="col-sm-1 th-curation-group text-left">Gene</th>
								<th class="col-sm-4 ">Drug</th>
								<th class="col-sm-2">CPIC Level</th>
								<th class="col-sm-2">Date Accessed</th>
								<th class="col-sm-1 text-center">CPIC Clinical Guidelines</th>
							  </tr>
							</thead>
							<tbody class="">
							  @foreach($record->pharma as $idx => $entry)
								@php $border = ($idx > 0 && $entry['guideline'] == $record->pharma[$idx - 1]['guideline'] ? "border-0" : ""); @endphp
								<tr>
									<td class="{{ $border ?? '' }}">{{ isset($border) && $border == "" ? $entry['gene'] : ''  }}</td>
								<td class="{{ $border ?? '' }}">{{  $entry['drug'] }}</td>
								<td class="{{ $border ?? '' }}">
									@if (empty($entry['guideline']))
											<a href="https://cpicpgx.org/genes-drugs/">Level {{ $entry['cpic_level'] }}</a>
									@else
									<a href="https://cpicpgx.org/genes-drugs/">Level {{ $entry['cpic_level'] }}</a>
									@endif
								</td>
								<td class="{{ $border ?? '' }}">{{ isset($border) && $border == "" ? $record->displayDate($entry['updated_at']) : ''  }}</td>
								@if (isset($border) && $border == "")
									@if (empty($entry['guideline']))
									<td class=" text-center {{ $border ?? '' }}"><a class="btn btn-xs btn-success btn-block" target="_pharma" href="https://cpicpgx.org/genes-drugs">  <span class=""><i class="glyphicon glyphicon-file"></i>  Provisional</span></a></td>
									@else
									<td class=" text-center {{ $border ?? '' }}"><a class="btn btn-xs btn-success btn-block" target="_pharma" href="{{ $entry['guideline'] }}"><span class=""><i class="glyphicon glyphicon-file"></i>  Guideline</span></a></td>
									@endif
								@else
								<td class="{{ $border ?? '' }}"></td>
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