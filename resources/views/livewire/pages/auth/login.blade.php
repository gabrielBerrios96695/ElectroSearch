<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Maneja una solicitud de autenticación entrante.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>


    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <!-- Session Status -->
     
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Inicio de Sesión</h2>

        <form wire:submit.prevent="login" class="space-y-6">
            <!-- Correo Electrónico -->
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input wire:model="form.email" id="email" class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" type="email" name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Contraseña -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input wire:model="form.password" id="password" class="block w-full border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

           

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-700" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif

                <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white">
                    {{ __('Iniciar Sesión') }}
                </x-primary-button>
            </div>

           
        </form>
    </div>

