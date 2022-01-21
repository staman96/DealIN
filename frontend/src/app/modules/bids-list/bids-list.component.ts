import { Component, OnInit, ViewChild, AfterViewInit } from '@angular/core';
import { User, UserBid } from 'src/app/core/_objects';
import { AuthenticationService, BidService } from 'src/app/core/_services';
import { Router } from '@angular/router';
import { MatDialog, MatTableDataSource, MatPaginator, MatSort } from '@angular/material';

@Component({
  selector: 'app-bids-list',
  templateUrl: './bids-list.component.html',
  styleUrls: ['../myproducts-list/myproducts-list.component.css']
})
export class BidsListComponent implements OnInit,AfterViewInit {


  bids: UserBid[];
  user: User = new User;
  public dataSource = new MatTableDataSource<UserBid>();

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  @ViewChild(MatSort,{static: true}) sort: MatSort;
  
  displayedColumns: string[] = ['product_name', 'status', 'etime', 'bid_amount', 'bid_time'];

  constructor(
    private router: Router,
    private authServ: AuthenticationService,
    private bidServ: BidService,
    public dialog: MatDialog
    ) {  }

  ngOnInit() {
    this.user = this.authServ.getUser();
    this.bidServ.get_my_bids(this.user.user_id)
      .subscribe(data =>{
        this.bids = data,
        this.dataSource.data = data;
      }
    );

  }

  //load paginator and sorting
  ngAfterViewInit(){
    this.dataSource.paginator = this.paginator; 
    this.dataSource.sort = this.sort; 
  }

  //enable material filter
  public doFilter = (value: string) => {
    this.dataSource.filter = value.trim().toLocaleLowerCase();
  }

  //go bakc
  toProd(slug: string){
    this.router.navigate(['/product/' + slug]);
  }

}
