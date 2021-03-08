<div class="modal" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="modalRegisterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">

        <div class="card login-card">
          <div class="row no-gutters">
            <div class="col-md-4">
              <img src="/assets/images/dna_spiral.jpg" alt="register"   style="width:100%; height: 160px; object-fit: cover;">
              <div class="card-body">
                Here is where we'll add text about password rules, etc.
              </div>
            </div>
            <div class="col-md-8">
              <button type="button" class="close mr-4 mt-4" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <div class="card-body pt-0">
                <div class="brand-wrapper">
                  <img src="/assets/images/clingen_logo.png" alt="logo" style="height:140px">
                </div>
                <p class="login-card-description"><b>Register</b></p>
                <form action="/register" method="POST" id="register-form">
                  @csrf
                  <input type="hidden" id="register-context-value" name="context" value="">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="firstname" class="sr-only">First Name</label>
                        <input type="text" name="firstname" class="form-control" placeholder="First Name">
                      </div>
                      <div class="col-md-6">
                        <label for="lastname" class="sr-only">Last Name</label>
                        <input type="text" name="lastname" class="form-control" placeholder="Last Name">
                      </div>
                  </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="email" class="sr-only">Email</label>
                          <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                        </div>
                        <div class="col-md-6">
                          <label for="organization" class="sr-only">Organization</label>
                          <input type="text" name="organization" class="form-control" placeholder="Organization">
                        </div>
                      </div>
                    </div>
                    <div class="form-group mb-4">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="password" class="sr-only">Password</label>
                          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="col-md-6">
                          <label for="password" class="sr-only">Confirm Password</label>
                          <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-block login-btn mb-4">Register</button>
                    
                  </form>
                  <p class="login-card-footer-text">Already have an account? <a href="#!" class="text-reset action-register-login"><i>Login here</i></a></p>
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