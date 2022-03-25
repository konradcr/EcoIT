function quizCorrection() {
    // Get the quiz
    let quiz = document.getElementById('#quiz');
    // Get the questions
    let questions = quiz.getElementsByClassName('question');

    // Loop all the questions
    for (let i = 0; i < questions.length; i++) {
        // Get the answers
        let answers = questions[i].getElementsByTagName('input');

        //Loop all the answers and check if the user answer is correct
        for (let j = 0; j < answers.length; j++) {
            let answer = answers[j];
            if (answer.checked && answer.value === "1" ) {
                questions[i].style.color = 'green';
            } else if (answer.checked && answer.value !== "1") {
                questions[i].style.color = 'red';
            } else if (!answer.checked && answer.value === "1") {
                questions[i].style.color = 'green';
            }
        }
    }
}

function clearQuiz() {
    // Get the quiz
    let quiz = document.getElementById('#quiz');
    // Get the questions
    let questions = quiz.getElementsByClassName('question');

    for (let i = 0; i < questions.length; i++) {
        questions[i].style.color = 'black';
        // Get the answers
        let answers = questions[i].getElementsByTagName('input');

        //Loop all the answers and uncheck them
        for (let j = 0; j < answers.length; j++) {
            answers[j].checked = false;
        }
    }
}