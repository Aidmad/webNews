// TOGGLE COMMENT SECTION WHEN BUTTON IS CLICKED
const toggleButton = document.getElementById('toggle-comments');
const commentSection = document.getElementById('comment-section');
const submitButton = document.getElementById('submit-comment');
const commentInput = document.getElementById('comment-input');
const commentsList = document.getElementById('comments-list');

// Toggle comment section visibility
toggleButton.addEventListener('click', () => {
    const isVisible = commentSection.style.display === 'block';
    commentSection.style.display = isVisible ? 'none' : 'block';
    toggleButton.textContent = isVisible ? 'Комментарии' : 'Закрыть'; // Change button text
});

// Submit comment
submitButton.addEventListener('click', (event) => {
    const commentText = commentInput.value.trim();

    // Prevent form submission if the comment is empty
    if (!commentText) {
        alert('Комментарий не может быть пустым.');
        event.preventDefault(); // Stop form submission
        return;
    }

    // Allow the form to be submitted to the server if the comment is valid
    // This will send the comment to the PHP backend to be saved in the database
});
