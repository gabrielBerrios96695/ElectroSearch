<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Edit extends Component
{
    public $userId, $name, $first_surname, $second_surname, $email, $phone, $role;

    public function mount(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->first_surname = $user->first_surname;
        $this->second_surname = $user->second_surname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'first_surname' => 'required|string|max:255',
            'second_surname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string',
        ]);

        $user = User::find($this->userId);
        $user->update([
            'name' => $this->name,
            'first_surname' => $this->first_surname,
            'second_surname' => $this->second_surname,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
