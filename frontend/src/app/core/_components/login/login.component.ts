import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthenticationService } from '../../_services';

@Component({ 
    templateUrl: 'login.component.html' ,
    styleUrls: ['login.component.css']})
export class LoginComponent implements OnInit {
    //variables declaration
    loginForm: FormGroup;
    submitted = false;
    returnUrl: string;
    error = '';
    adminPath = 'admin';

    constructor(
        private formBuilder: FormBuilder,
        private router: Router,
        private authenticationService: AuthenticationService
    ) { 
        // redirect to home if already logged in
        if (this.authenticationService.currentUserValue) { 
            this.router.navigate(['/']);
        }
    }

    ngOnInit() {
        this.loginForm = this.formBuilder.group({
            email: ['', Validators.required],
            user_password: ['', Validators.required]
        });

    }

    // convenience getter for easy access to form fields
    get f() { return this.loginForm.controls; }

    onSubmit() {
        this.submitted = true;

        // stop here if form is invalid
        if (this.loginForm.invalid) {
            return;
        }

        //execute login
        this.authenticationService.login(this.f.email.value, this.f.user_password.value)
            .subscribe(
                data => {
                    // console.log(data);
                    // console.log(data.user_role);

                    //if account is not activated to notification page
                    if(data.user_status === "0"){
                        this.router.navigate(["notification/pending"]);
                        localStorage.removeItem('currentUser');
                    }
                    //if admin go to cms
                    else if(data.user_role === "1"){
                        this.router.navigate([this.adminPath]);
                    }
                    //if user got ohome page
                    else{
                        this.router.navigate(['/']);
                    }
                },
                error => {
                    this.error = error;
                });
    }

    //cancel login go to homepage
    goback(): void{
        this.router.navigate(['']);
    }
}