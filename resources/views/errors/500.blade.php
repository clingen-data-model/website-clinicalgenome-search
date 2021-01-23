@extends('layouts.app')
@php
  $display_tabs['active'] = "";
  $display_tabs['active'] = "Sorry, we could not complete this request at this time";
@endphp
@section('content')
<div class="container">
	<div class="row justify-content-center text-center">
      <div class="col-md-12 curated-genes-table mt-5">
          <h1 class="h2 p-0 m-0">Sorry, we could not complete this request at this time...</h1>
            <p>Please use the search or navigation bar above.</p>

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
