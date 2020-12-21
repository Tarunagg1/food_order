<?php
include('header.php');
if(isset($_SESSION['USER_ID'])){
    redirect('shop');
}
if(isset($_GET['referal_code']) && $_GET['referal_code'] != ''){
    $_SESSION['FROM_REFERRAL_CODE'] = get_safe_value($_GET['referal_code']);
}
?>
        <div class="login-register-area pt-95 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                        <div class="login-register-wrapper">
                            <div class="login-register-tab-list nav">
                                <a class="active" data-toggle="tab" href="#lg1">
                                    <h4> login </h4>
                                </a>
                                <a data-toggle="tab" href="#lg2">
                                    <h4> register </h4>
                                </a>
                            </div>
                            <div class="tab-content">
                                <div id="lg1" class="tab-pane active">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                            <h5 class="loginerr" id="loginerr"></h5>
                                            <form id="loginform">
                                                <input type="email" name="lemail" placeholder="Email" required>
                                                <input type="password" name="lpassword-password" placeholder="Password" required>
                                                <input type="hidden" value="login" name="what">
                                                <input type="hidden" id="ischeckout" value="no" name="ischeckout">
                                                <div class="button-box">
                                                    <div class="login-toggle-btn">
                                                        <input type="checkbox">
                                                        <label>Remember me</label>
                                                        <a href="forgotpass">Forgot Password?</a>
                                                    </div>
                                                    <button id="loginbtn" type="submit"><span>Login</span></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="lg2" class="tab-pane">
                                    <div class="login-form-container">
                                        <div class="login-register-form">
                                        <div class="errmsg" id="form_msg"></div>
                                            <form id="register" method="post">
                                                <input type="text" name="user-name" placeholder="Username" required>
                                                <input name="user-email" placeholder="Email" type="email" required>
                                                <div class="errmsg" id="email_err"></div>
                                                <input type="number" name="user-number" placeholder="Number" required>
                                                <input type="password" name="user-password" placeholder="Password" required>
                                                <input type="hidden" value="register" name="what">
                                                <div class="button-box">
                                                    <button id="registerbtn" type="submit"><span>Register</span></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    include('footer.php');
?>