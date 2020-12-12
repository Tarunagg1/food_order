<?php
include('header.php');
?>
        
		<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Forgot Password </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="contact-area pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                            <h4 class="contact-title">Forgot password section</h4>
                        <div class="contact-message-wrapper">
                            <div class="contact-message">
                                <form id="forgot-form">
                                    <div class="row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-6">
                                            <h5 class="forgotpass" id="forgotpasserr"></h5>
                                            <div class="contact-form-style mb-20">
                                                <input name="email" placeholder="Email Address" type="email" required>
                                                <input name="what" value="forgot" type="hidden">
                                                <button class="submit btn-style" id="forgotbn" type="submit">Validate</button>
                                                <a href="index" class="btn btn-primary">Login</a>
                                            </div>
                                        </div>
            
                                        <div class="col-lg-12">
                                            <div class="contact-form-style">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
include('footer.php');
?>
