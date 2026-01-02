console.log("Student Platform loaded");

// Mali helper: automatski setuje trenutnu godinu u footer ako ima span#year
const yearEl = document.getElementById("year");
if (yearEl) yearEl.textContent = new Date().getFullYear();
