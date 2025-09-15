import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-example',
  standalone: true,
  templateUrl: './example.component.html',
  styleUrls: ['./example.component.css']
})
export class ExampleComponent {
  @Input() title: string = 'Example Component';
  @Input() count: number = 0;

  @Output() clicked: EventEmitter<void> = new EventEmitter<void>();

  handleClick(): void {
    this.clicked.emit();
  }
}

