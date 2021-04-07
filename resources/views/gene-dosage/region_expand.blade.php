
		<div class="row">
			<div class="col-md-6 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Symbol: </td>
						<td class="small">{{ $region->label ?? ''}}</td>
					</tr>
					<tr>
						<td valign="top" class="small text-muted pr-2">ClinGen DCI ID: </td>
						<td class="small">{{ $region->issue ?? ''}}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6 border-right">
				<table class="table-sm m-0">
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Loss Disease: </td>
						<td class="small">{{ empty($region->loss_pheno_name) ? 'N/A' : $region->loss_pheno_name }}
							@if (!empty($region->loss_omim))
							<a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $region->loss_omim }}" class="badge-info badge pointer ml-2">OMIM <i class="fas fa-external-link-alt"></i></a>
							@endif
							@if (!empty($region->loss_mondo))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $region->loss_mondo }}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
					<tr>
						<td valign="top" class=" small text-muted pr-2">Curated Gain Disease: </td>
						<td class="small">{{ empty($region->gain_pheno_name) ? 'N/A' : $region->gain_pheno_name }}
							@if (!empty($region->gain_omim))
							<a target='external' href="{{env('CG_URL_OMIM_GENE')}}{{ $region->gain_omim }}" class="badge-info badge pointer ml-2">OMIM <i class="fas fa-external-link-alt"></i></a>
							@endif
							@if (!empty($region->gain_mondo))
							<a target='external' href="{{env('CG_URL_MONARCH')}}{{ $region->gain_mondo }}" class="badge-info badge pointer ml-2">MONDO <i class="fas fa-external-link-alt"></i></a>
							@endif
						</td>
					</tr>
				</table>
			</div>
		</div>
