document.addEventListener("DOMContentLoaded", () => {
    fetch("../php/dashboard.php")
        .then(response => response.json())
        .then(data => {
            if (!data.ok) {
                alert("Niste prijavljeni!");
                window.location.href = "index.html";
                return;
            }

            document.getElementById("studentName").textContent = data.name;
            document.getElementById("examCount").textContent = data.exams;

            const list = document.getElementById("upcomingExams");
            list.innerHTML = "";

            data.upcoming.forEach(exam => {
                const li = document.createElement("li");
                li.textContent = exam;
                list.appendChild(li);
            });
        })
        .catch(err => {
            console.error(err);
            alert("Greška pri učitavanju dashboarda");
        });
});
