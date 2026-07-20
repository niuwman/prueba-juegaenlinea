<template>
  <div class="scheduler-container">
    <div class="death-theme">
      <h1 class="title">⚰️ Baile con la Muerte</h1>
      <p class="subtitle">Agenda tu cita final</p>
    </div>

    <div class="form-container">
      <!-- Selector de Fecha -->
      <div class="form-group">
        <label class="form-label">📅 Fecha de la cita</label>
        <input 
          type="date" 
          v-model="selectedDate"
          :min="minDate"
          class="input-field"
          :class="{ 'input-error': errorMessage && !selectedDate }"
        />
      </div>

      <div v-if="isLoading" class="loading-container">
        <div class="loading-spinner"></div>
        <span>Cargando disponibilidad...</span>
      </div>

      <div v-else-if="selectedDate && availabilityLoaded" class="form-group">
        <label class="form-label">⏰ Horarios disponibles</label>
        <div class="slots-grid">
          <button
            v-for="slot in slots"
            :key="slot.time"
            class="slot-button"
            :class="{
              'slot-available': slot.available,
              'slot-unavailable': !slot.available,
              'slot-selected': selectedSlot?.time === slot.time
            }"
            :disabled="!slot.available"
            @click="selectSlot(slot)"
          >
            {{ slot.display }}
          </button>
        </div>        
      
        <p v-if="slots.every(s => !s.available)" class="no-slots-message">
          😔 No hay horarios disponibles para esta fecha
        </p>
      </div>
      
      <div v-if="selectedSlot" class="form-group">
        <div class="form-row">
          <div class="form-field">
            <label class="form-label">👤 Nombre completo</label>
            <input 
              type="text" 
              v-model="form.name"
              placeholder="Ej: Juan Pérez"
              class="input-field"
              :class="{ 'input-error': errorMessage && !form.name }"
            />
          </div>
          <div class="form-field">
            <label class="form-label">📧 Correo electrónico</label>
            <input 
              type="email" 
              v-model="form.email"
              placeholder="ejemplo@correo.com"
              class="input-field"
              :class="{ 'input-error': errorMessage && !form.email }"
            />
          </div>
        </div>
        
        <div class="appointment-summary" v-if="selectedSlot">
          <p class="summary-text">
            <strong>📌 Cita seleccionada:</strong> 
            {{ formatDate(selectedDate) }} a las {{ selectedSlot.time }}
          </p>
        </div>

        <button 
          @click="submitAppointment"
          :disabled="!isFormValid || isSubmitting"
          class="submit-button"
          :class="{ 'submit-loading': isSubmitting }"
        >
          <span v-if="isSubmitting">⏳ Agendando...</span>
          <span v-else>💀 Agendar cita</span>
        </button>
      </div>

      <div v-if="successMessage" class="message success">
        <span>✅</span> {{ successMessage }}
      </div>
      
      <div v-if="errorMessage" class="message error">
        <span>❌</span> {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useAppointmentScheduler } from '../composables/useAppointmentScheduler';
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
const {
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
  selectSlot,
  submitAppointment,
} = useAppointmentScheduler();

const formatDate = (date: string) => {
  if (!date) return '';
  try {
    return format(new Date(date), "EEEE d 'de' MMMM 'de' yyyy", { locale: es });
  } catch {
    return date;
  }
};

const noDateSelected = computed(() => !selectedDate.value);
</script>

<style scoped>
.scheduler-container {
  @apply max-w-4xl mx-auto p-8 bg-death-dark rounded-xl;
  box-shadow: 0 0 40px rgba(0, 0, 0, 0.9);
  border: 1px solid #2a2a2a;
}

.death-theme {
  @apply text-center mb-8 pb-6 border-b border-gray-800;
}

.title {
  @apply text-4xl font-light tracking-widest text-death-red mb-2;
  text-shadow: 0 0 30px rgba(255, 107, 107, 0.2);
}

.subtitle {
  @apply text-gray-500 italic text-lg;
}

.form-container {
  @apply flex flex-col gap-6;
}

.form-group {
  @apply bg-gray-900/50 p-6 rounded-lg border border-gray-800;
}

.form-label {
  @apply block mb-3 text-gray-300 font-medium text-sm uppercase tracking-wider;
}

.input-field {
  @apply w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg 
         text-white text-base transition-all duration-300;
}

.input-field:focus {
  @apply outline-none border-death-red;
  box-shadow: 0 0 15px rgba(255, 107, 107, 0.15);
}

.input-field::placeholder {
  @apply text-gray-500;
}

.input-error {
  @apply border-red-600;
  box-shadow: 0 0 15px rgba(255, 0, 0, 0.15);
}

.loading-container {
  @apply flex items-center justify-center gap-4 py-8 text-gray-400;
}

.loading-spinner {
  @apply w-8 h-8 border-4 border-gray-700 border-t-death-red rounded-full animate-spin;
}

.slots-grid {
  @apply grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3;
}

.slot-button {
  @apply px-4 py-3 rounded-lg font-medium transition-all duration-300 
         text-sm border-2 border-transparent;
}

.slot-available {
  @apply bg-death-greenDark text-death-green hover:bg-green-800 
         hover:scale-105 hover:shadow-lg;
}

.slot-available:hover:not(:disabled) {
  box-shadow: 0 0 25px rgba(139, 195, 74, 0.2);
}

.slot-unavailable {
  @apply bg-gray-800 text-gray-600 cursor-not-allowed opacity-60;
}

.slot-selected {
  @apply bg-death-redDark text-death-red border-death-red;
  box-shadow: 0 0 30px rgba(255, 107, 107, 0.25);
  animation: pulseGlow 2s ease-in-out infinite;
}

.no-slots-message {
  @apply text-center text-gray-500 mt-4 text-sm italic;
}

.form-row {
  @apply grid grid-cols-1 md:grid-cols-2 gap-4 mb-6;
}

.form-field {
  @apply flex flex-col gap-2;
}

.appointment-summary {
  @apply mb-6 p-4 bg-gray-800/50 rounded-lg border border-gray-700;
}

.summary-text {
  @apply text-gray-300 text-center;
}

.summary-text strong {
  @apply text-death-red;
}

.submit-button {
  @apply w-full py-4 px-6 bg-gradient-to-r from-red-900/50 to-red-800/50 
         text-death-red font-bold rounded-lg border-2 border-death-red 
         transition-all duration-300 text-lg uppercase tracking-wider;
}

.submit-button:hover:not(:disabled) {
  @apply from-red-800/70 to-red-700/70;
  box-shadow: 0 0 40px rgba(255, 107, 107, 0.15);
  transform: scale(1.02);
}

.submit-button:disabled {
  @apply opacity-50 cursor-not-allowed transform-none;
}

.submit-loading {
  @apply animate-pulse;
}

.message {
  @apply p-4 rounded-lg text-center font-medium;
}

.message.success {
  @apply bg-green-900/20 text-death-green border border-death-greenDark;
}

.message.error {
  @apply bg-red-900/20 text-death-red border border-red-800;
}

@keyframes pulseGlow {
  0%, 100% {
    box-shadow: 0 0 20px rgba(255, 107, 107, 0.3);
  }
  50% {
    box-shadow: 0 0 40px rgba(255, 107, 107, 0.6);
  }
}

@media (max-width: 640px) {
  .scheduler-container {
    @apply p-4 mx-2;
  }
  
  .title {
    @apply text-3xl;
  }
  
  .slots-grid {
    @apply grid-cols-2;
  }
  
  .form-row {
    @apply grid-cols-1;
  }
}
</style>