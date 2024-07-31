<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;

class Edit extends Component
{
    public $userId,$role, $name, $email;

    public function mount(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->role = $user->role;
        $this->email = $user->email;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'role' => 'required'. $this->userId,
        ]);

        $user = User::find($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}