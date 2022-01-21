import { Component } from '@angular/core';
import { Router } from '@angular/router';

import { AuthenticationService } from './core/_services';
import { User, Role } from './core/_objects';

declare var $: any;

@Component({ selector: 'app-root', templateUrl: 'app.component.html' })
export class AppComponent {
    title = 'dealin-frontend';
    currentUser: User;

    

    constructor(
        private router: Router,
        private authenticationService: AuthenticationService
    ) {
        this.authenticationService.currentUser.subscribe(x => this.currentUser = x);
    }

    get isAdmin() {
        return this.currentUser && this.currentUser.user_role === Role.Admin;
    }

    logout() {
        this.authenticationService.logout();
        this.router.navigate(['/login']);
    }
}