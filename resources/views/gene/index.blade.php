@extends('layouts.app')

@section('content')
<div class="container">
		<div class="row justify-content-center">
				<div class="col-md-12">
						<h2>Genes</h2>
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
																 @sortablelink('symbol','Gene Symbol')
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																@sortablelink('hgnc_id','HGNC ID')
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase hidden-sm hidden-xs">

																@sortablelink('name','Gene Name')
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																Curations
															</th>
															<th nowrap class="th-sort  bg-white border-1  text-uppercase">

																@sortablelink('last_curated','Last Curation Date')
															</th>
													</tr>
											</thead>
											<tbody>
												@foreach ($records as $record)
													<tr>
                            <td><a href="{{ route('gene-show', $record->hgnc_id) }}"><strong>{{ $record->symbol }}</strong></a></td>
                            <td><a class='text-muted small' href="{{ route('gene-show', $record->hgnc_id) }}">{{ $record->hgnc_id }}</a></td>
                            <td class="text-muted small">{{ $record->name }}</td>
                            <td nowrap>
                              <a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
                                @if ($record->hasActionability ?? false)
                                  <img class="" src="/images/clinicalActionability-on.png" style="width:30px">
                                @else
                                  <img class="" src="/images/clinicalActionability-off.png" style="width:30px">
                                @endif
                              </a>
                              <a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
                                @if ($record->hasValidity ?? false)
                                  <img class="" src="/images/clinicalValidity-on.png" style="width:30px">
                                @else
                                  <img class="" src="/images/clinicalValidity-off.png" style="width:30px">
                                @endif
                                            </a>
                                            <a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
                                @if ($record->hasDosage ?? false)
                                  <img class="" src="/images/dosageSensitivity-on.png" style="width:30px">
                                @else
                                  <img class="" src="/images/dosageSensitivity-off.png" style="width:30px">
                                @endif
                                            </a>
                            </td>
                            <td class="text-right">{{ $record->displayDate($record->last_curated) }}</td>
                        </tr>
												@endforeach
												</tbody>
										</table>
				</div>
		</div>
</div>

			{!! $records->appends(\Request::except('page'))->render() !!}

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
