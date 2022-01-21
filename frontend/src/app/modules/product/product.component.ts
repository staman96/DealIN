import { Component, OnInit, ViewEncapsulation, Input, Inject } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { User, Product, Bid, View, AuctionData } from 'src/app/core/_objects';
import { UserService, ProductsService, AuthenticationService } from 'src/app/core/_services';
import { FormBuilder, FormGroup, NgForm, Validators } from '@angular/forms';
import { BidService } from 'src/app/core/_services/bid.service';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { AddViewService } from 'src/app/core/_services/add-view.service';
import { Observable } from 'rxjs';

export interface DialogData {
  bid: number;
  cont: boolean;
}

@Component({
  selector: 'app-product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class ProductComponent implements OnInit {

  slug: string;
  seller: User = new User;
  user: User = new User;
  product: Product = new Product;
  current_is_bidder: boolean = false;
  bid: Bid = new Bid;
  result: boolean = false;


  constructor(
    private route:ActivatedRoute,
    private userServ:UserService,
    private prodServ:ProductsService,
    private auth:AuthenticationService,
    private bidserv: BidService,
    public dialog: MatDialog,
    private viewServ: AddViewService) { }

  ngOnInit() {
    this.slug = this.route.snapshot.paramMap.get('product_slug');
    //get current user, if visitor user = null
    this.user = this.auth.getUser();
    //get product data
    this.get_product_data();
    
  }

  valid(): Observable<AuctionData>{
    return this.prodServ.get_live_data(this.product.product_id);
  }

  onBid(newbid: number){
    // console.log('number:', newbid);

    //open confiramtion dialog
    this.openDialog(newbid);

  }

  openDialog(amount: number){
    //open dialog and pass data
    const dialogRef = this.dialog.open(ConfirmationDialog, {
      width: '250px',
      data: {bid: amount, cont: this.result}
    });

    //after dialog is closed get response
    dialogRef.afterClosed().subscribe(result => {
      console.log('The dialog was closed');
      this.result = result;
      //user cancelled the bid
      if (!this.result){
        alert('canceled');
        this.result = false;
      }
      //user accepted to bid
      else{
        var curr: number;
        //get product current value and...
        this.prodServ.get_live_data(this.product.product_id).toPromise().then(
          data => 
            //...check if bid amount is valid
            this.validate(amount, data.current_value, data.product_status)
        );
      }
      console.log(result);
    });
  }


  validate(amount: number, current: number, status: string){
    if (amount <= current || status != '1'){
      //if not valid return
      alert('invalid bid');
      return;
    }
    else{
      //if valid submit
      this.Submit(amount);
    }
  }

  Submit(amount: number): boolean{

    //fill bid object to send
    this.bid.bid_amount = amount;
    this.bid.product_id = this.product.product_id;
    this.bid.user_id = this.user.user_id;
    console.log(this.bid);

    //send bid
    this.bidserv.create_bid(this.bid)
    .subscribe(
      data  => {
        console.log("POST Request is successful ", data);
        alert("Bid was successful!");
      },
      error  => {
        console.log("Error", error);
        alert("Bid wasn't successful!");
      });
    
    return true;
  }
 
  get_product_data(){
    
    //req product data
    this.prodServ.read_prod_by_slug(this.slug)
      .toPromise().then(product =>{
        this.product = product;
        //if its not my product:
        if(this.user && this.product.user_id != this.user.user_id ){
          //1.create a view
          this.viewServ.create_view(this.product.product_id);
          //2.I can bid
          this.current_is_bidder = true;
        }
        else{
          //i cant bid
          this.current_is_bidder = false;
        };
        //get product owner data
        this.userServ.getById(product.user_id)
            .subscribe(seller =>this.seller = seller);
      }
    );
  }

  //returns true if user is logged
  logged(): boolean{
    return this.auth.logged_in();
  }


}

/*bid confirmation dialog*/
@Component({
  selector: 'confirmation-dialog',
  templateUrl: 'product-dialog.component.html',
})
export class ConfirmationDialog {

  constructor(
    public dialogRef: MatDialogRef<ConfirmationDialog>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) {}

  onNoClick(): void {
    this.dialogRef.close();
  }

}