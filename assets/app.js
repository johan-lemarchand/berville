/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import Map from './js/map';

const eventsMap = document.querySelectorAll(".event-map")
eventsMap.forEach(event => {
    Map.init(event.parentElement.dataset.id);
})

const events = document.querySelectorAll(".event")
events.forEach(event => {
    event.addEventListener('click', () => {
        event.classList.add('rotate-and-hide')
        const eventMap = document.querySelector('#map'+ event.parentElement.dataset.id)
        eventMap.classList.add('rotate-and-display')
        const img = event.parentElement.querySelector('.back')
        img.classList.add('display-back')
    })
})

const backs = document.querySelectorAll(".back")
backs.forEach(back => {
    back.addEventListener('click', () => {
        back.classList.remove('display-back')
        const event = back.parentElement.querySelector('.event')
        event.classList.remove('rotate-and-hide')
        const eventMap = back.parentElement.querySelector('.event-map')
        eventMap.classList.remove('rotate-and-display')
    })
})
