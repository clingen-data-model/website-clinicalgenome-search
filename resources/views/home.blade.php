@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>You are logged in!</div>

                    <button class="btn action-logout">
                        Logout
                    </button>

                    <p>The genes you are following:</p>

                    @foreach($genes as $gene)
                    <div>{{ $gene->name }}</div>
                    @endforeach
                    <form id="frm-logout" action="/api/logout" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script_js')

<script>
	
    $(function() {
    
        $('.action-logout').on('click', function() {

            $('#frm-logout').submit();

        });
    });

</script>

@endsection
