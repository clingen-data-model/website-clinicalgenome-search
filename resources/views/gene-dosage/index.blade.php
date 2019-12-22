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
															<td>
																Disease (New/Optional)
															</td>
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
													<tr>
															<td>
																<a href="#genepage">A2ML1</a>
															</td>
															<td>
																<a href="#genepage">Noonan syndrome with multiple lentigines</a>
																<div class="small">MONDO:0007893</div>
															</td>
															<td>
																Sufficient Evidence for Haploinsufficiency
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																No Evidence for Triplosensitivity
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																<a href="#report-link-at-ncbi">View Detail</a>
															</td>
															<td>
																XX/XX/XXXX
															</td>
													</tr>
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
