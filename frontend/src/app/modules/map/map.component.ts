import { Component, OnInit, Input, Inject, ViewEncapsulation} from '@angular/core';
// import plugin
// import "node_modules/leaflet-routing-machine/dist/leaflet-routing-machine.js";

import { ProductsService } from 'src/app/core/_services';
import { Observable } from 'rxjs';
import { Product } from 'src/app/core/_objects';


declare let L;

@Component({
  selector: 'app-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class MapComponent implements OnInit{
 
  constructor(
    private prodServ:ProductsService
  ) { }

  @Input('product_slug') product_slug: string;

  ngOnInit() {

    this.prodServ.read_prod_by_slug(this.product_slug)
      .subscribe(data => {
        if(data.product_osm_lat && data.product_osm_long) {
          this.loadMap(data.product_osm_lat,data.product_osm_long);
        } else {
          var map = document.getElementById('map');
          map.remove();
        }
         
      });

    
  }

  loadMap(lat, long){
    var customIcon = L.icon({
      iconUrl: 'https://iconshow.me/media/images/Mixed/small-n-flat-icon/png2/128/-map-marker.png',
      shadowUrl: '',
      iconSize:     [38, 38], // size of the icon
      shadowSize:   [50, 64], // size of the shadow
      iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
      shadowAnchor: [4, 62],  // the same for the shadow
      popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    const map = L.map('map').setView([lat, long], 13);
    L.marker([lat, long], {icon: customIcon}).addTo(map);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.Routing.control({
        waypoints: [
            L.latLng(lat, long),
        ]
    }).addTo(map); 
  }

}
