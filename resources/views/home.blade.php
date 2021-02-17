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

					<li class="active" style="">
            <a href="{{ route('dashboard-index') }}" class="">
              Following
            </a>
          </li>
          <li class="" style="">
                        <a href="{{ route('dashboard-preferences') }}" class="">
Notification Preferences</a>
          </li>
          <li class="" style="">
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
                <h3>You're Currently Following</h3>
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
                    <div class="col-lg-9">
                        <input type="text" class="form-control" placeholder="Filter by gene " aria-label="...">
                        <!-- /btn-group -->
                    </div>
                    <div class="col-lg-3">
                        <button class="btn btn-primary btn-block">Add New Gene To Follow</button>
                        <!-- /btn-group -->
                    </div><!-- /.col-lg-6 -->
                    </div>
                <table class="table">
                    <thead>
                        <tr>
                        <th class="col-sm-2">Gene Symbol</th>
                        <th class="col-sm-2">HGNC</th>
                        <th class="col-sm-3">Curation Status</th>
                        <th class="col-sm-2">Date Last Update</th>
                        <th class="col-sm-3">Sending Preference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row">ABC123</th>
                        <td>HGNC:1234</td>
                        <td>
                            <img src="/images/clinicalValidity-on.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-off.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-off.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-on.png" width="22" height="22">
                            <img src="/images/clinicalActionability-on.png" width="22" height="22">
                        </td>
                        <td>12/12/2020</td>
                        <td>
                            <div class="btn-group w-100">
                            <button type="button" class="text-left btn btn-block btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Send Daily
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Daily</a></li>
                                <li><a href="#">Weekly (Update Default)</a></li>
                                <li><a href="#">Monthly</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Paused</a></li>
                            </ul>
                            </div>
                        </td>
                        </tr>
                        <tr>
                        <th scope="row">CCC123</th>
                        <td>HGNC:2234</td>
                        <td>
                            <img src="/images/clinicalValidity-on.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-off.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-off.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-on.png" width="22" height="22">
                            <img src="/images/clinicalActionability-on.png" width="22" height="22">
                        </td>
                        <td>12/12/2020</td>
                        <td>
                            <div class="btn-group w-100">
                            <button type="button" class="text-left btn btn-block btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Send Weekly (Update Default)
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Daily</a></li>
                                <li><a href="#">Weekly (Update Default)</a></li>
                                <li><a href="#">Monthly</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Paused</a></li>
                            </ul>
                            </div>
                        </td>
                        </tr>
                        <tr>
                        <th scope="row">123XXX</th>
                        <td>HGNC:4234</td>
                        <td>
                            <img src="/images/clinicalValidity-off.png" width="22" height="22">
                            <img src="/images/dosageSensitivity-off.png" width="22" height="22">
                            <img src="/images/Pharmacogenomics-off.png" width="22" height="22">
                            <img src="/images/variantPathogenicity-off.png" width="22" height="22">
                            <img src="/images/clinicalActionability-off.png" width="22" height="22">
                        </td>
                        <td>Not curated...</td>
                        <td>
                            <div class="btn-group w-100">
                            <button type="button" class="text-left btn btn-block btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Send Daily (New Default)
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Daily (New Default)</a></li>
                                <li><a href="#">Weekly</a></li>
                                <li><a href="#">Monthly</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Paused</a></li>
                            </ul>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                    </table>
        </div>
    </div>
</div>
@endsection
