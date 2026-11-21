document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('passwordModal');
  const btnOpen = document.getElementById('btnOpenPasswordModal');
  const btnClose = document.getElementById('btnClosePasswordModal');
  const btnCancel = document.getElementById('btnCancelPassword');
  const profileForm = document.getElementById('profileForm');
  const passwordForm = document.getElementById('passwordForm');
  const toast = document.getElementById('profileToast');
  const toastMessage = document.getElementById('toastMessage');

  function showToast(msg) {
    if (!toast) return;
    toastMessage.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  if (btnOpen && modal) {
    btnOpen.addEventListener('click', () => {
      modal.classList.add('active');
    });
  }

  function closeModal() {
    if (modal) {
      modal.classList.remove('active');
      if (passwordForm) passwordForm.reset();
    }
  }

  if (btnClose) btnClose.addEventListener('click', closeModal);
  if (btnCancel) btnCancel.addEventListener('click', closeModal);

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });
  }

  // Toggle password visibility
  document.querySelectorAll('.js-toggle-password').forEach((btn) => {
    btn.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const iconEye = this.querySelector('.icon-eye');
      const iconEyeOff = this.querySelector('.icon-eye-off');

      if (input) {
        if (input.type === 'password') {
          input.type = 'text';
          if (iconEye) iconEye.style.display = 'none';
          if (iconEyeOff) iconEyeOff.style.display = 'block';
        } else {
          input.type = 'password';
          if (iconEye) iconEye.style.display = 'block';
          if (iconEyeOff) iconEyeOff.style.display = 'none';
        }
      }
    });
  });

  if (profileForm) {
    profileForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showToast('Profil berhasil disimpan!');
    });
  }

  if (passwordForm) {
    passwordForm.addEventListener('submit', function (e) {
      e.preventDefault();
      closeModal();
      showToast('Kata sandi berhasil diperbarui!');
    });
  }
});
