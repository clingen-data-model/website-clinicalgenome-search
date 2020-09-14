@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
					<h2>
					<a class="btn btn-lg btn-default float-right" href="{{ route('affiliate-index') }}" role="button"><i class="fas fa-arrow-left"></i> <span class='hidden-sm hidden-xs'>Back to list</span></a>
					{{$record->label}}</h2>
						<div class="mb-2 row">
							<div class="col-sm-6">
								<div class="input-group">
										<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-search"></i></span>
										<input type="text" class="form-control input-block search" id="interactive_search" placeholder="Filter results...">
								</div>
							</div>
						</div>

										<table id="interactive_table" class="table table-sm table-striped">
											<thead>
													<tr class="small text-center border-bottom-3 text-secondary">
															<th class="th-sort  bg-white border-1  text-uppercase">
																Curation Title
															</th>
															{{--<th class="th-sort  bg-white border-1  text-uppercase">
																Disease
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase hidden-sm hidden-xs">
																MOI
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase hidden-sm hidden-xs">
																SOP
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																Classification
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																Report &amp; Date
															</th>--}}
															<th class="th-sort  bg-white border-1  text-uppercase">
																Date
															</th>
													</tr>
											</thead>
											<tbody>
												@foreach ($record->curations as $curation)
													<tr>
															<td nowrap>
																<a href="{{ route('validity-show', $curation->perm_id) }}">
																	{{ $curation->perm_id }}
																</a>
															</td>
															<td nowrap data-sort="{{ $record->displaySortDate($curation->date) }}">
																{{ $record->displayDate($curation->date) }}
															</td>
															{{--<td>
																<a href="{{ route('condition-show', $curation->mondo) }}">
																	<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $curation->mondo }}"><i class="fas fa-info-circle text-muted hidden-sm hidden-xs"></i></span> {{ $curation->disease }}
																</a>
															</td>
															<td nowrap class='hidden-sm hidden-xs'>
																<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $curation->displayMoi($curation->moi, 'long') }}"><i class="fas fa-info-circle text-muted"></i></span> {{ $curation->displayMoi($curation->moi) }}
															</td>
															<td class='hidden-sm hidden-xs'>
																{{ $curation->sop }}
															</td>
															<td>

																<a class="btn text-left btn-block font-weight-bold btn-outline-secondary btn-sm pb-0 pt-0" href="{{ route('validity-show', $curation->perm_id) }}">{{ $curation->classification }}
															</td>
															<td data-sort="{{ $record->displaySortDate($curation->date) }}">
																<a class="btn btn-block text-left font-weight-bold btn-success btn-sm pb-0 pt-0" href="{{ route('validity-show', $curation->perm_id) }}"><i class="fas fa-file hidden-sm hidden-xs"></i> <span class='hidden-sm hidden-xs'>Report - </span>{{ $curation->displayDate($curation->date) }}</a>
															</td>
															--}}
													</tr>
												@endforeach
												</tbody>
										</table>
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


@section('script_js')
    <script>
        $(document).ready(function() {
            var table = $('#interactive_table').DataTable(
                {
                    pageLength: 250,
                    lengthChange: false,
                    //bFilter: false,
                    fixedHeader: true
                }
            );
            // #myInput is a <input type="text"> element
            $('#interactive_search').on( 'keyup', function () {
                table.search( this.value ).draw();
            } );
        } );
    </script>
@endsection
