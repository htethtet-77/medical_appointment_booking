<?php require_once APPROOT . '/views/inc/header.php'; ?>

<div class="container">
	<div class="register-box">
		<h2>Login</h2>
		<form name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/login">
		<?php require APPROOT . '/views/components/auth_message.php'; ?>

			<input type="email" name="email" placeholder="Email Address" required />

			<input type="password" id="passwordInput" name="password" placeholder="Password" required />

			<div class="show-password">
				<input type="checkbox" id="togglePassword" />
				<label for="togglePassword">Show Password</label>
			</div>

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
