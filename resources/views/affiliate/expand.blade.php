
<div class="row equal">
	<div class="col-md-2">
		Chairs:
	</div>
	<div class="col-md-4 border-left">
		@foreach($panel->member['members']['chair'] as $member)
		@if ($member['group'] == $panel->title)
		<div class="font-weight-bold">{{ $member['name'] }}</div>
		<div class="mb-1">{{ html_entity_decode(str_replace('&amp;#039;', "'", $member['inst']), ENT_QUOTES) }}</div>
		@endif
		@endforeach
	</div>
	<div class="col-offset-1 col-md-2">
		Coordinators:
	</div>
	<div class="col-md-3 border-left">
		@foreach($panel->member['members']['coordinator'] as $member)
		@if ($member['group'] == $panel->title)
		<div class="font-weight-bold">{{ $member['name'] }}</div>
		<div class="mb-1">{{ html_entity_decode(str_replace('&amp;#039;', "'", $member['inst']), ENT_QUOTES) }}</div>
		@endif
		@endforeach
	</div>
</div>
<hr />
<div class="row">
	<div class="col-md-2">
		Scope:
	</div>
	<div class="col-md-10 border-left">
		@if (empty($panel->summary))
		<div class="text-center"><i>No summary information available for this panel</i></div>
		@else
		{!! $panel->summary !!}
		@endif
	</div>
</div>
