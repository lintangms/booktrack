document.addEventListener('DOMContentLoaded', () => {
  const toggleButtons = document.querySelectorAll('.js-toggle-password');

  toggleButtons.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);

      if (!input) return;

      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';

      const eyeIcon = btn.querySelector('.icon-eye');
      const eyeOffIcon = btn.querySelector('.icon-eye-off');

      if (eyeIcon && eyeOffIcon) {
        if (isPassword) {
          eyeIcon.style.display = 'none';
          eyeOffIcon.style.display = 'block';
        } else {
          eyeIcon.style.display = 'block';
          eyeOffIcon.style.display = 'none';
        }
      }
    });
  });

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', (e) => {
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('password_confirmation');

      if (password && confirmPassword && password.value !== confirmPassword.value) {
        e.preventDefault();
        alert('Konfirmasi kata sandi tidak cocok. Silakan periksa kembali!');
        confirmPassword.focus();
      }
    });
  }
});
