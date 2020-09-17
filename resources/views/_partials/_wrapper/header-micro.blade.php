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
					<a class=' text-white' href='https://www.clinicalgenome.org/about/contact-clingen/'>Contact</a>
				</li>
				{{-- <li class='visible-inline-md visible-inline-lg visible-inline-xl text-white'>
					|
				</li> --}}
				@guest
				{{-- <li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='{{ route('login') }}'><i class="fas fa-user-circle"></i> Login</a>
				</li>
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='{{ route('register') }}'><i class="fas fa-sign-in-alt"></i> Register</a>
				</li>
				@else
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href='#'><i class="fas fa-user-circle"></i> {{ Auth::user()->name }}</a>
				</li>
				<li class='visible-inline-md visible-inline-lg visible-inline-xl'>
					<a class=' text-white' href="{{ route('logout') }}"
             onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
				</li> --}}
				@endguest
			</ul>
		</div>
	</div>
</div>