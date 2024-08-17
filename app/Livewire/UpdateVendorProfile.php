<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;

class UpdateVendorProfile extends Component
{
    public $vendor;
    public $name;
    public $email;

    public function mount(User $vendor)
    {
        $this->vendor = $vendor;
        $this->name = $vendor->name;
        $this->email = $vendor->email;
    }

    public function updateProfile()
    {
        if (Carbon::parse($this->vendor->subscription_end)->isPast()) {
            session()->flash('error', 'Subscription expired. Please renew to update your profile.');
            return;
        }

        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $this->vendor->name = $this->name;
        $this->vendor->email = $this->email;
        $this->vendor->save();

        session()->flash('message', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.update-vendor-profile');
    }
}
