@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h1 class=" display-4 ">Genes 
          </h1>
        </div>
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    
                </div>
                <table class="table table-striped table-hover">
                    <tr class="small">
                        <th>Gene</th>
                        <th>HGNC</th>
                        <th>Name</th>
                        <th>Curations</th>
                        <th>Date</th>
               
                    </tr>
                    @foreach($records as $record)
                    <tr>
                        <td><a href="{{ route('gene-show', $record->hgnc_id) }}"><strong>{{ $record->symbol }}</strong></a></td>
                        <td><a href="{{ route('gene-show', $record->hgnc_id) }}"><strong>{{ $record->hgnc_id }}</strong></a>
							<span class='badge text-xs'>Gene</span>
                        </td>
                        <td>{{ $record->name }}</td>
                        <td>
							<a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
								@if ($record->hasActionability ?? false)
									<img class="img-responsive" src="/images/clinicalActionability-on.png">
								@else
									<img class="img-responsive" src="/images/clinicalActionability-off.png">
								@endif
							</a>
							<a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
								@if ($record->hasValidity ?? false)
									<img class="img-responsive" src="/images/clinicalValidity-on.png">
								@else
									<img class="img-responsive" src="/images/clinicalValidity-off.png">
								@endif
                            </a>
                            <a class="menu_icon" href="{{ route('gene-show', $record->hgnc_id) }}">
								@if ($record->hasDosage ?? false)
									<img class="img-responsive" src="/images/dosageSensitivity-on.png">
								@else
									<img class="img-responsive" src="/images/dosageSensitivity-off.png">
								@endif
                            </a>
						</td>
                        <td>{{ $record->last_curated }}</td>
                    </tr>
                    @endforeach
				</table>
            </div>
            
            <nav class="text-center" aria-label="Page navigation">
              <ul class="pagination">
                <li>
                  <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="active"><a href="{{ route('gene-index') }}/page/1">1</a></li>
                <li><a href="{{ route('gene-index') }}/page/2">2</a></li>
                <li><a href="{{ route('gene-index') }}/page/3">3</a></li>
                <li><a href="{{ route('gene-index') }}/page/4">4</a></li>
                <li><a href="{{ route('gene-index') }}/page/5">5</a></li>
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
