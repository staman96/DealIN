import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { Product } from './../_objects/product';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { HostPath } from '../shared/host_path';
import { AuctionData } from '../_objects';

@Injectable({
  providedIn: 'root'
})
export class ProductsService {

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };

  config = { headers: new HttpHeaders().set('Content-Type', 'application/json') };

  private path: HostPath = new HostPath;

  private read_all_productsURL = this.path.host_path+'backend/api/product/read_all.php';
  private read_one_productURL = this.path.host_path+'backend/api/product/read_one.php';
  private delete_productURL = this.path.host_path+'backend/api/product/delete.php';
  private create_productURL = this.path.host_path+'backend/api/product/create.php';
  private update_productURL = this.path.host_path+'backend/api/product/update.php';
  private get_by_catURL = this.path.host_path+'backend/api/product/get_products_by_cat.php';
  private get_by_slugURL = this.path.host_path+'backend/api/product/get_by_product_slug.php';
  private get_my_prodsURL = this.path.host_path+'backend/api/product/get_by_user_id.php';
  private get_recommendationsURL = this.path.host_path+'backend/api/product/get_lsh_products.php';
  private get_live_prodsURL = this.path.host_path+'backend/api/product/get_live_auctions.php';
  private get_live_dataURL = this.path.host_path+'backend/api/product/get_status_currentvalue.php';
  private start_auctionURL = this.path.host_path+'backend/api/product/auction_starts.php';

  constructor(private http: HttpClient) { } 

  read_products(): Observable<Product[]> {
      return this.http.post<Product[]>(this.read_all_productsURL, null) ;
  }

  //create product
  create_product(product: Product): void{
    this.http.post<Product>(this.create_productURL, JSON.stringify(product) )
    .subscribe(
      data  => {
        console.log("POST Request is successful ", data);
      },
      error  => {
        console.log("Error", error);
      });
  }

  //update product
  update_product(product: Product): void{

    this.http.post<Product>(this.update_productURL, JSON.stringify(product) )
    .subscribe(
      data  => {
        console.log("POST Request is successful ", data);
      },
      error  => {
        console.log("Error", error);
      });
  }

  //get products of specific category
  get_prod_by_cat(slug: string): Observable<Product[]>{
    return this.http.post<Product[]>(this.get_by_catURL, JSON.stringify({"category_slug": slug}));
  }

  //get product by providing slug
  read_prod_by_slug(slug: string): Observable<Product>{
    return this.http.post<Product>(this.get_by_slugURL, JSON.stringify({"product_slug": slug}), this.config);
  }

  //get all products
  read_my_products(id: string): Observable<Product[]>{
    return this.http.post<Product[]>(this.get_my_prodsURL, JSON.stringify({"user_id": id}), this.config);
  }

  //get recommended products
  read_recommended(id: string): Observable<Product[]>{
    return this.http.post<Product[]>(this.get_recommendationsURL, JSON.stringify({"user_id":id}));
  }

  //get live products 
  read_live_prods(): Observable<Product[]>{
    return this.http.post<Product[]>(this.get_live_prodsURL, null);
  }

  //get current: price,bids,total bids and product id
  get_live_data(id:string): Observable<AuctionData>{
    return this.http.post<AuctionData>(this.get_live_dataURL, JSON.stringify({"product_id":id}));
  }

  //get specific priduct by providing id
  read_one(id: string): Observable<Product>{
    return this.http.post<Product>(this.read_one_productURL, JSON.stringify({"product_id":id}));
  }

  //tells the server to start an auction by providing product_id
  start_auction(product_id: string){
    return this.http.post<any>(this.start_auctionURL, JSON.stringify({"product_id":product_id}));
  }

  //deletes product
  delete_prod(product_id: string){
    // console.log(JSON.stringify({"product_id":product_id}));
    return this.http.post<any>(this.delete_productURL, JSON.stringify({"product_id":product_id}));
  }
 
}
