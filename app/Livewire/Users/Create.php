<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Create extends Component
{
    public $name, $first_surname, $second_surname, $email, $phone, $password, $role;

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'first_surname' => 'required|string|max:255',
            'second_surname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
        ]);

        User::create([
            'name' => $this->name,
            'first_surname' => $this->first_surname,
            'second_surname' => $this->second_surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => bcrypt($this->password),
            'role' => $this->role,
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
