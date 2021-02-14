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

                    You are logged in!
                    <div>
                        <a href="/api/logout" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                            Logout
                        </a>    
                        <form id="frm-logout" action="/api/logout" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
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
