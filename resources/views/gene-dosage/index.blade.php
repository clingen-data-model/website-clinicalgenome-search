@extends('layouts.app')

@section('content')
<div class="container">
		<div class="row justify-content-center">
				<div class="col-md-12">
						<div class="card">
								<div class="card-header">Gene Dosage Curations</div>
								{{--  NOTE - this currently has pagination so that may be needed... curious to test without  --}}
								<div class="card-body">
										<table class="table table-sm table-striped">
											<thead>
													<tr>
															<td>
																Gene
															</td>
															<!-- <td>
																Disease (New/Optional)
															</td> -->
															<td>
																Haploinsufficiency
															</td>
															<td>
																Triplosensitivity
															</td>
															<td>
																Report
															</td>
															<td>
																Date
															</td>
													</tr>
											</thead>
											<tbody>
												@foreach($records as $record)
													<tr>
															<td>
																<a href="{{ route('gene-show', $record->hgnc_id) }}">{{ $record->symbol }}<br \>
																<span class="text-muted small">{{ $record->hgnc_id }}</span>
																</a>
															</td>
															<!--<td>
																<a href="#genepage">Noonan syndrome with multiple lentigines</a>
																<div class="small">MONDO:0007893</div>
															</td>-->
															<td>
																Sufficient Evidence for Haploinsufficiency
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																No Evidence for Triplosensitivity
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																<a href="{{ env('GENE_DOSAGE_PAGES', '#') }}/clingen_gene.cgi?sym={{ $record->symbol }}&subject=">View Detail</a>
															</td>
															<td>
																{{ $record->displayDate($record->last_curated) }}
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
