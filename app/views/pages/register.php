<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/register.css">
<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


<div class="container">
	<div class="register-box">
		<h2>Register</h2>
		<?php require APPROOT . '/views/components/auth_message.php'; ?>
		<form name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/register">

			<input type="text" name="name" id="name" placeholder="Name" required />
			<p class="text-danger ml-4">
				<?php
				if(isset($data['name-err']))
				echo $data['name-err'];
				?>
			</p>

			<input type="email" name="email" id="email" placeholder="Email Address" required />
			<p class="text-danger ml-4">
				<?php
				if(isset($data['email-err']))
				echo $data['email-err'];
				?>
			</p>
			

			<!-- <input type="date" name="dob" required /> -->

			<div class="gender-selection">
				<label>Gender :</label>
				<label><input type="radio" name="gender" value="male" required /> Male</label>
				<label><input type="radio" name="gender" value="female" required /> Female</label>
			</div>

			<input type="tel" name="phone" id="phone" placeholder="Mobile Number" required />
			<p class="text-danger ml-4">
				<?php
				if(isset($data['phone-err']))
				echo $data['phone-err'];
				?>
			</p>


			<!-- <input type="password" id="passwordInput" name="password" placeholder="Password" required /> -->
		
			<input type="password" id="passwordInput" name="password" placeholder="Password" required />
			<p class="text-danger ml-4">
				<?php
				if(isset($data['password-err']))
				echo $data['password-err'];
				?>
			</p>

			<div class="show-password">
  				<input type="checkbox" id="togglePassword" />
  				<label for="togglePassword">Show Password</label>
			</div>

			<button type="submit"><a href="<?php echo URLROOT;?>/auth/register"></a>Create Account</button>

			<label class="alreadyRegi">Already registered? <a href="<?php echo URLROOT;?>/pages/login">Login</a></label>
		</form>
	</div>
</div>


<!-- reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_V3_SITEKEY; ?>"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo RECAPTCHA_V3_SITEKEY; ?>', {action: 'register'}).then(function(token) {
        let form = document.querySelector('form[name="contactForm"]');
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'g-recaptcha-response';
        input.value = token;
        form.appendChild(input);
    });
});
	//show password
  const toggle = document.getElementById('togglePassword');
  const passwordFields = document.querySelectorAll('input[type="password"]');

  toggle.addEventListener('change', function () {
    passwordFields.forEach(input => {
      input.type = this.checked ? 'text' : 'password';
    });
  });

</script>
