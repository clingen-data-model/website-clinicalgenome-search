 <div class="modal" id="modalForgot" tabindex="-1" role="dialog" aria-labelledby="modalForgotTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
        <div class="card login-card">
          <div class="row no-gutters">
            <div class="col-md-4">
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
                <p class="login-card-description"><b>Forgot Password</b></p>
                <form action="/forgot" method="POST" id="forgot-form">
                  @csrf
                    <div class="form-group row">
                      <label for="email" class="sr-only">Email</label>
                      <input type="email" name="email" id="email" class="form-control" placeholder="Email address">
                    </div>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <button type="submit" class="btn login-btn mb-4">Send Reset Link</button>
                      </div>
                      <div class="col-md-9">

                      </div>
                    </div>
                  </form>
                  <p class="login-card-footer-text mb-0">Return to <a href="#!" class="text-reset action-forgot-login"><i>Login Page here</i></a></p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>     