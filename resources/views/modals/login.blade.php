 <div class="modal" id="modalLogin" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modalLoginTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
        <div class="card login-card">
          <div class="row no-gutters">
            <div class="col-md-4 hidden-sm hidden-xs">
              <img src="/assets/images/dna_spiral.jpg" alt="login"   style="width:100%; height: 520px; object-fit: cover;">
            </div>
            <div class="col-md-8">
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
                    <div class="form-group row">
                      <label for="email" class="sr-only">Email</label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                    </div>
                    <div class="form-group mb-0 row">
                      <label for="password" class="sr-only">Password</label>
                      <input type="password" name="password" id="password" class="form-control mb-1" placeholder="Password">
                    </div>
                    <div class="form-group row">
                      <div class="col-md-6">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="remember_me" id="remember" value="1" {{ old('remember_me') ? 'checked' : '' }}>
                          <label class="form-check-label" for="remember_me">
                            {{ __('Remember Me') }}
                          </label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <a href="#!" class="forgot-password-link float-right action-login-forgot">Forgot password?</a>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <button type="submit" class="btn login-btn mb-4">Login</button>
                      </div>
                      <div class="col-md-9">
                        <!--<div class="d-flex justify-content-center social-buttons mt-1">
                          or login with
                          <button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top" title="Twitter">
                            <i class="fab fa-twitter fa-2x"></i>
                          </button>
                          <button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top" title="Facebook">
                            <i class="fab fa-facebook fa-2x"></i>
                          </button>
                          <button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top" title="Linkedin">
                            <i class="fab fa-linkedin fa-2x"></i>
                          </button>
                          <button type="button" class="btn btn-secondary btn-round" data-toggle="tooltip" data-placement="top" title="google">
                            <i class="fab fa-google-plus-square fa-2x"></i>
                          </button>
                        </div>-->
                      </div>
                    </div>
                  </form>
                  <p class="login-card-footer-text mb-0">Don't have a ClinGen account?  <a href="#!" class="text-reset action-login-register"><strong><i>Register Here</i></strong></a></p>
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