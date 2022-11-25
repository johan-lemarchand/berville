const events = document.querySelectorAll(".event-container")

events.forEach(event => {
    event.addEventListener('click', () => {
        event.classList.add('rotate-and-hide')
        const eventMap = document.querySelector('#map'+ event.parentElement.dataset.id)
        console.log(eventMap);
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