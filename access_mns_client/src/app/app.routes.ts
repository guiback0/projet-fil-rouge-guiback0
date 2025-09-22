import { Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { UserComponent } from './components/user/user.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { PointageComponent } from './components/pointage/pointage.component';
import { PayMeCoffeeComponent } from './components/pay-me-coffee/pay-me-coffee.component';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },
  { 
    path: 'dashboard', 
    component: DashboardComponent,
    canActivate: [authGuard]
  },
  { 
    path: 'user', 
    component: UserComponent,
    canActivate: [authGuard]
  },
  { 
    path: 'pointage', 
    component: PointageComponent,
    canActivate: [authGuard]
  },
  { 
    path: 'coffee', 
    component: PayMeCoffeeComponent,
    canActivate: [authGuard]
  },
  { path: '**', redirectTo: '/login' }, // Wildcard route for 404 cases
];
