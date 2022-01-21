import { Component, OnInit, ViewChild, AfterViewInit, Inject, OnDestroy, HostListener } from '@angular/core';
import { Product, User, Bid, AuctionData } from 'src/app/core/_objects';
import { ProductsService, AuthenticationService, BidService } from 'src/app/core/_services';
import { Router } from '@angular/router';
import { MatPaginator } from '@angular/material/paginator';
import { MatTableDataSource } from '@angular/material/table';
import { MatSort, MatDialogRef, MAT_DIALOG_DATA, MatDialog } from '@angular/material';
import { Observable } from 'rxjs';

export interface DelDialogData {
  del: boolean;
}

export interface BidsDialogData {
  product_name: string;
  bids: Bid[];
}

@Component({
  selector: 'app-myproducts-list',
  templateUrl: './myproducts-list.component.html',
  styleUrls: ['./myproducts-list.component.css']
})
export class MyProductsListComponent implements OnInit,AfterViewInit,OnDestroy {
  
  private result: boolean = false;
  product: Product = new Product;
  products: Observable<Product[]>;
  user: User = new User;
  live_data_array: AuctionData[] = [];
  timer: any;

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  @ViewChild(MatSort,{static: true}) sort: MatSort;

  displayedColumns: string[] = ['product_name', 'value',/* 'num_of_bids', */'status', 'stime', 'etime', 'actions'];

  public dataSource = new MatTableDataSource<Product>();

  
  constructor(
    private prodServ: ProductsService,
    private router: Router,
    private authServ: AuthenticationService,
    public dialog: MatDialog,
    private bidServ: BidService
    ) {  }

  ngOnInit() {
    //get current user
    this.user = this.authServ.getUser();
    //get_products
    this.products = this.prodServ.read_my_products(this.user.user_id);

      this.products.subscribe(data => {
        this.dataSource.data = data
    })

    this.InitLiveDataArray(this.products);
    this.updater()
  }

  //load padinator
  ngAfterViewInit(){
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  @HostListener('window:beforeunload')
  async ngOnDestroy(){
    //Called once, before the instance is destroyed and call stop()
    clearInterval(this.timer);
  }

  //initalize live data array
  InitLiveDataArray(Prod_array: Observable<Product[]>) {
 
    Prod_array.subscribe(data => {
    for (let i of data){
      var _data: AuctionData = new AuctionData;
      _data.current_value = i.current_value;
      _data.product_id = i.product_id;
      _data.product_status = i.product_status;
      _data.total_bids = 0;
      this.live_data_array.push(_data);
    }

  });
  }

  //gets live data if needed every some seconds
  startUpdates() {
    console.log('update!!');
    for (let live_data of this.live_data_array){
      
      if(live_data.product_status != '2'){

        var new_data:AuctionData = new AuctionData;
        this.prodServ.get_live_data( live_data.product_id)
          .subscribe(ld =>{
            live_data.current_value = ld.current_value;
            live_data.product_id = ld.product_id;
            live_data.product_status = ld.product_status;
            live_data.total_bids = ld.total_bids;
            live_data = new_data;
        })
      }
    }
  }

  //calls get_live_price() every 3 seconds
  updater(){
    this.timer = setInterval(() => {
      this.startUpdates();
    }, 3000);
    
  }

  //return live current value to html
  live_value(id: string): number{
    var res: number;
    if(this.live_data_array) this.live_data_array.map(data => data.product_id === id? res = data.current_value : false );
    else res = -1;
    return res;
  }

  //return live current status to html
  live_status(id: string): string{
    var res: string;
    if(this.live_data_array)this.live_data_array.map(data => data.product_id === id? res = data.product_status : false );
    // switch(res){
    //   case '0': res = 'Starting Soon'; break;
    //   case '1': res = 'OnGoing'; break;
    //   case '2': res = 'Ended'; break;
    //   }
    // }
    else res = '-1';
    return res;
  }

  //navigate to edit product product form
  editProd(id: string){
    this.router.navigate(['edit-product/' + id]);
  }

  //function that starts the auction if the ending date is set
  StartAuction(id: string){

    this.prodServ.read_one(id)
      .subscribe(data => {
        //if ending date is set
        if(!data.auction_ends.includes('0000-00-00')){
          //send to backend to start the auction
          this.prodServ.start_auction(id)
          .subscribe(
            data  => {
              console.log("POST Request is successful ", data);
            },
            error  => {
              console.log("Error", error);
            });
        }
        else{
          alert('Ending date has to be set!')
        }
      });
  
    //refresh page
    // this.refresh();
  }

  //deletes product
  delProd(product_id: string){
    this.openDelDialog()
      .subscribe(result =>{
        if(result){
          this.prodServ.delete_prod(product_id)
            .subscribe(
              data  => {
                console.log("POST Request is successful ", data);
                alert("Product was deleted successfully!");
                this.refresh();
              },
              error  => {
                console.log("Error", error);
                alert("Error deleting product!");
              }
            );
        }
      }
    );
  }

  openDelDialog(): Observable<boolean>{
    //open deletion dialog and pass data
    const dialogRef = this.dialog.open(DelProductDialog, {
      width: '250px',
      data: {cont: this.result}
    });

    //after dialog is closed get response
    return dialogRef.afterClosed();
  }

  seeBids(product_id: string, product_name: string){

    this.bidServ.get_bids_per_product(product_id)
      .toPromise().then( bids => {
        this.openBidsDialog(product_name, bids)
      }
    )
  }

  openBidsDialog(product_name: string, bids: Bid[]){
    //open bids dialog and pass data
    const dialogRef = this.dialog.open( BidsPerProductDialog, {
      width: '30%',
      data: {product_name: product_name, bids: bids}
    });

    //after dialog is closed get response
    return dialogRef.afterClosed();
  }

  refresh(): void {
    //reload window
      window.location.reload();
  }

}

/*product removal confirmation dialog*/
@Component({
  selector: 'prod-del-conf-dialog',
  templateUrl: 'product-del-dialog.component.html',
})
export class DelProductDialog {

  constructor(
    public dialogRef: MatDialogRef<DelProductDialog>,
    @Inject(MAT_DIALOG_DATA) public data: DelDialogData) {}

  onNoClick(): void {
    this.dialogRef.close();
  }
}

//dialog that shows bids of a product
@Component({
  selector: 'prod-bids-dialog',
  templateUrl: 'product-bids-dialog.component.html',
  styleUrls: ['./product-bids-dialog.component.css']
})
export class BidsPerProductDialog implements OnInit {

  displayedCols: string[] = ['index', 'amount', 'time'];

  constructor(
    public dialogRef: MatDialogRef<BidsPerProductDialog>,
    @Inject(MAT_DIALOG_DATA) public data: BidsDialogData) {}

    public dataSource = new MatTableDataSource<Bid>();

    ngOnInit(){
      this.dataSource.data = this.data.bids;
    }

  onNoClick(): void {
    this.dialogRef.close();
  }

}