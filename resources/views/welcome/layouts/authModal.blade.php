<style>
    .RegisterModal{
        display:none;
    }
</style>
<!-- Modal -->
<div class="modal fade logibPop" id="AuthModal" tabindex="-1" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                    
                    <div class="loginPopUp loginModal">
                        <div class="msgBoxLogin"></div>
                        <form class="loginAuth">
                            <label>Email/Mobile Number *</label>
                            <input class="form-control" name="email_phone" type="text" placeholder="Email / Mobile Number" required="" />
                            <label>Password *</label>
                            <input class="form-control" name="password" type="password" placeholder="Password" required="" />
                            <input type="checkbox" value="lsRememberMe" id="rememberMe"> <label for="rememberMe">Remember me</label>
                            <button class="loginSubmit">Login</button>
                        </form>
                        <h3>OR</h3>
                        <button class="googleBtn"><i class="fa fa-google"></i> Continue With Google</button>
                        <button class="fbBtn"><i class="fa fa-facebook"></i> Continue With Facebook</button>
                        <button class="emailBtn registerBtn"><i class="fa fa-user"></i> Register Account</button>
                        <p>By signing up for an account you agree to our</p>
                        <a href="#" class="trmComLink">Terms & Conditions</a>
                    </div>
                    <div class="loginPopUp RegisterModal">
                        <div class="msgBoxRegister"></div>
                        <form class="registerAuth">
                            <label>Your Name *</label>
                            <input class="form-control" name="name" type="text" placeholder="Enter your name" required="" />
                            <!--<label>Company Name *</label>-->
                            <!--<input class="form-control" name="company_name" type="text" placeholder="Enter Company name" required="" />-->
                            <label>Mobile *</label>
                            <input class="form-control" name="mobile" type="text" placeholder="Enter Mobile Number" required="" />
                            <label>Email</label>
                            <input class="form-control" name="email" type="email" placeholder="Email Address" />
                            <label>Password *</label>
                            <input class="form-control" name="password" type="password" placeholder="Password" required="" />
                            <small style="color: #666;">At least 6 characters.</small>
                            <p style="color: black;">
                            <input type="checkbox" name="terms" required> I agree to the <a href="/terms">Terms & Conditions</a> and <a href="/privacy-policy">Privacy Policy</a>.
                            </p>
                            <button class="loginSubmit">Register</button>
                        </form>
                        <h3>OR</h3>
                        <button class="emailBtn loginBtn"><i class="fa fa-user"></i> Login Account</button>
                        <p>I have already account click login account</p>
                    </div>
                 
            </div>
        </div>
    </div>
</div>