@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="my-3">
            <h2> Members Not Synced with ProcessWire </h2>
            <p> We need to update the correct email address of these members in Processwire</p>
        </div>

        @foreach($members as $index => $member)
            <div class="row">
                <div class="col-md-1"> {{ $index + 1 }}</div>
                <div class="col-md-3"> {{ $member->first_name }}</div>
                <div class="col-md-3"> {{ $member->last_name }} </div>
                <div class="col-md-3"> {{ $member->email }} </div>
            </div>
        @endforeach

        <div class="mt-2">
            <button class="primary sync-processwire"> Sync with Processwire </button>
        </div>

    </div>
</div>

<script>

    $(function () {
        $('.sync-processwire').on('click', function(event) {
            if (confirm('Are you sure you want to sync? This process will take a while')) {
                let obj = $(this);
                obj.text('Syncing ... ')
                $.post("members/sync",
                    {
                        "_token": "{{ csrf_token() }}",
                    },
                    function(data, status) {
                        obj.text(' Sync with Processwire ');
                        alert('Completed');
                    });
            }
        })
    })

</script>

@endsection
