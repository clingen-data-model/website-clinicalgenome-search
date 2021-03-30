@extends('layouts.app')

@section('content-heading')


@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">

		<div id="dashboard-logout">
      {{-- <div class="row"> --}}
        <div class="col-sm-12 mt-3">
          <h2>About ClinGen's Dashboard</h2>
        </div>
        <div class="col-sm-8">
            <h4>Key Features</h4>
            <p>The following are key features available when you create your free account.</p>
            <ul>
              <li>
                Follow genes of interest and see them tracked on a private dashboard.
              </li>
              <li>
                Receive emails when ClinGen updates one of the genes you follow.
              </li>
              <li>
                Be notified when a curation activity updates one of their curations.
              </li>
              <li>
                Customize the frequency of the emails you receive.
              </li>
              <li>
                Access to reports based on the genes you follow.
              </li>
              <li><a href="https://clinicalgenome.org/tools/clingen-website/">Learn more about these features here.</a>
              </li>
            </ul>
            <hr />
            <h4>Quick Overview</h4>
            <img src="/images/dashboard-help-1.jpg" alt="Dashboard" class="img-fluid img-thumbnail" />
            <ol class="mt-4">
              <li>Once you login, you will see your name here. From here you can access your dashboard and logout.</li>
              <li>To follow a gene from the page look for the star (<i class="fas fa-star"></i>).
                <ul>
                  <li>This is how you are able to quickly follow a gene.  </li>
                  <li>You know you are following a gene when it turns green (<i class="fas fa-star" style="color:green"></i>).</li>
                </ul>
              </li>
            </ol>
        </div>

        <div class="col-sm-4 border-l">
          <div class="text-center">
          <button class='btn-lg btn-primary action-login btn-block'>Login To Get Started</button>
          <i class="text-sm text-muted">You are currently logged-out</i>
          </div>
          {{-- <hr />
            <p>This is a placeholder page for the dashboard route when there is no access token</p>
            <p>We can turn this into billboard of events, notices, marketing, etc.</p> --}}
        </div>
      {{-- </div> --}}
		</div>

    </div>
</div>
@endsection

@section('modals')


@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
    <link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
    <style>
        .profile-background
        {
          position: relative;
          top: 0;
          left: 0;
        }
        .avatar
        {
          position: absolute;
          top: 0px;
          left: 12px;
        }
        .avatar-name
        {
          position: absolute;
          top: 148px;
          left: 30px;
          font-size: 18px;
        }
        .avatar-title
        {
          position: absolute;
          top: 172px;
          left: 30px;
          font-size: 14px;
        }
		.size {
			height: 100px;
			width: 100px;
			display: block;
			margin-left: auto;
			margin-right: auto;
			}

		.caption {
			font-size: 14px;
			color: black;
			text-align: center;
			width: 200px;
		}
		.folder-effects:hover .size {
			opacity: 0.7;
		}
    </style>

@endsection

@section('script_js')

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>

<script src="/js/genetable.js"></script>

<script>

    $(function() {

		/*
		** If a user logs back in on this page, we want them to see the dashboard
		*/
		$( "#dashboard-logout" ).on( "logout", function( event, param1, param2 ) {

			window.location.reload();

		});
	});


</script>

@endsection
