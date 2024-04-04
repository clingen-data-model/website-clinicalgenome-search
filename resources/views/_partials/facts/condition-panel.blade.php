<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1"> Disease Facts  <span class=" ml-2" style="font-size:11px"><i class="fas fa-question-circle"></i> <a href='https://clinicalgenome.org/tools/clingen-website-faq/attribution/' class="_blank">External Data Attribution</a></span></h4>

					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd>{{ displayMondoLabel($disease->label) }}
							<a target='external' href="{{env('URL_MONARCH')}}{{ $record->iri }}" class="ml-1 badge-info badge pointer"> MONDO <i class="fas fa-external-link-alt"></i> </a>
                        @if($disease->omim)
							<a target='external' href="{{ config('diseases.omim') }}{{ $disease->omim}}" class="ml-1 badge-info badge pointer"> OMIM <i class="fas fa-external-link-alt"></i> </a>
						@endif
						@if($disease->orpha_id)
							<a target='external' href="{{ config('diseases.orphanet') }}{{ $disease->orpha_id }}" class="ml-1 badge-info badge pointer"> Orphanet <i class="fas fa-external-link-alt"></i> </a>
						@endif
                        </dd>
						<dt>Ontological Reference</dt>
						<dd>{{  $record->getMondoString($record->iri, true) }} {!! displayMondoObsolete($record->symbol) !!}</dd>
                        <dt>Description</dt>
						<dd> {{ $disease->description }}  <i>(Source: <a href="{{env('URL_MONARCH')}}{{ $record->iri }}">MONDO</a>)</i></dd>
                        <dt>Synonyms</dt>
						<dd>
                            @if (count($disease->synonyms))
                                <ul class="m-0 p-0 list-unstyled">
                                    @foreach($disease->synonyms as $synonym)
                                        <li class="m-0 p-0"> {{ $synonym }} </li>
                                    @endforeach
                                </ul>
                            @else
                                No Synonyms
                            @endif
                        </dd>
					</dl>
			</div>
	</div>
</div>
