<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RegisterRoles extends Component

{
    public $role;
    public function render()
    {
        return view('livewire.register-roles');
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'role.name' => 'required|alpha|min:3|max:30|unique:roles,name',
        ]);
    }
}
