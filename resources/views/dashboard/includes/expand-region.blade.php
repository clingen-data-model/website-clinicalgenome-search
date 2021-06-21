<div class="row">
    @foreach ($genes as $gene)
	<div class="col-md-2 border-right">
		{{ $gene->name }}
	</div>
    @endforeach
</div>
