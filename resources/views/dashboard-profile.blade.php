@extends('layouts.app')

@section('content-heading')

    @include('dashboard.includes.header', ['active' => 'profile'])

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-sm-12">
                    <h3>Manage Your Profile</h3>
                </div>

            </div>

            <div class="row mb-3">
                <div class="col-lg-8">
                    <form class="form-horizontal" method="POST" action="/dashboard/profile">
                        @csrf
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">ClinGen ID</label>
                            <div class="col-sm-3">
                                pweller@member.clinicalgenome.org
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="firstname" value="{{ $user->firstname ?? '' }}" placeholder="First Name...">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="lastname" value="{{ $user->lastname ?? '' }}" placeholder="Last name...">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="credentials" value="{{ $user->credentials ?? '' }}" placeholder="Credentials">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Organization</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="organization" value="{{ $user->organization ?? '' }}" placeholder="Organization or Institution">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="password_confirm" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.col-lg-8 -->

                <div class="col-lg-offset-1 col-lg-3">
                    <div class="form-group">
                        Profile Picture
                    </div>
                    <div class="form-group">
                        <image id="profileImage" src="/images/profile.png" />
                        <!--<input id="imageUpload" type="file" name="profile_photo" placeholder="Photo" required="" capture>-->
                    </div>
                </div><!-- /.col-lg-4 -->
            </div>

            <hr />

            <div class="row mb-3">
                <div class="col-sm-6">
                    <h3>Display Settings</h3>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-12">
                    <form class="form-horizontal" method="POST" action="/dashboard/profile">
                        @csrf

                        <div class="form-group">
                            <div class="col-sm-8">
                                <h5>List Size</h5>
                                Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt.
                            </div>
                            <div class="col-sm-4 border-left" style="border-left-width: 10px !important">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="display_list" value="25" {{ $user->preferences['display_list'] == '25' ? 'checked' : '' }}>
                                        25
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="display_list" value="50" {{ $user->preferences['display_list'] == '50' ? 'checked' : '' }}>
                                        50
                                    </label>
                                </div>
                                <div class="radio">
                                        <label>
                                            <input type="radio" name="display_list" value="100" {{ $user->preferences['display_list'] == '100' ? 'checked' : '' }}>
                                            100
                                        </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="display_list" value="250" {{ $user->preferences['display_list'] == '250' ? 'checked' : '' }}>
                                        250
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
