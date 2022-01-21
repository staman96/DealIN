import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Bid, UserBid } from '../_objects';
import { Observable } from 'rxjs';
import { HostPath } from '../shared/host_path';

@Injectable({
  providedIn: 'root'
})
export class BidService {
  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };

  config = { headers: new HttpHeaders().set('Content-Type', 'application/json') };

  result: string;

  private path: HostPath = new HostPath;

  private bid_createURL = this.path.host_path+'backend/api/bid/create.php';
  private get_current_valueURL = this.path.host_path + 'backend/api/bid/get_current_bid.php';
  private bids_by_userURL = this.path.host_path+'backend/api/bid/bids_per_user.php';
  private bids_by_productURL = this.path.host_path+'backend/api/bid/bids_per_product.php';

  constructor(private http: HttpClient) { }

  create_bid(bid: Bid): Observable<any>{
    
    return this.http.post<any>(this.bid_createURL, JSON.stringify(bid),{observe: 'response'});
  }

  get_prod_current_value(id: string): Observable<Bid>{
    return this.http.post<Bid>(this.get_current_valueURL, JSON.stringify({"product_id":id}));
  }

  get_my_bids(user_id: string): Observable<UserBid[]>{
    return this.http.post<UserBid[]>(this.bids_by_userURL, JSON.stringify({"user_id":user_id}));
  }

  get_bids_per_product(product_id: string): Observable<Bid[]>{
    return this.http.post<Bid[]>(this.bids_by_productURL, JSON.stringify({"product_id":product_id}));
  }

}
