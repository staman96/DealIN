import { Component, OnInit } from '@angular/core';
import { MatToolbarModule } from '@angular/material/toolbar';
import { AuthenticationService } from '../../_services';
import { Router } from '@angular/router';
import { User } from '../../_objects';


@Component({
  selector: 'app-admin-toolbar',
  templateUrl: './admin-toolbar.component.html',
  styleUrls: ['./admin-toolbar.component.css']
})
export class AdminToolbarComponent implements OnInit {
  user: User = new User;

  constructor(
    private auth: AuthenticationService,
    private router: Router
  ) { }

  ngOnInit() {
    //get surrent user data
    this.user = this.auth.getUser();
  }

  logout(): void{
    //loggs user out
    this.auth.logout();
    this.router.navigate(['notification/logout']);
  }

}
