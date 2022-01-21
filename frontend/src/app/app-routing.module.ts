import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CommonModule, } from '@angular/common';
import { BrowserModule  } from '@angular/platform-browser';


import { HomeComponent, ProductsComponent, CategoriesComponent } from './modules/';
import { LoginComponent } from './core/_components';
import { AuthGuard } from './core/_services';
import { Role } from './core/_objects';
import { AdminDashboardComponent } from './modules/admin-dashboard/admin-dashboard.component';
import { SignupComponent } from './modules/signup/signup.component';
import { ProfileComponent } from './modules/profile/profile.component';
import { AllUsersComponent } from './modules/all-users/all-users.component';
import { NotificationComponent } from './core/_components/notification/notification.component';
import { ProductComponent } from './modules/product/product.component';
import { MessagingComponent } from './modules/messaging/messaging.component';
import { AccountComponent } from './modules/account/account.component';
import { MyProductsListComponent } from './modules/myproducts-list/myproducts-list.component';
import { AddProductComponent } from './modules/add-product/add-product.component';
import { EditProductComponent } from './modules/edit-product/edit-product.component';
import { BidsListComponent } from './modules/bids-list/bids-list.component';

const appRoutes: Routes = [
  {
      path: '',
      component: HomeComponent
  },
  { 
      path: 'admin', 
      component: AdminDashboardComponent, 
      canActivate: [AuthGuard], 
      data: { roles: [Role.Admin] } 
  },
  { 
      path: 'login', 
      component: LoginComponent 
  },
  {
      path: 'products',
      component: ProductsComponent
     
  },
  {
    path: 'products/:param',
    component: ProductsComponent
  },
  {
    path: 'sign-up',
    component: SignupComponent
  },
  {
    path: 'account',
    component: AccountComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'profile',
    component: ProfileComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'profile/:id',
    component: ProfileComponent,
    canActivate: [AuthGuard], 
    data: { roles: [Role.Admin]}
  },
  {
    path: 'notification/:type',
    component: NotificationComponent
  },

  {
    path: 'product/:product_slug', 
    component: ProductComponent
  },
  {
    path: 'categories',
    component: CategoriesComponent
  },
  {
    path: 'users',
    component: AllUsersComponent,
    canActivate: [AuthGuard], 
    data: { roles: [Role.Admin]}
  },
  {
    path: 'messages',
    component: MessagingComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'my-products',
    component: MyProductsListComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'new-product',
    component: AddProductComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'edit-product/:id',
    component: EditProductComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'my-bids',
    component: BidsListComponent,
    canActivate: [AuthGuard]
  },

  // otherwise redirect to home
  { path: '**', redirectTo: ''}
];

export const routing = RouterModule.forRoot(appRoutes);
