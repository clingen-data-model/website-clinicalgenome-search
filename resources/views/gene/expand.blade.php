
		<div class="row">
			<div class="col-md-3 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Symbol: </td>
						<td class="small">{{ $gene->name ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class="small text-muted pr-2">HGNC ID: </td>
						<td class="small">{{ $gene->hgnc_id ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Previous: </td>
						<td class="small">{{ $gene->display_previous ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Aliases: </td>
						<td class="small">{{ $gene->display_aliases ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Cytoband: </td>
						<td class="small">{{ $gene->location ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Description: </td>
						<td class="small">{{ $gene->description ?? ''}}</td>
					</tr>
				</table>
			</div>
		</div>
