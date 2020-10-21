@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
      <div class="col-md-7 curated-genes-table">
        <h1><span id="gene-count"></span><img src="/images/adept-icon-circle-gene.png" width="50" height="50"> List of HGNC Genes</h1>
        {{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
      </div>

      <div class="col-md-12">
        <div class="text-right">{{  $all->links() }}</div>
        <hr class="m-1 p-1" />
        <ul class="row list-unstyled mt-4">
			@foreach ($all as $record)
          <li class="col-xs-6 col-sm-4 col-md-3 col-lg-2"><a href="{{ route('gene-show', $record->hgnc_id) }}" class=""><i class="fas fa-search text-muted"></i> {{ $record->name }}</a></li>
      @endforeach
        </ul>
        <hr class="m-1 p-1" />
        <div class="text-right">{{  $all->links() }}</div>
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

@section('script_js')

<!-- load up all the local formatters and stylers -->

@endsection
