@extends('layouts.app')

@section('content-heading')
<div class="row mb-1 mt-1">
	<div class="col-md-5">
			<table class="mt-3 mb-4">
        <tr>
          <td class="valign-top"></td>
          <td class="pl-2">
						<h1 class="h2 p-0 m-0">Your Dashboard</h1>
            <p>Welcome back USER!</p>
          </td>
        </tr>
      </table>

			</h1>
			{{-- <strong></strong> --}}

</div>

	<div class="col-md-7 text-right mt-2 hidden-sm  hidden-xs">
		  <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countCurations text-18px">XX</span><br />Total Genes<br />Followed</li>
            <li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px">XX</span><br />Followed Genes <br /> With Classifications</li>
			<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countEps text-18px">XX</span><br /> Genes Updated In <br />The Last 90 Days</li>
		</ul>

</div>

			</div>
			<ul class="nav nav-tabs mt-1" style="">

					<li class="" style="">
            <a href="{{ route('dashboard-index') }}" class="">
              Following
            </a>
          </li>
          <li class="active" style="">
                        <a href="{{ route('dashboard-preferences') }}" class="">
Notification Preferences</a>
          </li>
          <li class="" style="">
                        <a href="{{ route('dashboard-profile') }}" class="">
Manage Your Profile</a>
          </li>
		</ul>

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-sm-6">
                <h3>Notification Preferences</h3>
                    </div>

                <div class="col-sm-6 text-right mt-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Notifications Status:  On
                            </button>
                            <ul class="dropdown-menu">
                            <li><a href="#">Pause Notifications</a></li>
                            </ul>
                        </div>
                        </div>
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
                                <div class="col-sm-12">
                                    <h5>On Change Of Curation Activity</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-12">
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right pr-0">
                                            Frequency:
                                        </div>
                                        <div class="col-sm-10 pl-1">
                                    <ul class="list-inline">
                                        <li>
                                        <label class="text-normal">
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_DAILY) }}>
                                            Send Daily
                                        </label>
                                        </li>
                                        <li>
                                        <label class="text-normal">
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_WEEKLY) }}>
                                            Send Weekly
                                        </label>
                                        </li>
                                        <li>
                                        <label class="text-normal">
                                            <input type="radio" name="frequency" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('frequency', App\Notification::FREQUENCY_MONTHLY) }}>
                                            Send Monthly
                                        </label>
                                        </li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h5>First Curation</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-12">
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right pr-0">
                                            Send Notice:
                                        </div>
                                        <div class="col-sm-10">
                                    <ul class="list-inline">
                                        <li>
                                        <label class="text-normal">
                                            <input type="radio" name="first" value="{{ App\Notification::FREQUENCY_DAILY }}" {{ $notification->checked('first', App\Notification::FREQUENCY_DAILY) }}>
                                            Yes
                                        </label>
                                        </li>
                                        <li>
                                        <label class="text-normal">
                                            <input type="radio" name="first" value="{{ App\Notification::FREQUENCY_NONE }}" {{ $notification->checked('first', App\Notification::FREQUENCY_NONE) }}>
                                            No
                                        </label>
                                        </li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h5>Summary Follow Genes Report</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-12">
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right pr-0">
                                            Frequency:
                                        </div>
                                        <div class="col-sm-10">
                                    <ul class="list-inline">
                                            <li>
                                            <label class="text-normal">
                                                <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_WEEKLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_WEEKLY) }}>
                                                Send Weekly
                                            </label>
                                            </li>
                                            <li>
                                            <label class="text-normal">
                                                <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_MONTHLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_MONTHLY) }}>
                                                Send Monthly
                                            </label>
                                            <label class="text-normal">
                                                <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_QUARTERLY }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_QUARTERLY) }}>
                                                Send Quarterly
                                            </label>
                                            <label class="text-normal">
                                                <input type="radio" name="summary" value="{{ App\Notification::FREQUENCY_ANNUAL }}" {{ $notification->checked('summary', App\Notification::FREQUENCY_ANNUAL) }}>
                                                Send Annual
                                            </label>
                                        </li>
                                    </ul>
                                    </div>
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

                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             Option 2

                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <h5>Your Emails Addresses</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-12">
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right mt-1 pr-0">
                                            Account Email:
                                        </div>
                                        <div class="col-sm-10 pl-1">
                                            <input type="text" class="form-control" disabled value="something@email.com">
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-sm-2 text-right mt-1 pr-0">
                                            Additional Emails:
                                        </div>
                                        <div class="col-sm-10 pl-1">
                                            <input type="text" class="form-control" placeholder="Type in emails..." aria-label="...">
                                            <small>Add as many emails you want with a comma (,) between each.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>On Change Of Curation Activity</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                            Send Daily
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                            Send Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                                            Send Monthly
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>Gene Without Activity (?????)</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios1" value="option1" checked>
                                            Daily
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios2" value="option2">
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios3" value="option3" disabled>
                                            Monthly
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-8">
                                    <h5>Summary Follow Genes Report</h5>
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                                <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Send Weekly
                                        </label>
                                        </div>
                                        <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="" disabled>
                                            Send Monthly
                                        </label>
                                    </div>
                                        <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="" disabled>
                                            Send Quarterly
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

                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                             <br/>
                            Option 3

                            <form class="form-horizontal">
                            <div class="form-group mb-4 pb-4">
                                <label for="inputEmail3" class="col-sm-2 control-label">On Update</label>
                                <div class="col-sm-3 border-right" style="border-right-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                            Daily
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
                                            Monthly
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                            </div>
                            <div class="form-group mb-4 pb-4">
                                <label for="inputEmail3" class="col-sm-2 control-label">On Addition</label>
                                <div class="col-sm-3 border-right" style="border-right-width: 10px !important">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios1" value="option1" checked>
                                            Daily
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios2" value="option2">
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios1" id="optionsRadios3" value="option3" disabled>
                                            Monthly
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                            </div>
                            <div class="form-group mb-4 pb-4">
                                <label for="inputEmail3" class="col-sm-2 control-label">Summary</label>
                                <div class="col-sm-3 border-right" style="border-right-width: 10px !important">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="">
                                            Weekly
                                        </label>
                                        </div>
                                        <div class="checkbox disabled">
                                        <label>
                                            <input type="checkbox" value="" disabled>
                                            Monthly
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                            </form>
                    </div><!-- /.col-lg-6 -->
                    </div>

        </div>
    </div>
</div>
@endsection
