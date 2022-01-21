import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyProductsListComponent } from './myproducts-list.component';

describe('MyproductsListComponent', () => {
  let component: MyProductsListComponent;
  let fixture: ComponentFixture<MyProductsListComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyProductsListComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyProductsListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
