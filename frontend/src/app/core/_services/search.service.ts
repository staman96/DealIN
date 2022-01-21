import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';
import { Product } from '../_objects';


@Injectable({
  providedIn: 'root'
})
export class SearchService {
 
  private input = new Subject<Product[]>();
  private results = new Subject<Product[]>();

  inputProds = this.input.asObservable();
  resultsProds = this.results.asObservable();

  getdata(data: Product[]){
    this.input.next(data);
  }

  //return stream to parecnt class(products)
  returnProds(result: Product[]){
    this.results.next(result);
  }

}


