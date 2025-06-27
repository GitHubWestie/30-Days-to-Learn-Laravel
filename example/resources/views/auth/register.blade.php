<x-layout>
    <x-slot:heading>📋 Register</x-slot:heading>
    <form action="/register" method="POST">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base/7 font-semibold text-gray-900">Register for an account</h2>
                <p class="mt-1 text-sm/6 text-gray-600">We just need a few details to create your account.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    {{-- First name --}}
                    <x-form-field>
                        <x-form-label for="first_name">First name</x-form-label>
                        <div class="mt-2">
                            <x-form-input type="text" name="first_name" id="first_name" required></x-form-input>
                            <x-form-error name="first_name" />
                        </div>  
                    </x-form-field>
                    {{-- Last name --}}
                    <x-form-field>
                        <x-form-label for="last_name">Last name</x-form-label>
                        <div class="mt-2">
                            <x-form-input type="text" name="last_name" id="last_name" required></x-form-input>
                            <x-form-error name="last_name" />
                        </div>  
                    </x-form-field>
                    {{-- Email --}}
                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input type="email" name="email" id="email" placeholder="john@example.com" required></x-form-input>
                            <x-form-error name="email" />
                        </div>  
                    </x-form-field>
                    {{-- Password --}}
                    <x-form-field>
                        <x-form-label for="password">Password</x-form-label>
                        <div class="mt-2">
                            <x-form-input type="password" name="password" id="password" placeholder="Password" required></x-form-input>
                            <x-form-error name="password" />
                        </div>  
                    </x-form-field>
                    {{-- Confirm password --}}
                    <x-form-field>
                        <x-form-label for="password_confirmation">Confirm password</x-form-label>
                        <div class="mt-2">
                            <x-form-input type="password_confirmation" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" required></x-form-input>
                            <x-form-error name="password_confirmation" />
                        </div>  
                    </x-form-field>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
            <x-form-button>Register</x-form-button>
        </div>
    </form>

</x-layout>