import { Injectable } from '@angular/core';
import { User } from '../_objects';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { HostPath } from '../shared/host_path';

@Injectable({
  providedIn: 'root'
})
export class SignupService {
  user: User;

  private path: HostPath = new HostPath;

  private create_userURL = this.path.host_path+'backend/api/user/create.php';
  private check_emailURL = this.path.host_path+'backend/api/user/check_email.php';

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };
  

  constructor(private http: HttpClient) { }

  create_user(
    username: string, 
    email: string, 
    password: string, 
    firstname: string, 
    lastname: string, 
    telephone: string, 
    vat: string, 
    address: string): void{

      this.http.post<any>(this.create_userURL,JSON.stringify(
      {"user_name":username,"email":email,"user_password":password,"fname":firstname,"lname":lastname,"telephone":telephone,"vat": vat,"address":address,"source":"front"}))
        .subscribe(
          data  => {console.log("POST Request is successful ", data);},
          error  => {console.log("Error", error);}
        );
  }

  check_mail_availibility(mail: string): Observable<boolean>{
    
    return this.http.post<boolean>(this.create_userURL, mail , this.httpOptions);

  }
}
