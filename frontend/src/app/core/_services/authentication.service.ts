import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { User } from '../_objects';
import { HostPath } from '../shared/host_path';

@Injectable({ providedIn: 'root' })
export class AuthenticationService {
    private currentUserSubject: BehaviorSubject<User>;
    public currentUser: Observable<User>;
    private user: User = new User;
    private path: HostPath = new HostPath;
    
    loginURL = this.path.host_path+'backend/api/user/login.php';

    constructor(private http: HttpClient) {
        this.currentUserSubject = new BehaviorSubject<User>(JSON.parse(localStorage.getItem('currentUser')));
        this.currentUser = this.currentUserSubject.asObservable();
    }

    public get currentUserValue(): User {
        return this.currentUserSubject.value;
    }

    login(email: string, user_password: string): Observable<User> {
        this.user.email = email;
        this.user.user_password = user_password;
        return this.http.post<User>(this.loginURL, JSON.stringify(this.user))
            .pipe(map(user => {
                // login successful if there's a the response
                if (user && (user.user_role === '1' || user.user_role === '0')) {
                    // store user details in local storage to keep user logged in between page refreshes
                    localStorage.setItem('currentUser', JSON.stringify(user));
                    this.currentUserSubject.next(user);
                }

                return user;
            }));
    }

    logged_in(): boolean{
        if (localStorage.getItem('currentUser') != null){
            return true;
        }
        return false;
    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('currentUser');
        this.currentUserSubject.next(null);
    }

    getUser(): User{
        if (this.logged_in()){
            return JSON.parse(localStorage.getItem('currentUser'))
        }
        return null;

    }
}