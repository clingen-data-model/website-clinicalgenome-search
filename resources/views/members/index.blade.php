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
                <div class="col-md-2"> Button Here </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
