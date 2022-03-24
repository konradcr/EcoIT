function filterCourses(filter) {
    // Declare variables
    let list, cards, i;

    // Get list of courses
    list = document.getElementById("courseList");
    // Get courses
    cards = list.getElementsByClassName("card");

    if (filter === "all") filter = "";

    // Loop through all list courses, and hide those who don't match the filtered query
    for (i = 0; i < cards.length; i++) {
        if (cards[i].className.indexOf(filter) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}