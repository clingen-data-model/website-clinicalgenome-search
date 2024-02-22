<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1"> Disease Facts  <span class=" ml-2" style="font-size:11px"><i class="fas fa-question-circle"></i> <a href='https://clinicalgenome.org/tools/clingen-website-faq/attribution/' class="_blank">External Data Attribution</a></span></h4>

					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd>{{ displayMondoLabel($record->label) }}</dd>
						<dt>Ontological Reference</dt>
						<dd>{{  $record->getMondoString($record->iri, true) }} {!! displayMondoObsolete($record->symbol) !!}</dd>

					</dl>
			</div>
	</div>
</div>
