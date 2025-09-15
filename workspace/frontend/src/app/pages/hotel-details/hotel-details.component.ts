import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService, Hotel, Room } from '../../services/api.service';

@Component({
  selector: 'app-hotel-details',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './hotel-details.component.html',
  styleUrl: './hotel-details.component.scss'
})
export class HotelDetailsComponent {
  private route = inject(ActivatedRoute);
  private api = inject(ApiService);
  private router = inject(Router);
  hotel = signal<Hotel | null>(null);

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.api.getHotel(id).subscribe(h => this.hotel.set(h));
  }

  book(room: Room) {
    this.router.navigate(['/book', room.id]);
  }
}

