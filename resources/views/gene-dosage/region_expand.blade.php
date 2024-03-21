
		<div class="row">
			<div class="col-md-6 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Region: </td>
						<td class="small">{{ $haplo->title ?? $triplo->title }}</td>
					</tr>
					<tr>
						<td valign="top" class="small text-muted pr-2">ClinGen DCI ID: </td>
						<td class="small">{{ $haplo->source_uuid ?? $triplo_source_uuid }}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Loss Disease: </td>
						<td class="small">{{ empty($haplo->disease->label) ? 'N/A' : $haplo->disease->label }}
							{{-- @if (!empty($region->loss_omim))
							<a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $region->loss_omim }}" class="badge-info badge pointer ml-2">OMIM <i class="fas fa-external-link-alt"></i></a>
							@endif --}}
							@if (isset($haplo->disease->curie))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $haplo->disease->curie}}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Gain Disease: </td>
						<td class="small">{{ empty($triplo->disease->label) ? 'N/A' : $triplo->disease->label }}
							{{-- @if (!empty($region->gain_omim))
							<a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $region->gain_omim }}" class="badge-info badge pointer ml-2">OMIM <i class="fas fa-external-link-alt"></i></a>
							@endif --}}
							@if (isset($triplo->disease->curie))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $triplo->disease->curie }}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
				</table>
			</div>
		</div>
