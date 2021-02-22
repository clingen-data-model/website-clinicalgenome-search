<div class="row mb-1 mt-1">
	<div class="col-md-5">
		<table class="mt-3 mb-4">
            <tr>
                <td class="valign-top"></td>
                <td class="pl-2">
					<h1 class="h2 p-0 m-0">My ClinGen</h1>
                    <!-- <p>Welcome back {{ $user->name }}</p>-->
                 </td>
             </tr>
        </table>
    </div>

    <div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">
        <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px">{{  $total }}</span><br />Total Genes<br />Followed</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px">{{  $curations }}</span><br />Followed Genes <br /> With Classifications</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">{{ $recent }}</span><br /> Genes Updated In <br />The Last 90 Days</li>
        </ul>
    </div>
</div>

<ul class="col-md-10 nav nav-tabs mt-1" style="">
    <li class="{{ $active == "following" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-index') }}" class="">
            Following
        </a>
    </li>
    <li class="{{ $active == "preferences" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-preferences') }}" class="">
            Notification Preferences
        </a>
    </li>
    <li class="{{ $active == "profile" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-profile') }}" class="">
            Manage Your Profile
        </a>
    </li>
</ul>

<div class="col-md-2 btn-group float-right" role="group">
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notifications Status:  <span class="selection">On</span>
    </button>
    <ul class="dropdown-menu">
        <li><a data-value="Off">Pause Notifications</a></li>
        <li><a data-value="On">Resume Notifications</a></li>
    </ul>
</div>
