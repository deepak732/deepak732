import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../../services/api.service';

@Component({
  selector: 'app-booking',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './booking.component.html',
  styleUrl: './booking.component.scss'
})
export class BookingComponent {
  private route = inject(ActivatedRoute);
  private api = inject(ApiService);
  private router = inject(Router);

  roomId = Number(this.route.snapshot.paramMap.get('roomId'));
  check_in = '';
  check_out = '';
  guests = 1;
  customer_name = '';
  customer_email = '';
  customer_phone = '';
  message = '';

  submit() {
    this.api.createBooking({
      room_id: this.roomId,
      check_in: this.check_in,
      check_out: this.check_out,
      guests: this.guests,
      customer_name: this.customer_name,
      customer_email: this.customer_email,
      customer_phone: this.customer_phone,
    }).subscribe({
      next: () => { this.message = 'Booking confirmed!'; setTimeout(() => this.router.navigate(['/']), 1000); },
      error: (err) => { this.message = err?.error?.message || 'Booking failed'; }
    });
  }
}

