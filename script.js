// Script for navigation bar
const bar = document.getElementById("bar");
const close = document.getElementById("close");
const nav = document.getElementById("navbar");

if (bar) {
  bar.addEventListener("click", () => {
    nav.classList.add("active");
  });
}

if (close) {
  close.addEventListener("click", () => {
    nav.classList.remove("active");
  });
}

function validateLoginForm() {
  const user = document.getElementById('user');
  const pass = document.getElementById('pass');
  if (!user || !pass) return true;
  if (user.value.trim() === '' || pass.value.trim() === '') {
    alert('Please enter both username and password.');
    return false;
  }
  return true;
}

function validateSignupForm() {
  const fn = document.getElementById('fn');
  const ln = document.getElementById('ln');
  const user = document.getElementById('user');
  const email = document.getElementById('email');
  const pass = document.getElementById('pass');
  const cpass = document.getElementById('cpass');
  const cnic = document.getElementById('cnic');
  const dob = document.getElementById('dob');
  const contact = document.getElementById('contact');
  const gender = document.getElementById('gen');

  if (!fn.value.trim() || !ln.value.trim() || !user.value.trim() || !email.value.trim() || !pass.value || !cpass.value || !cnic.value || !dob.value || !contact.value || gender.value === 'S') {
    alert('Please fill in all required signup fields.');
    return false;
  }
  if (pass.value.length < 8) {
    alert('Password must be at least 8 characters.');
    return false;
  }
  if (pass.value !== cpass.value) {
    alert('Passwords do not match.');
    return false;
  }
  if (!/^[0-9]{13}$/.test(cnic.value)) {
    alert('CNIC must be 13 digits.');
    return false;
  }
  if (!/^[0-9]{11}$/.test(contact.value)) {
    alert('Contact number must be 11 digits.');
    return false;
  }
  return true;
}

function validateContactForm() {
  const name = document.getElementById('contact-name');
  const email = document.getElementById('contact-email');
  const subject = document.getElementById('contact-subject');
  const message = document.getElementById('contact-message');
  if (!name.value.trim() || !email.value.trim() || !subject.value.trim() || !message.value.trim()) {
    alert('Please fill all contact form fields.');
    return false;
  }
  if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value)) {
    alert('Please enter a valid email address.');
    return false;
  }
  return true;
}

// Slider functionality
document.addEventListener('DOMContentLoaded', function() {
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  const container = document.querySelector('.slider-container');

  if (slides.length > 0 && container) {
    function showSlide(index) {
      container.style.transform = `translateX(-${index * 100}%)`;
    }

    function changeSlide(direction) {
      currentSlide += direction;
      if (currentSlide < 0) {
        currentSlide = slides.length - 1;
      } else if (currentSlide >= slides.length) {
        currentSlide = 0;
      }
      showSlide(currentSlide);
    }

    // Auto slide
    setInterval(() => {
      changeSlide(1);
    }, 5000);

    // Initialize first slide
    showSlide(0);

    // Attach button events
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        console.log('Prev clicked');
        changeSlide(-1);
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        console.log('Next clicked');
        changeSlide(1);
      });
    }
  }
});

