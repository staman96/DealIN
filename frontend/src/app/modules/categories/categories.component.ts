import { Component, OnInit, ViewEncapsulation, OnDestroy } from '@angular/core';
import { Category } from 'src/app/core/_objects';
import { CategoryService } from 'src/app/core/_services/category.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css'],
  encapsulation: ViewEncapsulation.None,
})
export class CategoriesComponent implements OnInit,OnDestroy {
  
  category: Category;
  categories: Category[];
  sub: Subscription;

  constructor(
    private catServ: CategoryService
  ) { }

  //gets categories from db
  get_categories(): void{
    this.sub = this.catServ.read_categories()
      .subscribe(categories => this.categories = categories);

  }

  //call function to get categories
  ngOnInit() {
    this.get_categories();
  }

  //"destructor"
  ngOnDestroy() {
    if(this.sub) this.sub.unsubscribe();
  }

}
