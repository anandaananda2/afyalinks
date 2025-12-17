<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PatientProfile;
use App\Models\DoctorProfile;
use App\Models\DoctorAvailability;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\IncidentReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@afyalinks.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+254700000001',
            'is_active' => true,
        ]);

        // Create Health Worker
        $healthWorker = User::create([
            'name' => 'Jane Health Worker',
            'email' => 'healthworker@afyalinks.com',
            'password' => Hash::make('password'),
            'role' => 'health_worker',
            'phone' => '+254700000002',
            'is_active' => true,
        ]);

        // Create Doctors
        $doctors = [
            [
                'name' => 'Dr. John Kamau',
                'email' => 'john.kamau@afyalinks.com',
                'specialization' => 'General Practice',
                'license' => 'MP-12345',
                'fee' => 1000,
            ],
            [
                'name' => 'Dr. Sarah Wanjiku',
                'email' => 'sarah.wanjiku@afyalinks.com',
                'specialization' => 'Pediatrics',
                'license' => 'MP-12346',
                'fee' => 1500,
            ],
            [
                'name' => 'Dr. Michael Ochieng',
                'email' => 'michael.ochieng@afyalinks.com',
                'specialization' => 'Cardiology',
                'license' => 'MP-12347',
                'fee' => 2000,
            ],
        ];

        foreach ($doctors as $index => $doctorData) {
            $doctor = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '+25470000000' . (3 + $index),
                'is_active' => true,
            ]);

            DoctorProfile::create([
                'user_id' => $doctor->id,
                'specialization' => $doctorData['specialization'],
                'license_number' => $doctorData['license'],
                'qualifications' => 'MBBS, MD',
                'bio' => 'Experienced medical professional with over 10 years in ' . $doctorData['specialization'],
                'consultation_fee' => $doctorData['fee'],
                'consultation_duration' => 30,
                'work_start_time' => '08:00:00',
                'work_end_time' => '17:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
                'working_days' => [1, 2, 3, 4, 5], // Monday to Friday
                'is_available' => true,
            ]);

            // Create availability for next 14 days
            for ($i = 0; $i < 14; $i++) {
                $date = now()->addDays($i);
                if (in_array($date->dayOfWeek, [1, 2, 3, 4, 5])) { // Weekdays only
                    DoctorAvailability::create([
                        'doctor_id' => $doctor->id,
                        'date' => $date->toDateString(),
                        'start_time' => '08:00:00',
                        'end_time' => '17:00:00',
                        'is_available' => true,
                        'type' => 'regular',
                    ]);
                }
            }
        }

        // Create Patients
        $patients = [
            ['name' => 'Alice Muthoni', 'email' => 'alice@example.com', 'dob' => '1990-05-15', 'gender' => 'female'],
            ['name' => 'Bob Kiprop', 'email' => 'bob@example.com', 'dob' => '1985-08-22', 'gender' => 'male'],
            ['name' => 'Carol Akinyi', 'email' => 'carol@example.com', 'dob' => '1995-03-10', 'gender' => 'female'],
            ['name' => 'David Omondi', 'email' => 'david@example.com', 'dob' => '1988-12-05', 'gender' => 'male'],
            ['name' => 'Emma Njeri', 'email' => 'emma@example.com', 'dob' => '1992-07-18', 'gender' => 'female'],
        ];

        foreach ($patients as $index => $patientData) {
            $patient = User::create([
                'name' => $patientData['name'],
                'email' => $patientData['email'],
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => '+25471000000' . $index,
                'is_active' => true,
            ]);

            PatientProfile::create([
                'user_id' => $patient->id,
                'date_of_birth' => $patientData['dob'],
                'gender' => $patientData['gender'],
                'blood_group' => ['A+', 'B+', 'O+', 'AB+'][array_rand(['A+', 'B+', 'O+', 'AB+'])],
                'address' => 'Nairobi, Kenya',
                'allergies' => $index % 2 == 0 ? 'Penicillin' : null,
                'chronic_conditions' => $index % 3 == 0 ? 'Hypertension' : null,
                'emergency_contact_name' => 'Emergency Contact ' . $index,
                'emergency_contact_phone' => '+25472000000' . $index,
                'emergency_contact_relationship' => 'Spouse',
            ]);
        }

        // Create Sample Appointments
        $allDoctors = User::where('role', 'doctor')->get();
        $allPatients = User::where('role', 'patient')->get();

        // Past completed appointment
        $pastAppointment = Appointment::create([
            'patient_id' => $allPatients[0]->id,
            'doctor_id' => $allDoctors[0]->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '10:00:00',
            'duration' => 30,
            'type' => 'consultation',
            'status' => 'completed',
            'consultation_fee' => 1000,
            'payment_status' => 'paid',
            'reason' => 'Regular checkup',
            'notes' => 'Patient is healthy. Recommended regular exercise.',
            'completed_at' => now()->subDays(5),
        ]);

        Payment::create([
            'appointment_id' => $pastAppointment->id,
            'user_id' => $pastAppointment->patient_id,
            'amount' => 1000,
            'payment_method' => 'mobile_money',
            'status' => 'completed',
            'paid_at' => now()->subDays(5),
        ]);

        // Today's confirmed appointment
        Appointment::create([
            'patient_id' => $allPatients[1]->id,
            'doctor_id' => $allDoctors[1]->id,
            'appointment_date' => now()->toDateString(),
            'appointment_time' => '14:00:00',
            'duration' => 30,
            'type' => 'consultation',
            'status' => 'confirmed',
            'consultation_fee' => 1500,
            'payment_status' => 'paid',
            'reason' => 'Child vaccination',
        ]);

        // Future pending appointment
        Appointment::create([
            'patient_id' => $allPatients[2]->id,
            'doctor_id' => $allDoctors[2]->id,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'appointment_time' => '11:00:00',
            'duration' => 30,
            'type' => 'follow_up',
            'status' => 'pending',
            'consultation_fee' => 2000,
            'payment_status' => 'pending',
            'reason' => 'Follow-up on heart condition',
        ]);

        // Create Sample Incident Reports
        IncidentReport::create([
            'reported_by' => $healthWorker->id,
            'category' => 'drugs',
            'severity' => 'critical',
            'item_name' => 'Paracetamol 500mg',
            'description' => 'Stock completely depleted. Last 50 tablets used today. Urgent restocking required.',
            'current_stock' => 0,
            'minimum_required' => 500,
            'status' => 'reported',
        ]);

        IncidentReport::create([
            'reported_by' => $healthWorker->id,
            'category' => 'equipment',
            'severity' => 'high',
            'item_name' => 'Blood Pressure Monitor',
            'description' => 'Main BP monitor malfunctioning. Displaying inconsistent readings.',
            'status' => 'acknowledged',
        ]);

        IncidentReport::create([
            'reported_by' => $healthWorker->id,
            'category' => 'supplies',
            'severity' => 'medium',
            'item_name' => 'Surgical Gloves',
            'description' => 'Running low on medium-sized surgical gloves.',
            'current_stock' => 50,
            'minimum_required' => 200,
            'status' => 'in_progress',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Credentials:');
        $this->command->info('Admin: admin@afyalinks.com / password');
        $this->command->info('Health Worker: healthworker@afyalinks.com / password');
        $this->command->info('Doctor 1: john.kamau@afyalinks.com / password');
        $this->command->info('Doctor 2: sarah.wanjiku@afyalinks.com / password');
        $this->command->info('Doctor 3: michael.ochieng@afyalinks.com / password');
        $this->command->info('Patient 1: alice@example.com / password');
        $this->command->info('Patient 2: bob@example.com / password');
        $this->command->info('Patient 3: carol@example.com / password');
    }
}