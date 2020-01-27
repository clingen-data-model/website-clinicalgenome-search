@extends('layouts.app')

@section('content')
<div class="container">
		<div class="row justify-content-center">
				<div class="col-md-12">
						<div class="card">
								<div class="card-header">Curations (XX Number of genes curated)</div>
								{{--  NOTE - this page will be further desgined... just grabbing data  --}}
								<div class="card-body">
										<table class="table table-sm table-striped">
											<thead>
													<tr>
															<td>
																Gene
															</td>
															<td>
																Validity
															</td>
															<td>
																Actionability
															</td>
															<td>
																Haploinsufficiency
															</td>
															<td>
																Triplosensitivity
															</td>
													</tr>
											</thead>
											<tbody>
													<tr>
															<td>
																<a href="#genepage">A2ML1</a>
															</td>
															<td>
																<a href="#genepage">Y/N Validity</a>
															</td>
															<td>
																<a href="#genepage">Y/N Actionability</a>
															</td>
															<td>
																<a href="#genepage">Y/N Haploinsufficiency</a>
															</td>
															<td>
																<a href="#genepage">Y/N Triplosensitivity</a>
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
