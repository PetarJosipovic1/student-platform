const subjectSelect = document.getElementById("subject_id");
const form = document.getElementById("examForm");
const msg = document.getElementById("msg");

const SUBJECTS_LIST_URL = "../php/subjects_list.php";
const EXAM_REGISTER_URL = "../php/exam-register.php";

function showMsg(text, ok) {
  if (!msg) return;
  msg.style.display = "block";
  msg.textContent = text;
  msg.style.color = ok ? "#b6ffd1" : "#ffb4b4";
}

// Ako nije na toj stranici, ne radi ništa
if (subjectSelect) {
  fetch(SUBJECTS_LIST_URL)
    .then((res) => res.json())
    .then((list) => {
      subjectSelect.innerHTML = '<option value="">Izaberi predmet</option>';

      if (!Array.isArray(list) || list.length === 0) {
        subjectSelect.innerHTML = '<option value="">Nema predmeta</option>';
        return;
      }

      list.forEach((s) => {
        const opt = document.createElement("option");
        opt.value = s.id;
        opt.textContent = s.name;
        subjectSelect.appendChild(opt);
      });
    })
    .catch(() => {
      subjectSelect.innerHTML = '<option value="">Greška pri učitavanju</option>';
    });
}

if (form) {
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const fd = new FormData(form);

    fetch(EXAM_REGISTER_URL, {
      method: "POST",
      body: fd,
    })
      .then((res) => res.json())
      .then((r) => {
        showMsg(r.message || "Završeno.", !!r.ok);
        if (r.ok) form.reset();
      })
      .catch(() => showMsg("Greška pri slanju zahteva.", false));
  });
}
