import axios from 'axios';

const menuButton = document.querySelector('header button');

if (menuButton) {
    menuButton.onclick = function() {
        console.log('Hello');
        document.querySelector('nav').classList.toggle('active');
    }
}
