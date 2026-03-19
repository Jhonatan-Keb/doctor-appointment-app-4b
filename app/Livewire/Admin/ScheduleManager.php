<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Livewire\Component;

class ScheduleManager extends Component
{
    public $doctorId;
    public $doctor;

    // 2D array: selectedSlots[day][time] = true/false
    public $selectedSlots = [];

    // Time slots from 08:00 to 17:45 in 15-min increments
    public $timeSlots = [];

    // Days of week
    public $days = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
    ];

    // Hours for grouping
    public $hours = [];

    public function mount($doctorId)
    {
        $this->doctorId = $doctorId;
        $this->doctor = Doctor::with('user', 'schedules')->findOrFail($doctorId);

        // Generate time slots (08:00 - 17:45)
        $this->generateTimeSlots();

        // Load existing schedules
        $this->loadExistingSchedules();
    }

    private function generateTimeSlots()
    {
        $slots = [];
        $hours = [];
        $start = \Carbon\Carbon::createFromTime(8, 0);
        $end = \Carbon\Carbon::createFromTime(18, 0);

        while ($start->lt($end)) {
            $slotStart = $start->format('H:i');
            $slotEnd = $start->copy()->addMinutes(15)->format('H:i');
            $slots[] = [
                'start' => $slotStart,
                'end' => $slotEnd,
                'label' => $slotStart . ' - ' . $slotEnd,
                'hour' => $start->copy()->minute(0)->format('H:i:s'),
            ];

            if ($start->minute === 0) {
                $hours[] = $start->format('H:i:s');
            }

            $start->addMinutes(15);
        }

        $this->timeSlots = $slots;
        $this->hours = $hours;

        // Initialize all slots to false
        foreach (array_keys($this->days) as $day) {
            foreach ($slots as $slot) {
                $this->selectedSlots[$day][$slot['start']] = false;
            }
        }
    }

    private function loadExistingSchedules()
    {
        foreach ($this->doctor->schedules as $schedule) {
            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
            $day = $schedule->day_of_week;

            if (isset($this->selectedSlots[$day][$startTime])) {
                $this->selectedSlots[$day][$startTime] = true;
            }
        }
    }

    public function toggleSlot($day, $time)
    {
        $this->selectedSlots[$day][$time] = !$this->selectedSlots[$day][$time];
    }

    public function toggleAllInHour($hour, $day)
    {
        // Get all slots that belong to this hour
        $hourSlots = array_filter($this->timeSlots, function ($slot) use ($hour) {
            return $slot['hour'] === $hour;
        });

        // Check if all are currently selected
        $allSelected = true;
        foreach ($hourSlots as $slot) {
            if (!($this->selectedSlots[$day][$slot['start']] ?? false)) {
                $allSelected = false;
                break;
            }
        }

        // Toggle all
        foreach ($hourSlots as $slot) {
            $this->selectedSlots[$day][$slot['start']] = !$allSelected;
        }
    }

    public function saveSchedules()
    {
        // Delete existing schedules for this doctor
        DoctorSchedule::where('doctor_id', $this->doctorId)->delete();

        // Insert selected slots
        $schedulesToInsert = [];
        foreach ($this->selectedSlots as $day => $slots) {
            foreach ($slots as $time => $selected) {
                if ($selected) {
                    $start = \Carbon\Carbon::createFromFormat('H:i', $time);
                    $schedulesToInsert[] = [
                        'doctor_id' => $this->doctorId,
                        'day_of_week' => $day,
                        'start_time' => $time . ':00',
                        'end_time' => $start->addMinutes(15)->format('H:i:s'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($schedulesToInsert)) {
            DoctorSchedule::insert($schedulesToInsert);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Horario guardado!',
            'text' => 'El horario del doctor se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.admin.doctors.schedules', $this->doctorId);
    }

    public function render()
    {
        return view('livewire.admin.schedule-manager');
    }
}
