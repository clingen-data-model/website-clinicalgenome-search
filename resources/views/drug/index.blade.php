@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
						<h2>Drugs &amp; Medications</h2>
						<div class="mb-2 row">
							<div class="col-sm-6">
								<div class="input-group">
										<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-search"></i></span>
										<input type="text" class="form-control input-block search" id="interactive_search" placeholder="Filter results...">
								</div>
							</div>
							<div class="col-sm-6">
								{{-- <div class=" pt-1 text-right">
                  <span class='text-muted'>Gene Count:</span> <strong>{{ count($records)}}</strong>
                </div> --}}
							</div>
						</div>

                <table id="interactive_table" class="table table-sm table-striped">
											<thead>
													<tr class="small text-center border-bottom-3 text-secondary">
															<th nowrap class="th-sort  bg-white border-1  text-uppercase">
																 @sortablelink('curie','RXNORM')
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																@sortablelink('label','Name')
															</th>
													</tr>
											</thead>
											<tbody>
												@foreach ($records as $record)
													<tr>
                            <td>
                              <a href="{{ route('drug-show', $record->curie) }}">RXNORM:{{ $record->curie }}</a>
                            </td>
                            <td>
                              <a class="text-muted small" href="{{ route('drug-show', $record->curie) }}">{{ $record->label }}</a>
                            </td>
                        </tr>
												@endforeach
												</tbody>
                    </table>



				</table>
            </div>

            <nav class="text-center" aria-label="Page navigation">
              <ul class="pagination">
                <li>
                  <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="active"><a href="{{ route('drug-index') }}/page/1">1</a></li>
                <li><a href="{{ route('drug-index') }}/page/2">2</a></li>
                <li><a href="{{ route('drug-index') }}/page/3">3</a></li>
                <li><a href="{{ route('drug-index') }}/page/4">4</a></li>
                <li><a href="{{ route('drug-index') }}/page/5">5</a></li>
                <li>
                  <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
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

@section('script_js')

@endsection
