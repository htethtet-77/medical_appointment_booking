<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/register.css">
<link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container">
	<div class="register-box">
		<h2>Login</h2>
		<form name="contactForm" method="POST" action="<?php echo URLROOT; ?>/auth/login">
		<?php require APPROOT . '/views/components/auth_message.php'; ?>

			<input type="email" name="email" placeholder="Email Address" required />

			<input type="password" id="passwordInput" name="password" placeholder="Password" required />
			
			<input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctor_id) ?>" />


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
