import { Component, OnInit, ViewEncapsulation, Input, OnDestroy, HostListener, ViewChild, DoCheck } from '@angular/core';
import { Product } from 'src/app/core/_objects/product';
import { ProductsService } from 'src/app/core/_services/products.service';
import { ActivatedRoute, Router } from '@angular/router';
import { User } from 'src/app/core/_objects';
import { AuthenticationService, SearchService } from 'src/app/core/_services';
import { Subscription, Observable } from 'rxjs';
import { MatPaginator, MatTableDataSource } from '@angular/material';


@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css'],
  encapsulation: ViewEncapsulation.None,

})
export class ProductsComponent implements OnInit,OnDestroy {
  
  //declaring object variables
  obs: Observable<any>;
  products: Product[];
  product: Product;
  user: User = new User;
  sub: Subscription;
  search: boolean = false;

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  public dataSource = new MatTableDataSource<Product>();

  //input parameter
  @Input() param = '';

  //instatiating services
  constructor(
    private productServ: ProductsService,
    private route: ActivatedRoute,
    private router: Router,
    private auth: AuthenticationService,
    private searchServ: SearchService
  ){ }
  
  ngOnInit() {
    //get current user
    this.user = this.auth.getUser();
    //get route paramameter
    this.param = this.route.snapshot.paramMap.get('param');
    
    if(this.param === 'live'){
      //read live auctions
      this.read_live_auctions();
    }
    else if(this.param === 'recommended'){
      //if not logged in reroute to home page
      if(this.user == null){
        this.router.navigate(['']);
        return;
      }
      //else load reccomended products
      this.read_recommendations();
    }
    else if(this.param){
      //load products from specific category
      this.read_cat_products();
    }
    else{
      //load all products
      this.read_products();
    }
  }

  ngAfterViewInit(){
    //fill paginator
    this.dataSource.paginator = this.paginator;
  }

  ngDoCheck(){
    console.log("content checked")
  }

  ngAfterContentChecked(){
    if(this.param != this.route.snapshot.paramMap.get('param')){
      this.refresh();
    }
    
    console.log("after content checked")
  }

  //"destructor"
  @HostListener('window:beforeunload')
  async ngOnDestroy(){
    this.products = null;
    if(this.sub){
      this.sub.unsubscribe();
    }
  }

  //returns products from specific category
  read_cat_products() {
    this.sub = this.productServ.get_prod_by_cat(this.param)
    .subscribe(products => {this.products = products,
      this.loadPaginator(products)
    });
  }

  //returns all products
  read_products(): void{
    this.sub = this.productServ.read_products()
      .subscribe(products => {this.products = products,
        this.loadPaginator(products);
      });
  }

  //returns recommended products
  read_recommendations(): void{
    this.sub = this.productServ.read_recommended(this.user.user_id)
      .subscribe(products => {this.products = products,
        this.loadPaginator(products);
      });
  }

  read_live_auctions(): void{
    this.sub = this.productServ.read_live_prods()
      .subscribe(products => {this.products = products,
        this.loadPaginator(products);
      });
  }

  //function that reloas the page
  refresh(): void {
    window.location.reload();
  }

  loadPaginator(data){
    this.dataSource.data = data;
    this.obs = this.dataSource.connect();
  }

  enableSearch(){
    if(this.search){
      this.searchServ.resultsProds
        .subscribe(data => {
          this.loadPaginator(data)
          // ,console.log(data);
        })
      
    }
    else{
      this.loadPaginator(this.products);
    }
  }

}
