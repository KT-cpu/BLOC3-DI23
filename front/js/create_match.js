document.addEventListener("DOMContentLoaded", function () {
    const homeTeamSelect = document.getElementById("home_team");
    const visitorTeamSelect = document.getElementById("visitor_team");
    const matchDateInput = document.getElementById("match_date");
    const mainOfficialSelect = document.getElementById("main_official");
    const leftLinesmanSelect = document.getElementById("left_linesman");
    const rightLinesmanSelect = document.getElementById("right_linesman");

    const homeTeamRecap = document.getElementById("home_team_recap");
    const visitorTeamRecap = document.getElementById("visitor_team_recap");
    const matchDateRecap = document.getElementById("match_date_recap");
    const matchHoursRecap = document.getElementById("match_hours_recap");
    const mainOfficialRecap = document.getElementById("main_official_recap");
    const leftLinesmanRecap = document.getElementById("left_linesman_recap");
    const rightLinesmanRecap = document.getElementById("right_linesman_recap");

    const matchCity = document.getElementById("match_city_recap");

    homeTeamSelect.addEventListener("change", function () {
        homeTeamRecap.textContent =
            homeTeamSelect.options[homeTeamSelect.selectedIndex].text;
        for (let option of visitorTeamSelect.options) {
            option.disabled =
                option.value === homeTeamSelect.value || option.value === "";
        }
        for (club of clubsWithStadium) {
            console.log(homeTeamSelect.value);
            if (club.club_name === homeTeamSelect.value) {
                matchCity.textContent = capitalizeEachWord(club.stadium_name);
            }
        }
    });

    visitorTeamSelect.addEventListener("change", function () {
        visitorTeamRecap.textContent =
            visitorTeamSelect.options[visitorTeamSelect.selectedIndex].text;
        for (let option of homeTeamSelect.options) {
            option.disabled =
                option.value === visitorTeamSelect.value || option.value === "";
        }
    });

    matchDateInput.addEventListener("change", function () {
        const date = new Date(matchDateInput.value);
        const options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        matchDateRecap.textContent = date.toLocaleDateString("fr-FR", options);
        matchHoursRecap.textContent = date.toLocaleTimeString("fr-FR", {
            hour: "2-digit",
            minute: "2-digit",
        });
    });

    // Bloquer la saisie clavier
    matchDateInput.addEventListener("keydown", function (e) {
        e.preventDefault();
    });

    mainOfficialSelect.addEventListener("change", function () {
        mainOfficialRecap.textContent =
            mainOfficialSelect.options[mainOfficialSelect.selectedIndex].text;
        for (let option of [leftLinesmanSelect, rightLinesmanSelect]) {
            option.options[mainOfficialSelect.selectedIndex].disabled = true;
        }
    });

    leftLinesmanSelect.addEventListener("change", function () {
        leftLinesmanRecap.textContent =
            leftLinesmanSelect.options[leftLinesmanSelect.selectedIndex].text;
        for (let option of [mainOfficialSelect, rightLinesmanSelect]) {
            option.options[leftLinesmanSelect.selectedIndex].disabled = true;
        }
    });

    rightLinesmanSelect.addEventListener("change", function () {
        rightLinesmanRecap.textContent =
            rightLinesmanSelect.options[rightLinesmanSelect.selectedIndex].text;
        for (let option of [mainOfficialSelect, leftLinesmanSelect]) {
            option.options[rightLinesmanSelect.selectedIndex].disabled = true;
        }
    });

    const matchForm = document.getElementById("match_form");

    matchForm.addEventListener("submit", function (e) {
        //e.preventDefault(); // Empêche le rechargement de la page

        // Créez un objet FormData pour récupérer facilement toutes les valeurs du formulaire
        const formData = new FormData(matchForm);

        // Utilisez formData pour accéder aux valeurs du formulaire
        // Exemple : formData.get('home_team')
        console.log("Équipe domicile:", formData.get("home_team"));
        console.log("Équipe extérieure:", formData.get("away_team"));
        console.log("Date et heure du match:", formData.get("match-time"));
        console.log("Arbitre principal:", formData.get("main-official"));
        console.log("Arbitre de touche gauche:", formData.get("left-linesman"));
        console.log("Arbitre de touche droit:", formData.get("right-linesman"));

        // Ou, pour afficher toutes les valeurs d'un coup :
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
    });

    function capitalizeEachWord(str) {
        return str.replace(/\b\w/g, (char) => char.toUpperCase());
    }
});
