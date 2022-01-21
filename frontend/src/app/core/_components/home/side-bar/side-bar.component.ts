import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { AuthenticationService } from 'src/app/core/_services/';
import { Router } from '@angular/router';
import { User } from 'src/app/core/_objects';


@Component({
  selector: 'app-side-bar',
  templateUrl: './side-bar.component.html',
  styleUrls: ['./side-bar.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class SideBarComponent implements OnInit {

  user: User = new User;
  N: string = '';

  constructor(
    private auth: AuthenticationService,
    private router: Router) { }
  
  logged(): boolean{
    return this.auth.logged_in();
  }

  logout(): void{
    this.auth.logout();
    this.router.navigate(['notification/logout']);
  }

  ngOnInit() {
    if (this.logged()){
      this.user = this.auth.getUser();
    }
  }

}
