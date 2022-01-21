import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Category } from '../_objects';
import { Observable } from 'rxjs';
import { HostPath } from '../shared/host_path';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };

  config = { headers: new HttpHeaders().set('Content-Type', 'application/json') };

  private path: HostPath = new HostPath;
  
  private read_all_categoriesURL = this.path.host_path+'backend/api/category/read_all.php';

  constructor(private http: HttpClient) { } 

  read_categories(): Observable<Category[]> {
      // return all categories
      return this.http.post<Category[]>(this.read_all_categoriesURL, null , this.config) ;

  }
}
