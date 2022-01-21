import { Component, OnInit, ViewEncapsulation, Input } from '@angular/core';
import { Validators, FormBuilder, FormGroup, FormArray, FormControl, ValidatorFn } from '@angular/forms';
import { Router } from '@angular/router';
import { ProductsService, CategoryService, AuthenticationService } from 'src/app/core/_services';
import { Product, User, Category } from 'src/app/core/_objects';
import { AmazingTimePickerService } from 'amazing-time-picker';
import { formatDate } from '@angular/common';

@Component({
  selector: 'app-add-product',
  templateUrl: './add-product.component.html',
  styleUrls: ['./add-product.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class AddProductComponent implements OnInit {

  //variables declaration
  newPform: FormGroup;
  returnUrl: string;
  error = '';
  submitted = false;
  user: User = new User;
  match = false;
  product: Product = new Product;
  categories: Category[];
  category: Category;
  minDate = new Date();
  d: Date;
  fileData: any;
  fileName: any;


  //instatiate services
  constructor(
    private formbuilder: FormBuilder,
    private router: Router,
    private catServ: CategoryService,
    private prodServ: ProductsService,
    private atp: AmazingTimePickerService,
    private authServ: AuthenticationService
    ) {
    
  }

  ngOnInit() {
    //get current user
    this.user = this.authServ.getUser();
    //instatiate form and validators
    this.newPform = this.formbuilder.group(
      {
        name: ['', Validators.required ],
        desc: ['', Validators.required ],
        start_price: '',
        longitude: ['', Validators.required ],
        latitude: ['', Validators.required ],
        country: ['', Validators.required ],
        selectedDate : '',
        end_time: '',
        cats: new FormArray([], this.minCategories(1))
      }
    );

    // returns all categories
    this.catServ.read_categories()
      .subscribe(cats =>{
        this.categories = cats,
        this.addCats(cats)}
      );

  }

  minCategories(min: number){
    const validator: ValidatorFn = (formArray: FormArray) => {
      const totalSelected = formArray.controls
        // get a list of checkbox values (boolean)
        .map(control => control.value)
        // total up the number of checked checkboxes
        .reduce((prev, next) => next ? prev + next : prev, 0);
  
      // if the total is not greater than the minimum, return the error message
      return totalSelected >= min ? null : { required: true };
    }
    return validator;
  }

  //opens timer to choose time
  open() {
    const amazingTimePicker = this.atp.open();
    amazingTimePicker.afterClose().subscribe(time => {
      console.log(time);
    });
  }

  //fills arrayform with category formcontrols
  private addCats(cats){
    cats.forEach((o, i) => {
      const control = new FormControl();
      (this.a.cats as FormArray).push(control);
    });
  }

  Uploadfile(event){
    const reader = new FileReader();

    if (event.target.files && event.target.files.length) {
      this.fileName = event.target.files[0].name;
      const [file] = event.target.files;
      reader.readAsDataURL(file);
     
      reader.onload = () => {
        this.fileData = reader.result;
      };
    }
  }

  
  //runs when the form is submitted
  onSubmit(newPForm): boolean{
    this.submitted = true;
    console.warn('Creating new Product', newPForm);

    // stop here if form is invalid
    if (this.newPform.invalid) {

      return false;
    }

    //get ids of selected categories
    const categoryIds = this.newPform.value.cats
    .map((v, i) => v ? this.categories[i].category_id : null)
    .filter(v => v !== null);
    console.log(categoryIds);

    console.log(this.a.selectedDate);
    

    //fill object to be sent for creation
    this.product.user_id = this.user.user_id;
    this.product.product_name = this.a.name.value;
    if (this.a.start_price.value){
      this.product.auction_starting_price = this.a.start_price.value;
    }
    else{
      this.product.auction_starting_price = 0;
    }
    
    this.product.product_description = this.a.desc.value;
    this.product.product_osm_long = this.a.longitude.value;
    this.product.product_osm_lat = this.a.latitude.value;
    this.product.product_osm_country = this.a.country.value;
    this.product.product_image = this.fileData;

    //format date and add time, if set
    if(this.a.selectedDate.value){
      const format = 'yyyy-MM-dd';
      const locale = 'en-US';
      const formattedDate = formatDate(this.a.selectedDate.value, format, locale);
      var time = this.a.end_time.value;
      //if time is not set, autoset to midnight
      if(!time) time = '00:00';
      this.product.auction_ends = formattedDate.concat(' ',this.a.end_time.value,':00');
    }
    else{
      //else send it null
      this.product.auction_ends = '';
    }
    
    console.log(this.product);
    this.product.categories = categoryIds;

    console.log(JSON.stringify(this.product));

    //create product
    this.prodServ.create_product(this.product);

    //reset form and go back
    this.newPform.reset();
    this.router.navigate(['/my-products']);
    return true;
  }

  //goes to previous page
  goback(): void{
    this.router.navigate(['my-products']);
  }

  //just saves some ink
  get a() { return this.newPform.controls; }

}

