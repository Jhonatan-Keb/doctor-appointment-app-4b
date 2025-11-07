import './bootstrap';
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
  const ok = document.querySelector('meta[name="flash-success"]');
  if (ok) {
    Swal.fire({
      title: ok.content,
      icon: 'success',
      timer: 1800,
      showConfirmButton: false
    });
  }

  const err = document.querySelector('meta[name="flash-error"]');
  if (err) {
    Swal.fire({
      title: err.content,
      icon: 'error'
    });
  }
});
