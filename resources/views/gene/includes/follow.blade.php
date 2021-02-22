<div class="row collapse" id="follow-gene-id">
	<div class="col-md-6 pl-5 pr-2 mt-3 border-right">
		@if (!Auth::check())
			<p>For complete control over gene update notifications, ClinGen recommends you login or create an account.  If you select "Remember Me"
				during login, ClinGen will remember your login until you manually log out.
			</p>
			<div class="text-center">
				<button type="button" class="btn btn-outline-secondary action-login mt-2">
					Login or Register
				</button>
			</div>
		@endif
	</div>
	<div class="col-md-6 pr-5 pl-4 mt-3">
		<p>If you cannot login at this time, enter your email address and click on submit.  You will not have access to your dashboard, however, 
			ClinGen will save your requests pending confirmationo of your email address. </p>	
		<form id="follow_form" method="POST" action="" class="form-horizontal">
			@csrf
			<div class="input-group mt-3">
				<span class="input-group-addon">Email: </span>
				<input type="email" id="follow-gene-email" class="form-control" name="email" value="" placeholder="Enter your email address">
				<input type="hidden" name="gene" value="{{ $record->hgnc_id }}">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit">Submit</button>
				</span>
			</div>
		</form>
	</div>
	<div class="col-md-12 mt-3 border-bottom">
		<span class="float-right action-follow-cancel" >
		Cancel Login
		<i class="far fa-caret-square-up"></i></span>
	</div>
</div>