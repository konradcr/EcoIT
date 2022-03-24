function searchCourse() {
    // Declare variables
    let search, filter, list, cards, course, i, txtValue;

    // Get search text
    search = document.getElementById('mySearch');
    filter = search.value.toUpperCase();
    // Get list of courses
    list = document.getElementById("courseList");
    // Get courses
    cards = list.getElementsByClassName("card");

    // Loop through all list courses, and hide those who don't match the search query
    for (i = 0; i < cards.length; i++) {
        // Get course name
        course = cards[i].getElementsByClassName("card-title")[0];
        txtValue = course.textContent || course.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}