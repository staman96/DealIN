import { Injectable } from '@angular/core';
import { HostPath } from '../shared/host_path';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { View } from '../_objects';
import { AuthenticationService } from './authentication.service';

@Injectable({
  providedIn: 'root'
})
export class AddViewService {

  view: View = new View;

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };

  config = { headers: new HttpHeaders().set('Content-Type', 'application/json') };

  private path: HostPath = new HostPath;
  private create_viewURL = this.path.host_path+'backend/api/view/create.php';


  constructor(
    private http: HttpClient,
    private auth: AuthenticationService
    ) { }


  create_view(product_id: string): void{

    if(!this.auth.logged_in()) return;

    this.view.product_id = product_id;
    this.view.user_id = this.auth.getUser().user_id;
    

    this.http.post<any>(this.create_viewURL, JSON.stringify(this.view) )
    .subscribe(
      data  => {
        console.log("POST Request is successful ", data);
      },
      error  => {
        console.log("Error", error);
      });
  }


}
