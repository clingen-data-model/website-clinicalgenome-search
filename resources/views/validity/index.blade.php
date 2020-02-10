@extends('layouts.app')

@section('content')
<div class="container">
		<div class="row justify-content-center">
				<div class="col-md-12">
						<div class="card">
								<div class="card-header">Gene Disease Validity</div>

								<div class="card-body">
										<table class="table table-sm table-striped">
											<thead>
													<tr>
															<td>
																Gene
															</td>
															<td>
																Disease
															</td>
															<td>
																MOI
															</td>
															<td>
																SOP
															</td>
															<td>
																Classification
															</td>
															<td>
																Date
															</td>
													</tr>
											</thead>
											<tbody>
												@foreach ($records as $record)
													<tr>
															<td>
																<a href="{{ route('gene-show', $record->gene_hgnc) }}">{{ $record->gene_symbol }}</a>
															</td>
															<td>
																<a href="{{ route('condition-show', $record->mondo) }}">{{ $record->disease }}</a>
																<div class="small">{{ $record->mondo }}</div>
															</td>
															<td>
																*AR*
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																{{ $record->sop }}
															</td>
															<td>
																<a href="{{ route('validity-show', $record->perm_id) }}">{{ $record->classification }}</a>
															</td>
															<td>
																{{ $record->displayDate($record->date) }}
															</td>
													</tr>
												@endforeach
												</tbody>
										</table>
								</div>
						</div>
				</div>
		</div>
</div>
@endsection

@section('heading')
<div class="content ">
		<div class="section-heading-content">
		</div>
</div>
@endsection
