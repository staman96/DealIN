import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { User } from 'src/app/core/_objects';
import { ActivatedRoute, Router } from '@angular/router';
import { UserService } from 'src/app/core/_services';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class ProfileComponent implements OnInit {

  //variables declaration
  profileform: FormGroup;
  error = '';
  submitted = false;
  user: User = new User;
  match = false;
  admin: boolean = false;
  change_pass: boolean = false;
  temp_pass: string = ''; 
  passform: FormGroup;

  constructor(
    private formbuilder: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private userServ: UserService
    ) {
    
  }

  ngOnInit() {
    //variable for change password button and pass validation
    this.change_pass = false;

    //get route parameter
    this.user.user_id = '';
    this.user.user_id = this.route.snapshot.paramMap.get('id');

    //admin: if route parameter is set
    if(this.user.user_id){ 
      this.admin = true;
    }
    //user
    else{
      this.admin = false;
      this.user.user_id = JSON.parse(localStorage.getItem('currentUser')).user_id;
    }

    //form instantiation
    this.profileform = this.formbuilder.group(
      {
        username: '',
        email: '',
        firstname: '',
        lastname: '',
        role: '',
        status: '',
        telephone: '',
        address: '',
        vat: ''
      }
    );
    this.passform = this.formbuilder.group(
      { 
        password: '',
        password_rpt: ''
      }
    );

    //fill form with data
    this.userServ.getById(this.user.user_id)
      .subscribe(
        data => {
          this.user = data,
          this.profileform = this.formbuilder.group(
            {
              username: [data.user_name, Validators.required ],
              //can't change email
              email: data.email,
              firstname: [data.fname, Validators.required ],
              lastname: [data.lname, Validators.required ],
              role: data.user_role,
              status: data.user_status,
              telephone: data.telephone,
              address: data.address,
              vat: data.vat
            }
          );
      
          this.passform = this.formbuilder.group(
            { 
              password: ['', Validators.required ],
              password_rpt: ['', Validators.required ]
            }
          );
        }
      );

  }

  //leave button
  leave() {
    //if admin
    if(this.admin){
      this.router.navigate(['/users']);
    }
    //if user profile
    else{
      this.router.navigate(['/account']);
    }
  }

  //function that checks if input passwords match
  passMatcher(): boolean{
    if(this.passform.controls.password.value === this.passform.controls.password_rpt.value){
      return true;
    }
    return false;
  }

  //fucntion that gets called when user/admin want to update data(button update)
  onSubmit(): boolean{
    this.submitted = true;

    // console.log(this.user);
    
    // stop here if form is invalid
    if (this.profileform.invalid ) {

      return false;
    }

    //checks if user want to change password and if its invalid
    if (this.change_pass && this.passform.invalid ) {

      return false;
    }

    //if user change pass and is valid then set pass
    if (this.change_pass){
      this.user.user_password = this.passform.controls.password.value;
    }
    //else  set to empty string so that will not be updated
    else{ 
      this.user.user_password = '';
    }

    //update values where needed
    if (this.p.username.value) this.user.user_name = this.p.username.value;
    if(this.p.firstname.value) this.user.fname = this.p.firstname.value; 
    if(this.p.lastname.value) this.user.lname = this.p.lastname.value;
    if(this.p.telephone.value ) this.user.telephone = this.p.telephone.value; 
    if(this.p.vat.value) this.user.vat = this.p.vat.value; 
    if(this.p.address.value) this.user.address = this.p.address.value;
    
    //if admin,then can change role and status
    if(this.admin){
      this.user.user_role = this.profileform.controls.role.value;
      this.user.user_status = this.profileform.controls.status.value;
    }

    console.log(this.user);

    //update data
    this.userServ.update_user(this.user);

      //if admin
    if(this.admin){
      alert('Update successful');
    }
    //if user profile
    else{
      alert('Update was successful!');
    }
    this.ngOnInit();

    return true;
  }

  get p() { return this.profileform.controls; }

}
