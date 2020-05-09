@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<h2>Gene Disease Validity</h2>
				<div class="mb-2 row">
					<div class="col-sm-6">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-search"></i></span>
							<input type="text" class="form-control input-block search" id="interactive_search" placeholder="Filter results...">
						</div>
					</div>
					<div class="col-sm-6">
						<div class=" pt-1 text-right">
							<span class='text-muted'>Curation Count:</span> <strong>{{ count($records)}}</strong> |
							<a href="#"><i class="fas fa-file-download"></i> Download Summary Data</a>
						</div>
					</div>
				</div>
				<table id="interactive_table" class="table table-sm table-striped">
					<thead>
						<tr class="small text-center border-bottom-3 text-secondary">
							<th class="th-sort  bg-white border-1  text-uppercase">
								Gene
							</th>
							<th class="th-sort  bg-white border-1  text-uppercase">
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
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($records as $record)
							<tr>
								<td nowrap nowrap data-search="{{ $record->hgnc_id }} {{ $record->symbol }}">
									<a href="{{ route('gene-show', $record->href) }}">
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{$record->hgnc_id}}"><i class="fas fa-info-circle text-muted hidden-sm hidden-xs"></i></span>&nbsp;<strong>{{ $record->symbol }}</strong>
									</a>
								</td>
								<td data-search="{{ $record->disease }} {{ $record->displayReplaceCharacter($record->mondo) }}">
									<a href="{{ route('condition-show', $record->mondo) }}">
										<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $record->displayReplaceCharacter($record->mondo) }}">
											<i class="fas fa-info-circle text-muted hidden-sm hidden-xs"></i>
										</span> {{ $record->disease }}
									</a>
								</td>
								<td nowrap class='hidden-sm hidden-xs'>
									<span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $record->displayMoi($record->moi, 'long') }}"><i class="fas fa-info-circle text-muted"></i></span> {{ $record->displayMoi($record->moi) }}
									{{--  Once data is available in model or controller it will be configured to be standardized  --}}
								</td>
								<td class='hidden-sm hidden-xs'>
									{{ $record->sop }}
								</td>
								<td>

									<a class="btn text-left btn-block font-weight-bold btn-outline-secondary btn-sm pb-0 pt-0" href="{{ route('validity-show', $record->perm_id) }}">{{ $record->classification }}
								</td>
								<td data-sort="{{ $record->displaySortDate($record->date) }}">
									<a class="btn btn-block text-left font-weight-bold btn-success btn-sm pb-0 pt-0" href="{{ route('validity-show', $record->perm_id) }}"><i class="fas fa-file hidden-sm hidden-xs"></i> <span class='hidden-sm hidden-xs'>Report - </span>{{ $record->displayDate($record->date) }}</a>
								</td>
							</tr>
						@endforeach
						</tbody>
										</table>
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
