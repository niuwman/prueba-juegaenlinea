<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Obtener disponibilidad de horas para una fecha específica
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d'
        ]);

        $date = Carbon::parse($request->date);
        
        // Si es fin de semana o fecha pasada, devolver todas las horas como no disponibles
        if ($date->isWeekend() || $date->isPast()) {
            return response()->json([
                'date' => $date->format('Y-m-d'),
                'slots' => $this->generateSlots(false)
            ]);
        }

        // Generar slots base
        $slots = $this->generateSlots(true);

        // Obtener citas ocupadas para esta fecha
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $occupiedSlots = Appointment::whereBetween('appointment_at', [$startOfDay, $endOfDay])
            ->pluck('appointment_at')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        // Marcar slots ocupados
        foreach ($slots as &$slot) {
            if (in_array($slot['time'], $occupiedSlots)) {
                $slot['available'] = false;
            }
        }

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'slots' => $slots
        ]);
    }

    /**
     * Registrar una nueva cita
     */
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {            
            Log::info('POST a /appointments', [
                'data' => $request->all(),
            ]);

            // Crear appointment sin validación compleja
            $appointment = Appointment::create([
                'customer_name' => $request->input('customer_name', 'Default Name'),
                'customer_email' => $request->input('customer_email', 'default@email.com'),
                'appointment_at' => $request->input('appointment_at', now()->addDay()),
            ]);

            return response()->json([
                'message' => 'Cita creada exitosamente',
                'appointment' => $appointment,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Generar los slots de hora para un día
     */
    private function generateSlots(bool $defaultAvailable): array
    {
        $slots = [];
        for ($hour = 9; $hour < 19; $hour++) {
            $time = sprintf('%02d:00', $hour);
            $slots[] = [
                'time' => $time,
                'available' => $defaultAvailable,
                'display' => $time . ' - ' . sprintf('%02d:00', $hour + 1)
            ];
        }
        return $slots;
    }
}