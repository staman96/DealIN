import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ReactiveFormsModule }    from '@angular/forms';
import { HttpClientModule }    from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatInputModule, MatButtonModule, MatNativeDateModule, MatPaginatorModule, MatSortModule, MatFormFieldModule } from '@angular/material';
import { FormsModule } from '@angular/forms';
import { MatTableModule} from '@angular/material/table';
import { MatToolbarModule} from '@angular/material/toolbar';
import { MatDialogModule} from '@angular/material';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';

import { routing } from './app-routing.module';
import { AppComponent } from './app.component';

import { SideBarComponent } from './core/_components/home/side-bar/side-bar.component';
import { FooterComponent } from './core/_components/home/footer/footer.component';
import { AdminToolbarComponent } from './core/_components/admin-toolbar/admin-toolbar.component';

import { ProductsComponent } from './modules/';
import { HomeComponent} from './modules';
import { LoginComponent } from './core/_components/login/login.component';
import { CategoriesComponent } from './modules/categories/categories.component';
import { AdminDashboardComponent } from './modules/admin-dashboard/admin-dashboard.component';
import { SignupComponent } from './modules/signup/signup.component';
import { ProfileComponent } from './modules/profile/profile.component';
import { AllUsersComponent } from './modules/all-users/all-users.component';
import { NotificationComponent } from './core/_components/notification/notification.component';
import { PasswordMatchDirective } from './core/shared/password-match.directive';
import { ProductComponent, ConfirmationDialog } from './modules/product/product.component';
import { SearchComponent } from './modules/search/search.component';
import { MessagingComponent } from './modules/messaging/messaging.component';
import { AccountComponent } from './modules/account/account.component';
import { MyProductsListComponent, DelProductDialog, BidsPerProductDialog } from './modules/myproducts-list/myproducts-list.component';
import { LivePriceComponent } from './core/_components/home/live-price/live-price.component';
import { AddProductComponent } from './modules/add-product/add-product.component';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MapComponent } from './modules/map/map.component';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { AmazingTimePickerModule } from 'amazing-time-picker';
import { EditProductComponent } from './modules/edit-product/edit-product.component';
import { BidsListComponent } from './modules/bids-list/bids-list.component';
import { HashLocationStrategy, LocationStrategy } from '@angular/common';

@NgModule({
  declarations: [
    AppComponent,
    SideBarComponent,
    FooterComponent,
    ProductsComponent,
    AdminDashboardComponent,
    LoginComponent,
    HomeComponent,
    CategoriesComponent,
    SignupComponent,
    ProfileComponent,
    AllUsersComponent,
    NotificationComponent,
    AdminToolbarComponent,
    PasswordMatchDirective,
    ProductComponent,
    SearchComponent,
    MessagingComponent,
    AccountComponent,
    MyProductsListComponent,
    LivePriceComponent,
    AddProductComponent,
    ConfirmationDialog,
    MapComponent,
    EditProductComponent,
    BidsListComponent,
    DelProductDialog,
    BidsPerProductDialog
  ],
  entryComponents: [
    ConfirmationDialog,
    DelProductDialog,
    BidsPerProductDialog
  ],
  imports: [
    BrowserModule,
    ReactiveFormsModule,
    routing,
    HttpClientModule,
    FormsModule,
    BrowserAnimationsModule,
    MatInputModule,
    MatButtonModule,
    MatTableModule,
    MatToolbarModule,
    MatDialogModule,
    MatCheckboxModule,
    MatDatepickerModule,
    MatNativeDateModule,
    AmazingTimePickerModule,
    MatPaginatorModule,
    MatSortModule,
    MatFormFieldModule,
    MatSlideToggleModule
  ],
  exports: [
    MatPaginatorModule,
    MatSortModule,
    MatFormFieldModule
  ],
  providers: [{provide: LocationStrategy, useClass: HashLocationStrategy}
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
