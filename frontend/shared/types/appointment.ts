export interface TimeSlot {
  time: string;
  available: boolean;
  display: string;
}

export interface AvailabilityResponse {
  date: string;
  slots: TimeSlot[];
}

export interface AppointmentForm {
  name: string;
  email: string;
}

export interface AppointmentRequest {
  customer_name: string;
  customer_email: string;
  appointment_at: string;
}

export interface AppointmentResponse {
  message: string;
  appointment: {
    id: number;
    customer_name: string;
    customer_email: string;
    appointment_at: string;
  };
}

export interface ApiError {
  message: string;
  errors?: Record<string, string[]>;
}