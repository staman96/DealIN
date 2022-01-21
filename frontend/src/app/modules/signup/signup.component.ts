import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl, AbstractControl } from '@angular/forms';
import { UserService, AuthenticationService } from 'src/app/core/_services';
import { Router } from '@angular/router';
import { User } from 'src/app/core/_objects';
import { map } from 'rxjs/operators'; 
 
@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class SignupComponent implements OnInit {

  signUpform: FormGroup;
  returnUrl: string;
  error = '';
  submitted = false;
  user: User;
  match = false;

  constructor(
    private formbuilder: FormBuilder,
    private router: Router,
    private userServ: UserService,
    private authServ:AuthenticationService
    ) {
    // redirect to home if already logged in
    if (this.authServ.currentUserValue) { 
      this.router.navigate(['/']);
    }
  }

  ngOnInit() {
    this.signUpform = this.formbuilder.group(
      {
        username: ['', Validators.required ],
        email: new FormControl('', Validators.compose([Validators.required, Validators.email]), this.validateEmailNotTaken.bind(this)),
        password: ['', Validators.required ],
        password_rpt: ['', Validators.required ],
        firstname: ['', Validators.required ],
        lastname: ['', Validators.required ],
        telephone: [''],
        address: [''],
        vat: ['']
      }

    );
  }

  validateEmailNotTaken(control: AbstractControl) {
   return this.userServ.is_email_used(control.value).pipe(
     map(res => {
       console.log('result is: ', res);
       return res ? { emailTaken: true } : null;
      })
   );
  }

  passMatcher(): boolean{
    if(this.s.password.value === this.s.password_rpt.value){
      return true;
    }
    return false;
  }

  onSubmit(signupForm): boolean{
    this.submitted = true;

    // stop here if form is invalid
    if (this.signUpform.invalid) {
      console.warn('Sign Up form invalid!', signupForm);

      return false;
    }
    console.log('Account activation pending!', signupForm);

    this.userServ.create_user(
      this.s.username.value, 
      this.s.email.value, 
      this.s.password.value, 
      this.s.firstname.value, 
      this.s.lastname.value, 
      this.s.telephone.value, 
      this.s.vat.value, 
      this.s.address.value
    );

    this.signUpform.reset();
    this.router.navigate(['notification/pending']);
    return true;
  }

  goback(): void{
    this.router.navigate(['']);
  }

  get s() { return this.signUpform.controls; }

}
