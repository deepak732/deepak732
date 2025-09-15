import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: '', loadComponent: () => import('./pages/search/search.component').then(m => m.SearchComponent) },
  { path: 'hotel/:id', loadComponent: () => import('./pages/hotel-details/hotel-details.component').then(m => m.HotelDetailsComponent) },
  { path: 'book/:roomId', loadComponent: () => import('./pages/booking/booking.component').then(m => m.BookingComponent) },
  { path: '**', redirectTo: '' }
];
