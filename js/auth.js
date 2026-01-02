function setError(inputEl, message) {
  const field = inputEl.closest(".field") || inputEl.parentElement;

  const old = field.querySelector(".js-error");
  if (old) old.remove();

  const p = document.createElement("p");
  p.className = "helper js-error";
  p.textContent = message;

  field.appendChild(p);
}

function clearError(inputEl) {
  const field = inputEl.closest(".field") || inputEl.parentElement;
  const old = field.querySelector(".js-error");
  if (old) old.remove();
}

function validateRegister(form, e) {
  const fullName = form.querySelector('input[name="full_name"]');
  const email = form.querySelector('input[name="email"]');
  const pass = form.querySelector('input[name="pass"]');

  let ok = true;

  [fullName, email, pass].forEach(clearError);

  if (!fullName.value.trim()) {
    setError(fullName, "Unesi ime i prezime.");
    ok = false;
  }

  if (!email.value.trim()) {
    setError(email, "Unesi email.");
    ok = false;
  }

  const pval = pass.value.trim();
  if (!pval) {
    setError(pass, "Unesi lozinku.");
    ok = false;
  } else if (pval.length < 8) {
    setError(pass, "Lozinka mora imati najmanje 8 karaktera.");
    ok = false;
  }

  if (!ok) e.preventDefault();
}

function validateLogin(form, e) {
  const email = form.querySelector('input[name="email"]');
  const pass = form.querySelector('input[name="pass"]');

  let ok = true;

  [email, pass].forEach(clearError);

  if (!email.value.trim()) {
    setError(email, "Unesi email.");
    ok = false;
  }

  if (!pass.value.trim()) {
    setError(pass, "Unesi lozinku.");
    ok = false;
  }

  if (!ok) e.preventDefault();
}

// Register (po action-u ili po prisustvu polja)
const registerForm =
  document.querySelector('form[action$="register.php"]') ||
  (document.querySelector('input[name="full_name"]') ? document.querySelector("form") : null);

if (registerForm) {
  registerForm.addEventListener("submit", (e) => validateRegister(registerForm, e));
}

// Login (po action-u ili po poljima)
const loginForm =
  document.querySelector('form[action$="login.php"]') ||
  (document.querySelector('input[name="email"]') && document.querySelector('input[name="pass"]') ? document.querySelector("form") : null);

if (loginForm && !registerForm) {
  loginForm.addEventListener("submit", (e) => validateLogin(loginForm, e));
}
