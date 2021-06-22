<div class="row">
    <div class="col-md-11">
        <i><b>{{ $group->description }} {{ $group->type == 1 ? '(GRCh37)' : '(GRCh38)' }}</b></i>
    </div>
</div>
<div class="row">
    @foreach ($genes as $gene)
	<div class="col-md-2 border-right">
		{{ $gene->name }}
	</div>
    @endforeach
</div>
