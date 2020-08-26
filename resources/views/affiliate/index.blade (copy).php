@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
						<h2>Curation by Gene Disease Validity Group</h2>
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
																Expert Panels
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																ClinGen Affilation ID
															</th>
															<th class="th-sort  bg-white border-1  text-uppercase">
																Number of Curations
															</th>
													</tr>
											</thead>
											<tbody>
                    @foreach($records as $record)
                    <tr>
                          <td>
                            <a href="{{ route('affiliate-show', $record->displayAffiliateIdFromIri($record->agent)) }}"><strong>{{ $record->label }}</strong></a>
                          </td>
                          <td>{{ $record->displayAffiliateIdFromIri($record->agent) }}
                          </td>
                          <td><a href="{{ route('affiliate-show', $record->displayAffiliateIdFromIri($record->agent)) }}">{{ $record->count }} curations</a>
                          </td>
                    </tr>
                    @endforeach
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
