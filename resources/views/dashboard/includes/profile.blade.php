<div class="panel panel-default">
    <div class="panel-header">
        <img class="profile-background" height="200" src="/assets/images/profilebg2.jpg" width="100%">
        <!--<img class="avatar" src="/images/profile.png">-->
        <strong><span id="profile-name" class="avatar-name text-white">{{ $user->name }}</span></strong>
        <strong><span id="profile-credentials" class="avatar-title text-white">{{ $user->credentials }}</span></strong>
    </div>
    <div class="panel-body" style="background: #F7F9F9">
        <div class="text-muted"><strong>{{ $user->email }}</strong></div>
        <!--<small>Member since {{ $user->display_year }}</small>-->
        <hr>
        <h6 class="text-muted">Interests:</h6>
        @if (in_array('validity', $user->profile['interests']))
            <span id="profile-interest-validity" style="">
        @else
            <span id="profile-interest-validity" style="display:none">
        @endif
        <img class="ma-4" src="/images/clinicalValidity-on.png" heigth="40" width="40"></span>
        @if (in_array('dosage', $user->profile['interests']))
            <span id="profile-interest-dosage" style="">
        @else
            <span id="profile-interest-dosage" style="display:none">
        @endif
        <img class="ma-4" src="/images/dosageSensitivity-on.png" heigth="40" width="40"></span>
        @if (in_array('actionability', $user->profile['interests']))
            <span id="profile-interest-actionability" style="">
        @else
            <span id="profile-interest-actionability" style="display:none">
        @endif
        <img class="ma-4" src="/images/clinicalActionability-on.png" heigth="40" width="40"></span>
        <hr>
        <ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 float-right"><span class="text-18px action-edit-settings" data-toggle="tooltip" title="Edit Settings"><i class="fas fa-cog fa-lg pb-1"></i></span>
            </li>
        <ul>
    </div>
</div>