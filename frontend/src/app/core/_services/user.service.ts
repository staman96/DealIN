import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { User } from '../_objects';
import { Observable } from 'rxjs';
import { HostPath } from '../shared/host_path';

@Injectable({ providedIn: 'root' })
export class UserService {

    private path: HostPath = new HostPath;
    

    private getAllURL = this.path.host_path+'backend/api/user/read_all.php';
    private gerByIdURL = this.path.host_path+'backend/api/user/read_one.php';
    private create_userURL = this.path.host_path+'backend/api/user/create.php';
    private check_emailURL = this.path.host_path+'backend/api/user/check_email.php';
    private update_userURL = this.path.host_path+'backend/api/user/update.php';
    
    httpOptions = {
        headers: new HttpHeaders({ 'Content-Type': 'application/json' })
      };

    constructor(private http: HttpClient) { }

    getAll(): Observable<User[]> {
        return this.http.post<User[]>(this.getAllURL,null);
    }

    getById(id: string) {
        return this.http.post<User>(this.gerByIdURL, JSON.stringify({"user_id": id}));
    }

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

      is_email_used(mail: string): Observable<any>{
        return this.http.post<any>(this.check_emailURL, JSON.stringify({"email":mail}) );
    
      }

      update_user(user: User ): void{
            // console.log(user);

          this.http.post<any>(this.update_userURL,JSON.stringify(user))
          .subscribe(
              data  => {
                console.log("POST Request is successful ", data);
              },
              error  => {
                console.log("Error", error);
              }
            );
      }
}