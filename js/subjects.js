const SUBJECTS_URL = "../php/subjects.php";

const table = document.getElementById("subjects-table");
if (!table) {
  // ako nismo na subjects stranici
  console.warn("subjects-table not found");
} else {
  fetch(SUBJECTS_URL)
    .then((res) => {
      if (!res.ok) throw new Error("HTTP error");
      return res.json();
    })
    .then((subjects) => {
      table.innerHTML = "";

      if (!Array.isArray(subjects) || subjects.length === 0) {
        table.innerHTML = `
          <tr>
            <td colspan="4">Nema predmeta.</td>
          </tr>`;
        return;
      }

      subjects.forEach((s) => {
        const tr = document.createElement("tr");

        const tdName = document.createElement("td");
        tdName.textContent = s.name;

        const tdProf = document.createElement("td");
        tdProf.textContent = s.professor;

        const tdEcts = document.createElement("td");
        tdEcts.textContent = s.ects;

        const tdSem = document.createElement("td");
        tdSem.textContent = s.semester;

        tr.appendChild(tdName);
        tr.appendChild(tdProf);
        tr.appendChild(tdEcts);
        tr.appendChild(tdSem);

        table.appendChild(tr);
      });
    })
    .catch(() => {
      table.innerHTML = `
        <tr>
          <td colspan="4">Greška pri učitavanju.</td>
        </tr>`;
    });
}
