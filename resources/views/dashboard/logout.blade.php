@extends('layouts.app')

@section('content-heading')

   
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
		
		<div id="dashboard-logout">
    <h4>You are now logged out.  </h4>
    <p>This is a placeholder page for the dashboard route when there is no access token</p>
    <p>We can turn this into billboard of events, notices, marketing, etc.</p>
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
