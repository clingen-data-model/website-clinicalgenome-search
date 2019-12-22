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
													<tr>
															<td>
																<a href="#genepage">A2ML1</a>
															</td>
															<td>
																<a href="#genepage">Noonan syndrome with multiple lentigines</a>
																<div class="small">MONDO:0007893</div>
															</td>
															<td>
																AR
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																SOP6
																{{--  Once data is available in model or controller it will be configured to be standardized  --}}
															</td>
															<td>
																<a href="#report-link">Definitive</a>
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
