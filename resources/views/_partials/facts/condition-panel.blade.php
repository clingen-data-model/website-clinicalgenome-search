<div class="collapse" id="collapseExample">
	<div class="row">
			<div class="col-sm-12  mt-0 pt-0 small">
					<h4 class="border-bottom-1"> Disease Facts  <span class=" ml-2" style="font-size:11px"><i class="fas fa-question-circle"></i> <a href='https://clinicalgenome.org/tools/clingen-website-faq/attribution/' class="_blank">External Data Attribution</a></span></h4>

					<dl class="dl-horizontal">
						<dt>Name</dt>
						<dd>{{ displayMondoLabel($record->symbol) }}</dd>
						<dt>Ontological Reference</dt>
						<dd>{{  $record->getMondoString($record->iri, true) }} {!! displayMondoObsolete($record->symbol) !!}</dd>
						<dt>Synonyms &amp; Equivalents</dt>
						<dd>
							<ul class="list-inline">
								<li><a href="#somewhereinclingen" class="text-normal badge bg-white text-primary border-1 border-primary">Noonan syndrome (OMIM:12345)</a></li>
								<li><a href="#somewhereoutofclingen" class="text-normal badge bg-white text-primary border-1 border-primary">Noonan's syndrome (DOID:12345) <i class="fas fa-external-link-alt"></i></a></li>
								<li><a href="#somewhereoutofclingen" class="text-normal badge bg-white text-primary border-1 border-primary">Turner's phenotype karyotype normal (ORPHA:12345) <i class="fas fa-external-link-alt"></i></a></li>
							</ul>
						</dd>

					</dl>
			</div>
	</div>
</div>
