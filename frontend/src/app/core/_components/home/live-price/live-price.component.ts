import { Component, OnInit, Input, OnDestroy, HostListener, SimpleChange, SimpleChanges, OnChanges } from '@angular/core';
import { AuctionData } from 'src/app/core/_objects';
import { ProductsService } from 'src/app/core/_services';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-live-price',
  templateUrl: './live-price.component.html',
  styleUrls: ['./live-price.component.css']
})
export class LivePriceComponent implements OnInit,OnChanges,OnDestroy {

  //variable declaration

  //input id from parent component
  @Input('id') id: string;
  data: AuctionData = new AuctionData;
  timer: any;
  sub: Subscription;

  constructor(private prodServ: ProductsService) {}

  //runs Onchange after id goes from udefined to take its value
  ngOnChanges(changes: SimpleChanges) {
    const id: SimpleChange = changes.id;
    //get current valuue
    this.id = id.currentValue;

    // get data from server
    this.prodServ.get_live_data(this.id)
    .subscribe(data => {
      this.data = data;
      //if product is live  call refresh() periodically to get latest data
      if(data.product_status === '1'){
        this.refresh();
      }
    });
  }

  ngOnInit() {
    
  }

  @HostListener('window:beforeunload')
  async ngOnDestroy(){
    //Called once, before the instance is destroyed and call stop()
    if(this.sub){
      this.stop();
    }
  }

  //get latest current value, status and munber bids of specific product 
  get_live_price(){

    this.sub = this.prodServ.get_live_data(this.id)
    .subscribe(data => {
      this.data = data,
      console.log(data);
    });
  }

  //calls get_live_price() every second
  refresh(){
    this.timer = setInterval(() => {
      this.get_live_price();
    }, 3000);
  }

  //clears interval timer, and closes subscription
  stop(){
    clearInterval(this.timer);
    this.sub.unsubscribe();
  }

}
