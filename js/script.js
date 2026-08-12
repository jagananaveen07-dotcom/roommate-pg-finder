function registerUser() {

    alert("Registration Successful!");

}
function registerUser() {

    let name = document.getElementById("fullname").value;
    let email = document.getElementById("email").value;
    let city = document.getElementById("city").value;
    let budget = document.getElementById("budget").value;
    let password = document.getElementById("password").value;

    let gender = "";

    if (document.getElementById("male").checked) {
        gender = "Male";
    }
    else if (document.getElementById("female").checked) {
        gender = "Female";
    }

    if (name == "" || email == "" || city == "" || budget == "" || gender == "" || password == "") {
        alert("Please fill all the fields.");
        return;
    }

    alert(
        "Registration Successful!\n\n" +
        "Name: " + name +
        "\nEmail: " + email +
        "\nCity: " + city +
        "\nBudget: ₹" + budget +
        "\nGender: " + gender
    );
}

function loginUser() {

    let email = document.getElementById("loginEmail").value;
    let password = document.getElementById("loginPassword").value;

    if (email == "" || password == "") {
        alert("Please enter Email and Password.");
        return;
    }
    alert("Login Successful!");
    window.location.href = "dashboard.html";
}
function searchPG() {

    let city = document.getElementById("searchCity").value;
    let budget = document.getElementById("searchBudget").value;

    let pg1 = document.getElementById("pg1");
    let pg2 = document.getElementById("pg2");
    let heading = document.getElementById("pgHeading");

    heading.style.display = "none";
    pg1.style.display = "none";
    pg2.style.display = "none";

    if (city == "" || budget == "") {
        alert("Please select City and Budget.");
        return;
    }

    if (city == "Bangalore" && budget >= 8500) {
        heading.style.display = "block";
        pg1.style.display = "block";
    }

    if (city == "Chennai" && budget >= 7000) {
        heading.style.display = "block";
        pg2.style.display = "block";
    }

}
function searchRoommate() {

    let city = document.getElementById("roommateCity").value;
    let budget = document.getElementById("roommateBudget").value;

    let roommate1 = document.getElementById("roommate1");
    let roommate2 = document.getElementById("roommate2");
    let roommate3 = document.getElementById("roommate3");

    roommate1.style.display = "none";
    roommate2.style.display = "none";
    roommate3.style.display = "none";

    if(city == "" || budget == ""){
        alert("Please select City and Budget.");
        return;
    }

    if(city == "Bangalore" && budget >= 8000){
        roommate1.style.display = "block";
    }

    if(city == "Hyderabad" && budget >= 7500){
        roommate2.style.display = "block";
    }

    if(city == "Chennai" && budget >= 9000){
        roommate3.style.display = "block";
    }

}
function sendRequest(name){

    alert("Roommate request sent to " + name + "!");

}
function logoutUser() {

    alert("Logged Out Successfully!");

    window.location.href = "login.php";

}