<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/register.css?v=2">
<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container">
	<div class="register-box">
		<h2>Login</h2>
		<form name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/login">
		<?php require APPROOT . '/views/components/auth_message.php'; ?>

			<input type="email" name="email" placeholder="Email Address" required />

			<input type="password" id="passwordInput" name="password" placeholder="Password" autocomplete="new-password" required  />
			
			<input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctor_id) ?>" />


			<div class="show-password">
				<input type="checkbox" id="togglePassword" />
				<label for="togglePassword">Show Password</label>
			</div>
			<div class="separator">
    <span>Or</span>
</div>
			<!-- Google Sign-In Button -->
<a href="<?php echo URLROOT; ?>/auth/oauthLogin" class="google-btn">
  <img class="google-icon" src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo">
  <span class="btn-text">Sign in with Google</span>
</a><br><br>

<style>
	/* Separator styling */
.separator {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 20px 0;
    color: #666;
    font-weight: 500;
}

.separator::before,
.separator::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #dadce0;
}

.separator::before {
    margin-right: 10px;
}

.separator::after {
    margin-left: 10px;
}

.google-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    color: #000;
    border: 1px solid #dadce0;
    border-radius: 4px;
    height: 50px;
    padding: 0 16px;
    font-family: 'Roboto', sans-serif;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    transition: box-shadow 0.2s, background-color 0.2s;
}

.google-btn:hover {
    background-color: #f7f7f7;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.google-icon {
    width: 18px;
    height: 18px;
    margin-right: 12px;
}
</style>

 <!-- Google reCAPTCHA -->
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_V2_SITEKEY;?>"></div>
            </div><br>
			<button type="submit">Login</button>

			Do not have an account! 
			<label class="alreadyRegi">
				<a href="<?php echo URLROOT;?>/pages/register">Register</a>
			</label>
		</form>
	</div>
</div>


<script>
  const toggle = document.getElementById('togglePassword');
  const passwordFields = document.querySelectorAll('input[type="password"]');

  toggle.addEventListener('change', function () {
    passwordFields.forEach(input => {
      input.type = this.checked ? 'text' : 'password';
    });
  });
</script>
<!-- Include Google reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>