import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Amenity { id: number; name: string }
export interface Room {
  id: number; hotel_id: number; name: string; capacity: number; price_per_night: number;
  description?: string | null; image_url?: string | null; amenities?: Amenity[];
}
export interface Hotel {
  id: number; name: string; city: string; address?: string | null; description?: string | null;
  rating?: number | null; image_url?: string | null; rooms?: Room[];
}
export interface BookingPayload {
  room_id: number; check_in: string; check_out: string; guests: number;
  customer_name: string; customer_email: string; customer_phone?: string;
}

@Injectable({ providedIn: 'root' })
export class ApiService {
  private http = inject(HttpClient);
  private baseUrl = (import.meta as any).env?.NG_APP_API_URL || 'http://localhost:8080/api/v1';

  searchHotels(params: { city?: string; guests?: number; min_price?: number; max_price?: number; amenities?: string; check_in?: string; check_out?: string; }): Observable<Hotel[]> {
    let httpParams = new HttpParams();
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        httpParams = httpParams.set(key, String(value));
      }
    });
    return this.http.get<Hotel[]>(`${this.baseUrl}/hotels`, { params: httpParams });
  }

  getHotel(id: number): Observable<Hotel> {
    return this.http.get<Hotel>(`${this.baseUrl}/hotels/${id}`);
  }

  createBooking(payload: BookingPayload) {
    return this.http.post(`${this.baseUrl}/bookings`, payload);
  }
}

