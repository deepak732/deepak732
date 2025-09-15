import { Component, effect, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ApiService, Hotel } from '../../services/api.service';

@Component({
  selector: 'app-search',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './search.component.html',
  styleUrl: './search.component.scss'
})
export class SearchComponent {
  private api = inject(ApiService);
  private router = inject(Router);

  city = '';
  check_in = '';
  check_out = '';
  guests = 1;
  min_price?: number;
  max_price?: number;

  loading = signal(false);
  hotels = signal<Hotel[]>([]);

  search() {
    this.loading.set(true);
    this.api.searchHotels({ city: this.city, guests: this.guests, min_price: this.min_price, max_price: this.max_price, check_in: this.check_in, check_out: this.check_out })
      .subscribe({
        next: (res) => { this.hotels.set(res); this.loading.set(false); },
        error: () => { this.loading.set(false); }
      });
  }

  viewHotel(id: number) {
    this.router.navigate(['/hotel', id]);
  }
}

