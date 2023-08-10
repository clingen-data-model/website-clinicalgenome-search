@extends('layouts.report')

@section('content')
  <div class="container">
    <div class="row justify-content-center">

      <div class="col-md-12 pr-0 mt-4 mb-4">
        <h3>Thank you for participating!</h3>
        <h3>If the survey popup does not display, click <a href="https://www.surveymonkey.com/r/VWSN5ZQ">here.</a></h3>
      </div>

    </div>
  </div>

@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
  <link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
  <link href="/css/select2.css" rel="stylesheet">
  <link href="/css/bootstrap-table-sticky-header.css" rel="stylesheet">
@endsection

@section('script_js')

<script>(function(t,e,s,o){var n,a,c;t.SMCX=t.SMCX||[],e.getElementById(o)||(n=e.getElementsByTagName(s),a=n[n.length-1],c=e.createElement(s),c.type="text/javascript",c.async=!0,c.id=o,c.src="https://widget.surveymonkey.com/collect/website/js/tRaiETqnLgj758hTBazgdyoBnjF46_2BTmD9R4Ew9Jo0qxO7DVUzzyNoYxBPZ3QpLn.js",a.parentNode.insertBefore(c,a))})(window,document,"script","smcx-sdk");</script>


@endsection
