<div class="row">
    <div class="col-md-11">
        <i><b>{{ $group->smart_title }}</b></i>
    </div>
</div>
<div class="row">
    @foreach ($genes as $gene)
	<div class="col-md-2 border-right">
		{{ $gene->name }}
	</div>
    @endforeach
</div>
