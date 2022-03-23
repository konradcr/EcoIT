function filterCourses(filter) {
    let list, cards, i;
    // Get list of courses
    list = document.getElementById("courseList");
    // Get courses
    cards = list.getElementsByClassName("card");
    if (filter === "all") filter = "";
    // Add the "show" class (display:block) to the filtered elements, and remove the "show" class from the elements that are not selected
    for (i = 0; i < cards.length; i++) {
        if (cards[i].className.indexOf(filter) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}