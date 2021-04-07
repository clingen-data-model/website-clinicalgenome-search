 <div class="modal" id="modalRegister" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalRegisterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
        <div class="card login-card">
          <div class="row no-gutters">
            <div class="col-md-4 hidden-sm hidden-xs">
              <img src="/assets/images/dna_spiral.jpg" alt="login"   style="width:100%; height: 660px; object-fit: cover;">
            </div>
            <div class="col-md-8">
              <button type="button" class="close mr-4 mt-4" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <div class="card-body pt-0">
                <div class="brand-wrapper">
                  <img src="/assets/images/clingen_logo.png" alt="logo" style="height:140px">
                </div>
                <p class="login-card-description"><b>Create Acount</b></p>
                <form action="/register" method="POST" id="register-form" class="form-horizontal">
                  @csrf
                  <input type="hidden" id="register-context-value" name="context" value="">
                  <div class="form-group row">
                    <div class="col-sm-6">
                      <label for="firstname" class="sr-only">First Name</label>
                      <input type="text" name="firstname" class="form-control" placeholder="First Name">
                    </div>
                    <div class="col-sm-6">
                      <label for="lastname" class="sr-only">Last Name</label>
                      <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <label for="organization" class="sr-only">Organization (Optional)</label>
                      <input type="text" name="organization" class="form-control" placeholder="Organization (optional)">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <label for="email" class="sr-only">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6">
                      <label for="password" class="sr-only">Password</label>
                      <input type="password" id="register-password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="col-sm-6">
                      <label for="password_confirmation" class="sr-only">Confirm Password</label>
                      <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                    </div>
                  </div>
                    <div class="form-group row">
                      <div class="col-md-6">
                        <button type="submit" class="btn login-btn mb-4">Register</button>
                      </div>
                    </div>
                  </form>
                  <p class="login-card-footer-text mb-0">Already have an account? <a href="#!" class="text-reset action-register-login"><strong><i>Login Here</i></strong></a></p>
                  <!--<nav class="register-card-footer-nav">
                    <a href="#!">Terms of use.</a>
                    <a href="#!">Privacy policy</a>
                  </nav>-->
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>