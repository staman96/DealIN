import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Messages } from '../_objects';
import { Observable } from 'rxjs';
import { HostPath } from '../shared/host_path';

@Injectable({
  providedIn: 'root'
})
export class MessagingService {
  config = { headers: new HttpHeaders().set('Content-Type', 'application/json') };
  
  private path: HostPath = new HostPath;

  private groupMessagesURL = this.path.host_path+'backend/api/messages/get_grouped_messages.php';
  // private sendUrl = this.path.host_path+'backend/api/messages/read_by_sender_id.php';

  constructor(private http: HttpClient) { }

  read_group_messages(): Observable<Messages[]> {
    return this.http.post<Messages[]>(this.groupMessagesURL, JSON.stringify({"receiver_user_id" : JSON.parse(localStorage.getItem('currentUser')).user_id})) ;
  }

}
