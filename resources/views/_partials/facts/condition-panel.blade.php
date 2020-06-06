<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1">Gene Facts</h4>

					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd>{{ $record->symbol }}</dd>
						<dt>Ontological Reference</dt>
						<dd>{{  $record->getMondoString($record->iri) }}</dd>
						
					</dl>
			</div>
	</div>
</div>