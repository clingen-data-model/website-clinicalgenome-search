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

    <div class="col-md-2 btn-group mt-3" role="group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <strong>Notifications:  <span class="selection">On</span></strong>
        </button>
        <ul class="dropdown-menu">
            <li><a data-value="Off">Pause Notifications</a></li>
            <li><a data-value="On">Resume Notifications</a></li>
        </ul>
    </div>

    <div class="col-md-5 text-right mt-2 hidden-sm  hidden-xs">
        <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="text-18px action-edit-settings"><i class="fas fa-cog fa-lg pb-1"></i></span><br />Change<br />Settings</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="text-18px action-edit-reports"><i class="far fa-file fa-lg pb-1"></i></span><br />Manage<br />Reports</li>
            <!--<li class="text-stats line-tight text-center pl-3 pr-3"><span class="text-18px">{{ $recent }}</span><br /> Genes Updated In <br />The Last 90 Days</li>-->
        </ul>
    </div>
</div>

<ul class="col-md-10 nav nav-tabs mt-1" style="">
    <li class="{{ $active == "following" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-index') }}" class="">
            Home
        </a>
    </li>
   <!-- <li class="{{ $active == "preferences" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-preferences') }}" class="">
            Settings
        </a>
    </li>-->
    <li class="{{ $active == "reports" ? 'active' : '' }}" style="">
        <a href="{{ route('dashboard-reports') }}" class="">
            Reports
        </a>
    </li>
</ul>

