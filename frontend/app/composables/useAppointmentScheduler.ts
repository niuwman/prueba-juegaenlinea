import { ref, computed, watch } from 'vue';
import type { TimeSlot, AppointmentForm } from '../../shared/types/appointment';
import api from '../../services/api';

export function useAppointmentScheduler() {
  const selectedDate = ref<string>('');
  const slots = ref<TimeSlot[]>([]);
  const selectedSlot = ref<TimeSlot | null>(null);
  const form = ref<AppointmentForm>({ name: '', email: '' });
  const isLoading = ref<boolean>(false);
  const isSubmitting = ref<boolean>(false);
  const successMessage = ref<string>('');
  const errorMessage = ref<string>('');
  const availabilityLoaded = ref<boolean>(false);

  const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split('T')[0];
  });

  const isFormValid = computed(() => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return (
      form.value.name.trim() !== '' &&
      emailRegex.test(form.value.email) &&
      selectedSlot.value !== null
    );
  });

  const fetchAvailability = async () => {
    if (!selectedDate.value) return;

    isLoading.value = true;
    errorMessage.value = '';
    availabilityLoaded.value = false;
    selectedSlot.value = null;

    try {
      const response = await api.getAvailability(selectedDate.value);
      slots.value = response.slots;
      availabilityLoaded.value = true;
    } catch (error: any) {
      errorMessage.value = 'Error al cargar disponibilidad: ' + 
        (error.response?.data?.message || error.message);
    } finally {
      isLoading.value = false;
    }
  };

  const selectSlot = (slot: TimeSlot) => {
    if (!slot.available) return;
    selectedSlot.value = slot;
    errorMessage.value = '';
  };

  const submitAppointment = async () => {
    if (!isFormValid.value || isSubmitting.value) return;

    isSubmitting.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    try {
      const appointmentDateTime = `${selectedDate.value} ${selectedSlot.value!.time}:00`;

      const response = await api.createAppointment({
        customer_name: form.value.name,
        customer_email: form.value.email,
        appointment_at: appointmentDateTime
      });

      successMessage.value = response.message;
      form.value = { name: '', email: '' };
      selectedSlot.value = null;
      
      await fetchAvailability();
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const errors = error.response.data.errors;
        errorMessage.value = Object.values(errors).flat().join('. ');
      } else {
        errorMessage.value = error.response?.data?.message || 'Error al agendar la cita';
      }
    } finally {
      isSubmitting.value = false;
    }
  };

  // Watchers
  watch(selectedDate, () => {
    if (selectedDate.value) {
      fetchAvailability();
    }
  });

  return {
    selectedDate,
    slots,
    selectedSlot,
    form,
    isLoading,
    isSubmitting,
    successMessage,
    errorMessage,
    availabilityLoaded,
    minDate,
    isFormValid,
    fetchAvailability,
    selectSlot,
    submitAppointment
  };
}