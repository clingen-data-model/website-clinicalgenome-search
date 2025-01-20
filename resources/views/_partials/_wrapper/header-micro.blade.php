<div class="section-heading section-heading-groups">
	<div class="container text-right small text-muted">
		<div class="container">
			<ul class="list-inline pull-right m-0 p-1">
				<li class=''>
					<a class=' text-white' href='https://www.clinicalgenome.org/share-your-data/'>
						<span class="visible-inline-md visible-inline-lg visible-inline-xl">Data Sharing Resources</span>
					</a>
				</li>
				<li class=''>
					<a class=' text-white' href='https://www.clinicalgenome.org/genomeconnect/'>GenomeConnect</a>
				</li>
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='https://www.clinicalgenome.org/about/events/'>Events</a>
				</li>
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='https://www.clinicalgenome.org/docs/terms-of-use/'>Citing ClinGen</a>
				</li>
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='https://www.clinicalgenome.org/about/contact-clingen/'>Contact</a>
				</li>
				{{-- <li class='visible-inline-md visible-inline-lg visible-inline-xl text-white'>
					|
				</li> --}}
				@if(Auth::guard('api')->check())
				<li id="dashboard-menu" class="dropdown">
					<a href="#" class="dropdown-toggle text-white" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-user" aria-hidden="true"> </i>
						<span id="nav-user-name"> {{ $user->name ?? 'Member' }} </span>
					</a>
					<ul class="dropdown-menu">
					  <li><a href="/dashboard"><i class="fas fa-tachometer-alt mr-1"></i>Dashboard</a></li>
					  <li role="separator" class="divider"></li>
					  <li><!--<a class="dropdown-item" href="{{ route('logout') }}"
						onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">-->
						<a href="#" class="action-logout-now">
						<i class="fas fa-sign-out-alt mr-1 text-danger"></i>Logout
						</a>
						<form id="logout-form" action="/logout" method="POST" style="display: none;">
							@csrf
						</form></li>
					</ul>
				</li>
				<li id="login-menu" class='visible-inline-md visible-inline-lg visible-inline-xl' style="display:none">
					<a class='text-white action-login' href='#'>Login</a>
				</li>
				@else
				<li id="dashboard-menu" class="dropdown" style="display:none">
					<a href="#" class="dropdown-toggle text-white" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-user" aria-hidden="true"> </i>
						<span id="nav-user-name"> {{ $user->name ?? 'Member' }} </span>
					</a>
					<ul class="dropdown-menu">
					  <li><a href="/dashboard"><i class="fas fa-tachometer-alt mr-1"></i>Dashboard</a></li>
					  <li role="separator" class="divider"></li>
					  <li><a class="dropdown-item" href="{{ route('logout') }}"
						onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">
						<i class="fas fa-sign-out-alt mr-1 text-danger"></i>Logout
						</a>
						<form id="logout-form" action="/logout" method="POST" style="display: none;">
							@csrf
						</form></li>
					</ul>
				</li>
				<li id="login-menu" class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class='text-white action-login' href='#'>Login</a>
				</li>
				@endif
			</ul>
		</div>
	</div>
</div>