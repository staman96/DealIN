import { Component, OnInit, AfterViewInit, ViewChild } from '@angular/core';
import { User } from 'src/app/core/_objects';
import { UserService } from 'src/app/core/_services';
import { MatTableDataSource} from '@angular/material/table';
import { Router } from '@angular/router';
import { MatPaginator, MatSort } from '@angular/material';

@Component({
  selector: 'app-all-users',
  templateUrl: './all-users.component.html',
  styleUrls: ['./all-users.component.css']
})
export class AllUsersComponent implements OnInit,AfterViewInit {

  user: User = new User;
  users: User[];

  @ViewChild(MatPaginator, {static: true}) paginator: MatPaginator;
  @ViewChild(MatSort,{static: true}) sort: MatSort;
  public dataSource = new MatTableDataSource<User>();

  displayedColumns: string[] = ['username', 'email', 'fname', 'lname', 'role', 'status', 'created', 'actions'];

  constructor(
    private userServ: UserService,
    private router: Router
    ) {  }

  ngOnInit() {
    this.read_users();
  }

  ngAfterViewInit(){
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  //get all users
  read_users(): void{
    this.userServ.getAll().subscribe(
      users =>{
       this.users = users,
       this.dataSource.data = users;
       console.log(users)
      }
       );
  }

  //navigate to edit profile
  editProf(id: string){
    this.router.navigate(['/profile/' + id]);
  }

}
