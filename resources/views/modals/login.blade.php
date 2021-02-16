<div class="modal" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">

        <div class="card login-card">
          <div class="row no-gutters">
            <div class="col-md-5">
              <img src="/assets/images/dna_spiral.jpg" alt="login"   style="width:100%; height: 500px; object-fit: cover;">
            </div>
            <div class="col-md-7">
              <button type="button" class="close mr-4 mt-4" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <div class="card-body pt-0">
                <div class="brand-wrapper">
                  <img src="/assets/images/clingen_logo.png" alt="logo" style="height:140px">
                </div>
                <p class="login-card-description"><b>My Account</b></p>
                <form action="/login" method="POST" id="login-form">
                  @csrf
                  <input type="hidden" id="login-context-value" name="context" value="">
                    <div class="form-group">
                      <label for="email" class="sr-only">Email</label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                    </div>
                    <div class="form-group mb-4">
                      <label for="password" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group row">
                      <div class="col-md-6">
                        <button type="submit" class="btn login-btn mb-4">Login</button>
                      </div>
                      <div class="col-md-6">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                      </div>
                  </div>

                  </form>
                  <a href="#!" class="forgot-password-link">Forgot password?</a>
                  <p class="login-card-footer-text mb-0">Don't have an account? <a href="#!" class="text-reset action-login-register"><i>Register here</i></a></p>
                  <!--<nav class="login-card-footer-nav">
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