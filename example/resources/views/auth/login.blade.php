<x-layout>
    <x-slot:heading>👤 Login</x-slot:heading>
    <form action="/login" method="POST">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <h2 class="text-base/7 font-semibold text-gray-900">Register for an account</h2>
                <p class="mt-1 text-sm/6 text-gray-600">We just need a few details to create your account.</p>

                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
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
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
            <x-form-button>Login</x-form-button>
        </div>
    </form>

</x-layout>