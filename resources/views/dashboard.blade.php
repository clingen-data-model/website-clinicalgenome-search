@extends('layouts.app')

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">{{ $record->label }}</h1>
						<a class="btn btn-facts btn-outline-primary " role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
							<i class="far fa-caret-square-down"></i> View Gene Facts
						</a>
          </td>
        </tr>
      </table>

			</h1>
			{{-- <strong></strong> --}}

</div>

	<div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">
		  <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px">XX</span><br />Gene-Disease Validity<br />Classifications</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px">XX</span><br />Dosage Sensitivity<br />Classifications</li>
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">XX</span><br /> Clinical Actionability<br />Assertions</li>
			<!--@if ($follow)
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:green"></i></span><br /> Follow<br />Gene</li>
			@else
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:lightgray"></i></span><br /> Follow<br />Gene</li>
			@endif-->
		</ul>

</div>

			</div>
			<ul class="nav nav-tabs mt-1" style="">

					<li class="active" style="">
            <a href="#" class="">
              Following
            </a>
          </li>
          <li class="" style="">
            <a href=#" class="">Notification Preferences</a>
          </li>
          <li class="" style="">
            <a href="#"  class="" target="clinvar">Your Profile</a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboarsdfsdfdfsd</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
<<<<<<< HEAD
                    <div>
                        <a href="/api/logout" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            Logout
                        </a>    
                        <form id="frm-logout" action="/api/logout" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
=======
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                        Logout
                    </a>
                    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
>>>>>>> a009082073b760e6a1db786fa25c196b11f0296e
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_js')
<script>
	window.token = "{{ csrf_token() }}";
	window.bearer_token = Cookies.get('laravel_token');
</script>

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

@endsection
