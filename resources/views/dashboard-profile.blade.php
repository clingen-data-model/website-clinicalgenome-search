@extends('layouts.app')

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">Your Dashboard</h1>
            <p>Welcome back USER!</p>
          </td>
        </tr>
      </table>

			</h1>
			{{-- <strong></strong> --}}

</div>

	<div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">
		  <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px">XX</span><br />Total Genes<br />Followed</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px">XX</span><br />Followed Genes <br /> With Classifications</li>
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">XX</span><br /> Genes Updated In <br />The Last 90 Days</li>
		</ul>

</div>

			</div>
			<ul class="nav nav-tabs mt-1" style="">

					<li class="" style="">
            <a href="{{ route('dashboard-index') }}" class="">
              Following
            </a>
          </li>
          <li class="" style="">
                        <a href="{{ route('dashboard-preferences') }}" class="">
Notification Preferences</a>
          </li>
          <li class="active" style="">
                        <a href="{{ route('dashboard-profile') }}" class="">
Manage Your Profile</a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-sm-6">
                <h3>Manage Your Profile</h3>
                    </div>

                <div class="col-sm-6 text-right mt-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Notifications Status:  On
                            </button>
                            <ul class="dropdown-menu">
                            <li><a href="#">Pause Notifications</a></li>
                            </ul>
                        </div>
                        </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-12">

                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-5">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="First Name...">
                                </div>
                                <div class="col-sm-5">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="Last name...">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                            </form>

                    </div><!-- /.col-lg-6 -->
                    </div>

        </div>
    </div>
</div>
@endsection
