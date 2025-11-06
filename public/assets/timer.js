$(document).ready(function () {
    // Set the timer duration (2 minutes in seconds)
    let timerDuration = 10;

    // Reference to the timer display and button
    const timerDisplay = $('#timer');
    const resendButton = $('#resendButton');

    // Function to format time in MM:SS format
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Update the timer every second
    const countdown = setInterval(() => {
        timerDuration--;

        // Update the timer display
        timerDisplay.text(formatTime(timerDuration));

        // When timer reaches zero, activate the resend button and stop the timer
        if (timerDuration <= 0) {
            clearInterval(countdown);
            resendButton.prop('disabled', false);
            resendButton.css('display', 'block');
            $('.timer-line').css('display', 'none');
        }
    }, 1000);
 
});

 