import { Component, ViewEncapsulation } from '@angular/core';
import { User, Product, Category } from '../../core/_objects';
import { ProductsService, CategoryService } from '../../core/_services';

@Component({
    templateUrl: 'home.component.html',
    styleUrls: ['home.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class HomeComponent {
    //variables declaration
    currentUser: User;
    userFromApi: User;
    products: Product[];
    categories: Category[];

    constructor(
        private prodServ: ProductsService,
        private catServ: CategoryService
    ) {
    }

    ngOnInit() {
        //read products
        this.prodServ.read_live_prods()
            .subscribe(data => {this.products = data });

        //read categories
        this.catServ.read_categories()
            .subscribe(data => this.categories = data);
    }
}