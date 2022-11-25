import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import frLocale from '@fullcalendar/core/locales/fr';

import "./index.css";
import Map from "../map";

document.addEventListener("DOMContentLoaded", () => {
    let calendarEl = document.getElementById("calendar-holder");
    let { eventsUrl } = calendarEl.dataset;

    let calendar = new Calendar(calendarEl, {
        editable: true,
        locales: [ frLocale ],
        eventSources: [
            {
                url: eventsUrl,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({})
                },
                failure: () => {
                    alert("Une erreur est survenue lors de la récupération des événements");
                },
            },
        ],
        headerToolbar: {
            left: "prev,next",
            center: "title",
            right: "dayGridMonth, timeGridWeek, timeGridDay, listWeek",
        },
        initialView: "dayGridMonth",
        navLinks: true,
        plugins: [ interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin ],
        timeZone: "fr",
        eventClick: function(event, jsEvent, view) {
            fetch('/event', {
                method: 'POST',
                body: event.event._def.extendedProps.resourceId,
                 headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            }).then((response) => {
               return response.json()
            }).then((body) => {
                $('#modalCalendar').modal('show');
                const cardEventContainer = document.querySelector('#cardEventContainer');
                cardEventContainer.innerHTML = body.content
                const eventsMap = document.querySelectorAll(".event-map")

                eventsMap.forEach(event => {
                    Map.init(event.parentElement.dataset.id);
                });

                const events = document.querySelectorAll(".event-container")

                events.forEach(event => {
                    event.addEventListener('click', () => {
                        event.classList.add('rotate-and-hide')
                        const eventMap = document.querySelector('#map'+ event.dataset.id)
                        eventMap.classList.add('rotate-and-display')
                        const img = event.querySelector('.back')
                        img.classList.add('display-back')
                    })
                })

                const back = document.querySelector(".back")

                    back.addEventListener('click', () => {
                        console.log(back);

                        back.classList.remove('display-back')
                        const event = back.parentElement.querySelector('.event')

                        event.classList.remove('rotate-and-hide')
                        const eventMap = back.parentElement.querySelector('.event-map')
                        eventMap.classList.remove('rotate-and-display')
                    })
            })
        }
    });
    calendar.render();
});