import { Component, OnInit, ViewEncapsulation, Input } from '@angular/core';
import { Product } from 'src/app/core/_objects';
import { SearchService } from 'src/app/core/_services';
import { Subject } from 'rxjs';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class SearchComponent implements OnInit {

  //declare products objects
  private products: Product[];
  public prods: Product[] = [];

  //input from parent class to get the products to be filtered
  @Input() set _products(_products: Product[]){
    this.products = _products;
    console.log(this.products);
  }

  //declare event variables
  searchName$ = new Subject<string>();
  name: string;
  searchCat$ = new Subject<string>();
  cat: string;
  searchDesc$ = new Subject<string>();
  desc: string;
  searchMinVal$ = new Subject<string>();
  min: number;
  searchMaxVal$ = new Subject<string>();
  max: number;
  searchLoc$ = new Subject<string>();
  loc: string;


  constructor(
    private searchServ: SearchService

    ) { }

  ngOnInit() {
    // this.prodServ.read_products()
    //   .subscribe(data => this.products = data);
      
      this.searchInit();
  }

  //starts listening to user search input
  searchInit(){
    //name field
    this.searchName$.subscribe(term => {
      this.name = this.moldString(term),
      this.search()
    });
    //category field
    this.searchCat$.subscribe(term => {
      this.cat = this.moldString(term),
      this.search()
    });
    //description field
    this.searchDesc$.subscribe(term => {
      this.desc = this.moldString(term),
      this.search()
    });
    //min price field
    this.searchMinVal$.subscribe(term => {
      this.min = parseFloat(term),
      this.search()
    });
    //max price field
    this.searchMaxVal$.subscribe(term => {
      this.max = parseFloat(term),
      this.search()
    });
    //country field
    this.searchLoc$.subscribe(term => {
      this.loc = this.moldString(term)
      this.search()
    });
  }

  //search for products
  search(): void {
    this.prods = [];
    var p = this.products;
    //check each product, if valid push it to prods
    p.map(product => {(this.filtro(product))? this.prods.push(product) : false });
    //return to service so that parent component can take it
    this.searchServ.returnProds(this.prods);
  }

  //apply filters strings
  moldString(str: string): string{
    // str = str.trim();
    str = str.toLowerCase();
    return str;
  }

  //search functionality 
  filtro(product: Product): boolean{
    //term search states: 0->invalid, 1->found, 2->null
    var obj = [0,0,0,0,0,0];

    //handles search by name
    if(!this.name){
      obj[0] = 2;
    }
    else if(product.product_name.toLowerCase().search(this.name) != -1){
      obj[0] = 1;
    }
   
    //handles search by description
    if(!this.desc){
      obj[1] = 2;
    }
    else if(product.product_description.toLowerCase().search(this.desc) != -1){
      obj[1] = 1;
    }

    //handles search by categories
    if(!this.cat){
      obj[2] = 2;
    }
    else if(product.categories){
      product.categories.map(catname => (catname.toLowerCase().search(this.cat) != -1) ? obj[2]=1 : false);
    }

    //handles search by min price
    if(!this.min){
      obj[3] = 2;
    }
    else if(product.current_value >= this.min){
      obj[3] = 1;
    }

    //handles search by max price
    if(!this.max){
      obj[4] = 2;
    }
    else if(product.current_value <= this.max){
      obj[4] = 1;
    }

    //handles search by location
    if(!this.loc){
      obj[5] = 2;
    }
    else if(product.product_osm_country.toLowerCase().search(this.loc) != -1){
      obj[5] = 1;
    }

    var res = true;
    /*check if product passed all filters*/
    obj.map(val => (val < 1)? res = false: false);
    return res;

  }

}
