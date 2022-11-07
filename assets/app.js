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
import {events, backs} from './js/events/eventsCard.js';
import 'tw-elements';


const eventsMap = document.querySelectorAll(".event-map")
eventsMap.forEach(event => {
    Map.init(event.parentElement.dataset.id);
});

let date = {month: null, year: null};

const cardEventContainer = document.querySelector("#cardEventContainer")

const selectMonth = document.querySelector("#months")
selectMonth.addEventListener('change', (e) => {
    date = {
        ...date,
        month: e.target.value
    }
})

const selectYear = document.querySelector("#years")
selectYear.addEventListener('change', (e) => {
    date = {
        ...date,
        year: e.target.value
    }
})

const choiceMonthButton = document.querySelector("#choiceMonth")
choiceMonthButton.addEventListener('click', (e) => {
        fetch('/event', {
            method: 'POST',
            body: JSON.stringify(date),
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
        }).then((response) => {
            return response.json()
        }).then((body) => {
            cardEventContainer.innerHTML = body.content
        })
})
