import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

export default class Map {

	static init (id) {
		let map = document.querySelector('#map'+id)

		if (map === null) {
			return;
		}

		let icon = L.icon({
				iconUrl: '/images/marker-icon.png',
		})
		let center = [map.dataset.lat, map.dataset.lng]

		map = L.map('map'+id).setView(center, 15)
		let token = 'pk.eyJ1Ijoiam9qbzI3MDAwIiwiYSI6ImNreHA5NXVxZTA3a2IyeXB0ZGI2OXVtbW0ifQ.KwNT1uD_HVIPa8jfEcHGaQ'
		L.tileLayer(`https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=${token}`, {
				tileSize: 512,
				maxZoom: 18,
				zoomOffset: -1,
				minZoom: 6,
				id: 'mapbox/streets-v11',
				attribution: '©️ <a href="https://www.mapbox.com/feedback/">Mapbox</a> ©️ <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
		}).addTo(map)
		L.marker(center, {icon: icon}).addTo(map)
	}

}