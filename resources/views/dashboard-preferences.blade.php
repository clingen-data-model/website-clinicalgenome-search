@extends('layouts.app')

@section('content-heading')

    @include('dashboard.includes.header', ['active' => 'preferences'])

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-sm-12">
                <h3>Notification Preferences</h3>
                    </div>

                
                <div class="row mb-3">
                    <div class="col-lg-12">


                        <form class="form-horizontal" method="POST" action="/dashboard/preferences">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h5>Outgoing Emails Addresses</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-12">
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right mt-1 pr-0">
                                            Primary Email:
                                        </div>
                                        <div class="col-sm-10 pl-1">
                                            <input type="text" class="form-control" name="primary_email" value="{{  $notification->primary['email'] }}">
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right mt-1 pr-0">
                                            Additional Emails:
                                        </div>
                                        <div class="col-sm-10 pl-1">
                                            <input type="text" class="form-control" placeholder="Type in emails..." name="secondary_email" value="{{  $notification->secondary['email'] }}">
                                            <small>Add as many emails you want with a comma (,) between each.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>Change Notices</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_DAILY) }}>
                                            Daily
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_WEEKLY) }}>
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_MONTHLY) }}>
                                            Monthly
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>First Curation</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="first" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('first', App\Notification::FREQUENCY_DAILY) }}>
                                            Notify
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="first" value="{{ App\Notification::FREQUENCY_NONE }}" {{ $notification->checked('first', App\Notification::FREQUENCY_NONE) }}>
                                            Do not notify
                                        </label>
                                        </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>Summary Report</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_WEEKLY) }}>
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_MONTHLY) }}>
                                            Monthly
                                        </label>
                                    </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_QUARTERLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_QUARTERLY) }}>
                                            Quarterly
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_ANNUAL }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_ANNUAL) }}>
                                            Annual
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        <hr />
                        <div class="form-group">
                            {{-- <div class="col-sm-offset-8 col-sm-2"> --}}
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary">Save Preferences</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.col-lg-6 -->
            </div>
        </div>
    </div>
</div>
@endsection
