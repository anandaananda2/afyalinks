<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            @if($errors->has('payment'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <p class="text-sm text-red-700">{{ $errors->first('payment') }}</p>
            </div>
            @endif

            <!-- Appointment Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Appointment Summary</h3>
                    
                    <div class="flex items-start space-x-4 mb-4">
                        @if($appointment->doctor->doctorProfile->profile_photo)
                        <img src="{{ asset('storage/' . $appointment->doctor->doctorProfile->profile_photo) }}" alt="{{ $appointment->doctor->name }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($appointment->doctor->name, 0, 1) }}
                        </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-lg">Dr. {{ $appointment->doctor->name }}</h4>
                            <p class="text-gray-600">{{ $appointment->doctor->doctorProfile->specialization }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 pt-4 border-t">
                        <div>
                            <label class="block text-sm text-gray-500">Appointment Date</label>
                            <p class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Time</label>
                            <p class="font-medium">{{ $appointment->appointment_time->format('h:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Duration</label>
                            <p class="font-medium">{{ $appointment->duration }} minutes</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Type</label>
                            <p class="font-medium">{{ ucfirst($appointment->type) }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Total Amount:</span>
                            <span class="text-3xl font-bold text-green-600">KES {{ number_format($appointment->consultation_fee, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Select Payment Method</h3>

                    <form method="POST" action="{{ route('patient.payments.process', $appointment) }}">
                        @csrf

                        <!-- Payment Method Selection -->
                        <div class="space-y-4 mb-6">
                            
                            <!-- Mobile Money -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="payment_method" value="mobile_money" class="mt-1 mr-3" required>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-lg">Mobile Money (M-Pesa)</span>
                                        <span class="text-2xl">📱</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pay using your mobile money wallet</p>
                                    
                                    <div class="mt-3 mobile-money-fields hidden">
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                        <input type="tel" name="phone_number" id="phone_number" placeholder="+254712345678" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @error('phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </label>

                            <!-- Card Payment -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="payment_method" value="card" class="mt-1 mr-3" required>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-lg">Credit/Debit Card</span>
                                        <span class="text-2xl">💳</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pay securely with your card</p>
                                </div>
                            </label>

                            <!-- Bank Transfer -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 mr-3" required>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-lg">Bank Transfer</span>
                                        <span class="text-2xl">🏦</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Transfer directly from your bank</p>
                                    <p class="text-xs text-yellow-600 mt-1">⚠️ Requires manual verification</p>
                                </div>
                            </label>

                            <!-- Cash -->
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="payment_method" value="cash" class="mt-1 mr-3" required>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-lg">Cash</span>
                                        <span class="text-2xl">💵</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Pay in person at the facility</p>
                                    <p class="text-xs text-yellow-600 mt-1">⚠️ Payment must be verified by staff</p>
                                </div>
                            </label>

                        </div>

                        @error('payment_method')
                            <p class="mt-1 mb-4 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Terms and Conditions -->
                        <div class="mb-6">
                            <label class="inline-flex items-start">
                                <input type="checkbox" required class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                                <span class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">terms and conditions</a> and understand that cancellations must be made at least 2 hours before the appointment.
                                </span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('patient.appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700">
                                Proceed to Payment
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        // Show/hide phone number field for mobile money
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.mobile-money-fields').forEach(field => {
                    field.classList.add('hidden');
                });
                
                if (this.value === 'mobile_money') {
                    this.closest('label').querySelector('.mobile-money-fields').classList.remove('hidden');
                }
            });
        });
    </script>
</x-app-layout>