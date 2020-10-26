
		<div class="row">
			<div class="col-md-3 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Patient: </td>
						<td class="small">{{ $curation->individual_id ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class="small text-muted pr-2">Last Update: </td>
						<td class="small">{{ $curation->display_date ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Coding DNA: </td>
						<td class="small">{{ $curation->coding_dna_change ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Protein: </td>
						<td class="small">{{ $curation->protein_change ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Ref Sequence: </td>
						<td class="small">{{ $curation->reference_sequence ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Inheritance: </td>
						<td class="small">{{ $curation->inheritance ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Variant: </td>
						<td class="small">{{ $curation->variant_type ?? ''}}</td>
					</tr>
						<td valign="top" class=" small text-muted pr-2">Disorders: </td>
						<td class="small">{{ $curation->display_disorders ?? ''}}</td>
					<tr>
					</tr>
				</table>
			</div>
		</div>
