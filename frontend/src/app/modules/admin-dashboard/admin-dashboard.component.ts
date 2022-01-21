import { Component, OnInit } from '@angular/core';
import { User, Product } from '../../core/_objects';
import { AuthenticationService, ProductsService } from '../../core/_services';
import { saveAs } from 'file-saver';
import { parse } from 'js2xmlparser';

@Component({
    templateUrl: 'admin-dashboard.component.html',
    styleUrls: ['./admin-dashboard.component.css']})
export class AdminDashboardComponent implements OnInit {

    admin: User = new User;
    products: Product[];
    

    constructor(
        private authServ: AuthenticationService,
        private prodServ: ProductsService
        ) { }

    ngOnInit() {
        
        this.admin = this.authServ.getUser();
        this.prodServ.read_products()
            .subscribe(data => {
                this.products = data;

            });
    }

    //download products as json file
    downLoadFileJSON() {
        let jsn = JSON.stringify(this.products);
        var blob = new Blob([jsn], {type: "application/json:charset=utf-8"});
        console.log(blob);
        saveAs(blob, "AllProducts.json");
    }

    //download products as xml file
    downLoadFileXML() {

        var xml = parse("product", this.products);
        var blob = new Blob([xml], {type: "application/xml"});
        console.log(blob);
        saveAs(blob, "AllProducts.xml");
    }
}


// npm install file-saver --save

// npm install @types/file-saver --save-dev

// npm install -g gulp

// npm install js2xmlparser

// npm i @types/node