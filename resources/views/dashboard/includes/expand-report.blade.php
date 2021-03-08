<div class="row">
	<div class="col-md-6 border-right">
		<table class="table-sm m-0">
			<tr>
				<td valign="top" class=" small text-muted pr-2">Title: </td>
				<td class="small">{{ $title->title ?? ''}}</td>
			</tr>
			<tr>
				<td valign="top" class="small text-muted pr-2">Created: </td>
				<td class="small">{{ $title->display_created_date ?? ''}}</td>
			</tr>
		</table>
	</div>
	<div class="col-md-6 border-right">
		<table class="table-sm m-0">
			<tr>
				<td valign="top" class=" small text-muted pr-2">Description: </td>
				<td class="small">{{ $title->description ?? ''}}</td>
			</tr>
			<tr>
				<td valign="top" class=" small text-muted pr-2">Protein: </td>
				<td class="small">{{ $title->display_type ?? ''}}</td>
			</tr>
		</table>
	</div>
</div>
