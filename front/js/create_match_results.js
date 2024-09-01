const winnerSelect = document.getElementById("winner_select");
const winnerClubName = document.getElementById("winner_club_name");
winnerSelect.addEventListener("change", function () {
    winnerClubName.textContent = this.options[this.selectedIndex].text;
});

const matchDurationNumber = document.getElementById("match_duration_number");
const matchDurationSpan = document.getElementById("match_duration_span");
matchDurationNumber.addEventListener("input", function () {
    if (parseInt(this.value) < 0) {
        this.value = 0;
    }

    matchDurationSpan.textContent = parseInt(this.value);
});

// -------------------HOME EVENT-------------------
const eventTimeHomeClub = document.getElementById("event_time_home_club");
const eventTypeHomeClub = document.getElementById("event_type_home_club");
const homeButtonEvent = document.getElementById("home_button_event");
const playerSelectHomeClub = document.getElementById("player_select_home_club");
const homeGoalTypeLabel = document.getElementById("home_goal_type_label");
const homeGoalTypeSelect = document.getElementById("home_goal_type_select");
const homeGoalTypeSelectContainer = document.getElementById(
    "home_goal_type_select_container"
);
const substitutionPlayerSelectHomeClubContainer = document.getElementById(
    "substitution_player_select_home_club_container"
);
const substitutionPlayerSelectHomeClub = document.getElementById(
    "substitution_player_select_home_club"
);
const substitutionHomeClubInfo = document.getElementById(
    "substitution_home_club_info"
);
const cardHomeClubInfo = document.getElementById("card_home_club_info");
let homeEvent = {
    goal: [],
    yellow_card: [],
    red_card: [],
    substitution: [],
};

eventTimeHomeClub.addEventListener("input", function () {
    const matchDurationValue = parseInt(matchDurationSpan.textContent);
    if (!matchDurationValue) {
        alert("Please enter the match duration");
        this.value = 0;
    } else {
        if (parseInt(this.value) < 0) {
            this.value = 0;
        } else if (parseInt(this.value) > matchDurationValue) {
            alert("Event time cannot be greater than match duration");
            this.value = matchDurationValue;
        }
    }
});

function hideHomeGoalTypeSelect() {
    if (homeGoalTypeSelectContainer.style.display === "block") {
        homeGoalTypeSelectContainer.style.display = "none";
    }
}

function hideHomeSubstitutionPlayerSelect() {
    if (substitutionPlayerSelectHomeClubContainer.style.display === "block") {
        substitutionPlayerSelectHomeClubContainer.style.display = "none";
    }
}

eventTypeHomeClub.addEventListener("change", function () {
    switch (this.value) {
        case "goal":
            homeGoalTypeSelectContainer.style.display = "block";
            hideHomeSubstitutionPlayerSelect();
            break;
        case "yellow_card":
            hideHomeGoalTypeSelect();
            hideHomeSubstitutionPlayerSelect();
            break;
        case "red_card":
            hideHomeGoalTypeSelect();
            hideHomeSubstitutionPlayerSelect();
            break;
        case "substitution":
            substitutionPlayerSelectHomeClubContainer.style.display = "block";
            hideHomeGoalTypeSelect();
            break;
        default:
            alert("Please select an event type");
            break;
    }
});

homeButtonEvent.addEventListener("click", (e) => {
    //const matchDurationValue = parseInt(matchDurationSpan.textContent);

    if (!eventTimeHomeClub.value) {
        alert("Please enter the event time");
        return;
    } else if (!playerSelectHomeClub.value) {
        alert("Please select a player");
        return;
    }

    switch (eventTypeHomeClub.value) {
        case "goal":
            if (!homeGoalTypeSelect.value) {
                alert("Please select a goal type");
                return;
            }
            homeEvent.goal.push({
                time: eventTimeHomeClub.value,
                scorer: playerSelectHomeClub.value,
                goal_type: homeGoalTypeSelect.value,
            });
            addGoalHomeClubInfo();
            break;
        case "yellow_card":
            homeEvent.yellow_card.push({
                time: eventTimeHomeClub.value,
                player: playerSelectHomeClub.value,
            });
            addCardHomeClubInfo("jaune");
            break;
        case "red_card":
            homeEvent.red_card.push({
                time: eventTimeHomeClub.value,
                player: playerSelectHomeClub.value,
            });
            addCardHomeClubInfo("rouge");
            break;
        case "substitution":
            homeEvent.substitution.push({
                time: eventTimeHomeClub.value,
                playerGoOut: playerSelectHomeClub.value,
                playerGoIn: substitutionPlayerSelectHomeClub.value,
            });
            addSubstitutionHomeClubInfo();
            break;
        default:
            alert("Please select an event type");
            break;
    }
    console.log("homeEvent : ");
    console.log(homeEvent);
});

const goalHomeClubInfo = document.getElementById("goal_home_club_info");
function addGoalHomeClubInfo() {
    const addLi = document.createElement("li");

    // xxxx à marqué à xxxx minutes sur xxxx
    addLi.textContent =
        playerSelectHomeClub.options[playerSelectHomeClub.selectedIndex].text +
        " a marqué à " +
        eventTimeHomeClub.value +
        " minutes de match sur " +
        homeGoalTypeSelect.options[homeGoalTypeSelect.selectedIndex].text;

    goalHomeClubInfo.appendChild(addLi);
}

function addCardHomeClubInfo(cardColor) {
    const addLi = document.createElement("li");

    // xxxx a reçu un carton jaune à xxxx minutes
    addLi.textContent =
        playerSelectHomeClub.options[playerSelectHomeClub.selectedIndex].text +
        " a reçu un carton " +
        cardColor +
        " à " +
        eventTimeHomeClub.value +
        " minutes de match";

    cardHomeClubInfo.appendChild(addLi);
}

function addSubstitutionHomeClubInfo() {
    const addLi = document.createElement("li");

    // xxxx à remplacé xxxx à xxxx minutes
    addLi.textContent =
        playerSelectHomeClub.options[playerSelectHomeClub.selectedIndex].text +
        " a été remplacé à " +
        eventTimeHomeClub.value +
        " minutes de match par " +
        substitutionPlayerSelectHomeClub.options[
            substitutionPlayerSelectHomeClub.selectedIndex
        ].text;

    substitutionHomeClubInfo.appendChild(addLi);
}

// -------------------VISITOR EVENT-------------------

const eventTimeVisitorClub = document.getElementById("event_time_visitor_club");
const eventTypeVisitorClub = document.getElementById("event_type_visitor_club");
const visitorButtonEvent = document.getElementById("visitor_button_event");
const playerSelectVisitorClub = document.getElementById(
    "player_select_visitor_club"
);
const visitorGoalTypeLabel = document.getElementById("visitor_goal_type_label");
const visitorGoalTypeSelect = document.getElementById(
    "visitor_goal_type_select"
);
const visitorGoalTypeSelectContainer = document.getElementById(
    "visitor_goal_type_select_container"
);
const substitutionPlayerSelectVisitorClubContainer = document.getElementById(
    "substitution_player_select_visitor_club_container"
);
const substitutionPlayerSelectVisitorClub = document.getElementById(
    "substitution_player_select_visitor_club"
);
const substitutionVisitorClubInfo = document.getElementById(
    "substitution_visitor_club_info"
);
const cardVisitorClubInfo = document.getElementById("card_visitor_club_info");
let visitorEvent = {
    goal: [],
    yellow_card: [],
    red_card: [],
    substitution: [],
};

eventTimeVisitorClub.addEventListener("input", function () {
    const matchDurationValue = parseInt(matchDurationSpan.textContent);
    if (!matchDurationValue) {
        alert("Please enter the match duration");
        this.value = 0;
    } else {
        if (parseInt(this.value) < 0) {
            this.value = 0;
        } else if (parseInt(this.value) > matchDurationValue) {
            alert("Event time cannot be greater than match duration");
            this.value = matchDurationValue;
        }
    }
});

function hideVisitorGoalTypeSelect() {
    if (visitorGoalTypeSelectContainer.style.display === "block") {
        visitorGoalTypeSelectContainer.style.display = "none";
    }
}

function hideVisitorSubstitutionPlayerSelect() {
    if (
        substitutionPlayerSelectVisitorClubContainer.style.display === "block"
    ) {
        substitutionPlayerSelectVisitorClubContainer.style.display = "none";
    }
}

eventTypeVisitorClub.addEventListener("change", function () {
    switch (this.value) {
        case "goal":
            visitorGoalTypeSelectContainer.style.display = "block";
            hideVisitorSubstitutionPlayerSelect();
            break;
        case "yellow_card":
            hideVisitorGoalTypeSelect();
            hideVisitorSubstitutionPlayerSelect();
            break;
        case "red_card":
            hideVisitorGoalTypeSelect();
            hideVisitorSubstitutionPlayerSelect();
            break;
        case "substitution":
            substitutionPlayerSelectVisitorClubContainer.style.display =
                "block";
            hideVisitorGoalTypeSelect();
            break;
        default:
            alert("Please select an event type");
            break;
    }
});

visitorButtonEvent.addEventListener("click", (e) => {
    //const matchDurationValue = parseInt(matchDurationSpan.textContent);

    if (!eventTimeVisitorClub.value) {
        alert("Please enter the event time");
        return;
    } else if (!playerSelectVisitorClub.value) {
        alert("Please select a player");
        return;
    }

    switch (eventTypeVisitorClub.value) {
        case "goal":
            if (!visitorGoalTypeSelect.value) {
                alert("Please select a goal type");
                return;
            }
            visitorEvent.goal.push({
                time: eventTimeVisitorClub.value,
                scorer: playerSelectVisitorClub.value,
                goal_type: visitorGoalTypeSelect.value,
            });
            addGoalVisitorClubInfo();
            break;
        case "yellow_card":
            visitorEvent.yellow_card.push({
                time: eventTimeVisitorClub.value,
                player: playerSelectVisitorClub.value,
            });
            addCardVisitorClubInfo("jaune");
            break;
        case "red_card":
            visitorEvent.red_card.push({
                time: eventTimeVisitorClub.value,
                player: playerSelectVisitorClub.value,
            });
            addCardVisitorClubInfo("rouge");
            break;
        case "substitution":
            visitorEvent.substitution.push({
                time: eventTimeVisitorClub.value,
                playerGoOut: playerSelectVisitorClub.value,
                playerGoIn: substitutionPlayerSelectVisitorClub.value,
            });
            addSubstitutionVisitorClubInfo();
            break;
        default:
            alert("Please select an event type");
            break;
    }
    console.log("visitorEvent : ");
    console.log(visitorEvent);
});

const goalVisitorClubInfo = document.getElementById("goal_visitor_club_info");
function addGoalVisitorClubInfo() {
    const addLi = document.createElement("li");

    addLi.textContent =
        playerSelectVisitorClub.options[playerSelectVisitorClub.selectedIndex]
            .text +
        " a marqué à " +
        eventTimeVisitorClub.value +
        " minutes de match sur " +
        visitorGoalTypeSelect.options[visitorGoalTypeSelect.selectedIndex].text;

    goalVisitorClubInfo.appendChild(addLi);
}

function addCardVisitorClubInfo(cardColor) {
    const addLi = document.createElement("li");

    addLi.textContent =
        playerSelectVisitorClub.options[playerSelectVisitorClub.selectedIndex]
            .text +
        " a reçu un carton " +
        cardColor +
        " à " +
        eventTimeVisitorClub.value +
        " minutes de match";

    cardVisitorClubInfo.appendChild(addLi);
}

function addSubstitutionVisitorClubInfo() {
    const addLi = document.createElement("li");

    addLi.textContent =
        playerSelectVisitorClub.options[playerSelectVisitorClub.selectedIndex]
            .text +
        " a été remplacé à " +
        eventTimeVisitorClub.value +
        " minutes de match par " +
        substitutionPlayerSelectVisitorClub.options[
            substitutionPlayerSelectVisitorClub.selectedIndex
        ].text;

    substitutionVisitorClubInfo.appendChild(addLi);
}

// -------------------SUBMIT BUTTON-------------------
document.getElementById("submit_btn").addEventListener("click", (e) => {
    //e.preventDefault();
    const winnerId = winnerSelect.value;
    if (!winnerId) {
        alert("Please select a winner");
        e.preventDefault();
        return;
    } else {
        document.getElementById("winner_club").value = winnerId;
        console.log(
            "winnerId : " + document.getElementById("winner_club").value
        );
    }

    const matchDuration = matchDurationNumber.value;
    if (!matchDuration) {
        alert("Please enter the match duration");
        e.preventDefault();
        return;
    } else {
        document.getElementById("match_duration").value = matchDuration;
        console.log(
            "matchDuration : " + document.getElementById("match_duration").value
        );
    }

    const homeEventInput = document.getElementById("home_event").value = JSON.stringify(homeEvent);
    console.log("homeEventInput : " + homeEventInput);

    const visitorEventInput = document.getElementById("visitor_event").value = JSON.stringify(visitorEvent);
    console.log("visitorEventInput : " + visitorEventInput);
});
