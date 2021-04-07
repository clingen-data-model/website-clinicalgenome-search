
		<div class="row">
			<div class="col-md-2 border-right">
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
			<div class="col-md-7 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Loss Disease: </td>
						<td class="small">{{ empty($gene->loss_disease) ? 'N/A' : $gene->loss_disease }}
							@if (!empty($gene->loss_mondo))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $gene->loss_mondo }}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Gain Disease: </td>
						<td class="small">{{ empty($gene->gain_disease) ? 'N/A' : $gene->gain_disease }}
							@if (!empty($gene->gain_mondo))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $gene->gain_mondo }}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
				</table>
			</div>
		</div>
