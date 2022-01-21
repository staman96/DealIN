import { Component, OnInit, ViewEncapsulation, Input } from '@angular/core';
import { Validators, FormBuilder, FormGroup, FormArray, FormControl, ValidatorFn } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ProductsService, CategoryService, AuthenticationService } from 'src/app/core/_services';
import { Product, User, Category } from 'src/app/core/_objects';
import { AmazingTimePickerService } from 'amazing-time-picker';
import { formatDate } from '@angular/common';


@Component({
  selector: 'app-edit-product',
  templateUrl: './edit-product.component.html',
  styleUrls: ['./edit-product.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class EditProductComponent implements OnInit {

  edPform: FormGroup;
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

  x: any;
  

  constructor(
    private formbuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private catServ: CategoryService,
    private prodServ: ProductsService,
    private atp: AmazingTimePickerService,
    private authServ: AuthenticationService
    ) {
      //form instatiation
    this.edPform = this.formbuilder.group(
      {
        name: '',
        desc: '',
        start_price: 0,
        longitude: '',
        latitude: '',
        country: '',
        selectedDate: '',
        end_time: '',
        cats: new FormArray([])
      }
    );
    
  }

  ngOnInit() {

    this.product.product_id = this.route.snapshot.paramMap.get('id');
    this.user = this.authServ.getUser();
    


    this.prodServ.read_one(this.product.product_id)
          .subscribe(data =>{
            this.product = data;
            //check if product is mine, if not change route
            if(data.user_id != this.user.user_id) this.router.navigate(['/my-products']);
            // cset data
            const datetime: string[] = data.auction_ends.split(' ');
            const date = datetime[0];
            const time = datetime[1];
            this.edPform = this.formbuilder.group(
              {
                name: [data.product_name, Validators.required ],
                desc: [data.product_description, Validators.required ],
                start_price: data.auction_starting_price,
                longitude: [data.product_osm_long, Validators.required ],
                latitude: [data.product_osm_lat, Validators.required ],
                country: data.product_osm_country,
                selectedDate : date,
                end_time: time,
                cats: new FormArray([], this.minCategories(1))
              }
            );
               //get categories
            this.catServ.read_categories()
              .subscribe(cats =>{
                this.categories = cats,
                
                  this.addCats(cats, data.categories);
                }
            );
            
          });
  }

  //control to validate if theres at least one category checked
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

  //time picker
  open() {
    const amazingTimePicker = this.atp.open();
    amazingTimePicker.afterClose().subscribe(time => {
      console.log(time);
    });
  }

  //adds categories to checkboxes
  private addCats(cats,selected: string[]){
    
    cats.forEach((o, i) => {
      var control: any;
        if (selected &&  selected.indexOf(o.category_id) > -1 ) {
          control = new FormControl(true);
        } else {
          control = new FormControl();
        }

      (this.a.cats as FormArray).push(control);
    });
    
  }

  //form submition and checks validation
  onSubmit(edPForm): boolean{
    this.submitted = true;
    console.warn('Updating a Product', edPForm);

    // stop here if form is invalid
    if (this.edPform.invalid) {

      return false;
    }

    const categoryIds = this.edPform.value.cats
    .map((v, i) => v ? this.categories[i].category_id : null)
    .filter(v => v !== null);

    this.product.user_id = this.user.user_id;
    this.product.product_name = this.a.name.value;
    this.product.auction_starting_price = this.a.start_price.value;
    this.product.product_description = this.a.desc.value;
    this.product.product_osm_long = this.a.longitude.value;
    this.product.product_osm_lat = this.a.latitude.value;
    this.product.product_osm_country = this.a.country.value;
    
    //format date and add time
    const format = 'yyyy-MM-dd';
    const locale = 'en-US';
    const formattedDate = formatDate(this.a.selectedDate.value, format, locale);
    this.product.auction_ends = formattedDate.concat(' ',this.a.end_time.value,':00');
    

    this.product.categories = categoryIds;


    this.prodServ.update_product(this.product);



    this.edPform.reset();
    this.router.navigate(['/my-products']);
    return true;
  }

  getCats(){
    this.catServ.read_categories()
    .subscribe(cats => this.categories = cats);
  }

  goback(): void{
    this.router.navigate(['my-products']);
  }

  get a() { return this.edPform.controls; }

}

