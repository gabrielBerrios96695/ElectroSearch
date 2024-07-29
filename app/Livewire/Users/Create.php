<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Create extends Component
{
    public $name, $email, $password;

    public function create()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
