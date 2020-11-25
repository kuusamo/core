import axios from 'axios';

const completionBox = document.querySelector('#completion-box');

if (completionBox) {
    const loadingIcon = completionBox.querySelector('#loading-icon');

    completionBox.querySelector('input').onchange = function(event) {
        loadingIcon.style.visibility = 'visible';
        completionBox.classList.add('is-loading');

        axios.post(
            completionBox.getAttribute('data-url'),
            { checked: event.currentTarget.checked }
        ).catch(function(error) {
            console.log('Error updating lesson status:', error.response.data);
            loadingIcon.style.visibility = 'hidden';
            completionBox.classList.remove('is-loading');
        }).then(function(response) {
            loadingIcon.style.visibility = 'hidden';
            completionBox.classList.remove('is-loading');
        });
    }
}

const menuButton = document.querySelector('header button');

if (menuButton) {
    menuButton.onclick = function() {
        console.log('Hello');
        document.querySelector('nav').classList.toggle('active');
    }
}
