import axios, { type AxiosInstance, type AxiosError } from 'axios';
import type { 
  AvailabilityResponse, 
  AppointmentRequest, 
  AppointmentResponse,
  ApiError 
} from '../shared/types/appointment';

// ELIMINADA: La línea 'import { useRuntimeConfig } from "nuxt/kit";'

class ApiService {
  private api: AxiosInstance;

  constructor() {
    // useRuntimeConfig se usa directamente sin importar de ningún sitio
    const config = useRuntimeConfig();
    this.api = axios.create({
      baseURL: config.public.apiBase,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      withCredentials: true,
    });

    // Interceptor para manejar errores
    this.api.interceptors.response.use(
      (response) => response,
      (error: AxiosError<ApiError>) => {
        return Promise.reject(error);
      }
    );
  }

  async getAvailability(date: string): Promise<AvailabilityResponse> {
    const response = await this.api.get<AvailabilityResponse>('/appointments/availability', {
      params: { date }
    });
    return response.data;
  }

  async createAppointment(data: AppointmentRequest): Promise<AppointmentResponse> {
    const response = await this.api.post<AppointmentResponse>('/appointments', data);
    return response.data;
  }
}

export default new ApiService();