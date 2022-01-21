// if (jQuery('#map').length > 0) {
//   var map = L.map( 'map', {
//     center: [56.130366, -106.346771],
//     minZoom: 2,
//     zoom: 10
//   });

//     L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
//     subdomains: ['a', 'b', 'c']
//     }).addTo( map )

//     var myIcon = L.icon({
//     iconUrl: 'https://cdn3.iconfinder.com/data/icons/bunch-of-stuff/126/slice87-512.png',
//     iconRetinaUrl: 'https://cdn3.iconfinder.com/data/icons/bunch-of-stuff/126/slice87-512.png',
//     iconSize: [29, 24],
//     iconAnchor: [9, 21],
//     popupAnchor: [0, -14]
//     })

//     var markers = [
//         {
//           "name": "Canada",
//           "url": "https://en.wikipedia.org/wiki/Canada",
//           "lat": 56.130366,
//           "lng": -106.346771
//         },
//      ];

//     for ( var i=0; i < markers.length; ++i ) {
//       L.marker( [markers[i].lat, markers[i].lng], {icon: myIcon} )
//       .bindPopup( '<a href="' + markers[i].url + '" target="_blank">' + markers[i].name + '</a>' )
//       .addTo( map );
//   }
// }