import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-notification',
  templateUrl: './notification.component.html',
  styleUrls: ['./notification.component.css']
})
export class NotificationComponent implements OnInit {

  pending: string = "Your account activation is pending!";
  logout: string = "Logout Successful!";
  returnUrl: string;
  type: string;


  constructor(
    private route: ActivatedRoute,
    private router: Router
    ) { }

  ngOnInit() {
    this.type = this.route.snapshot.paramMap.get('type');
    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/';
  }

  nav(): void{
   this.router.navigate(['/']);
  }

}
