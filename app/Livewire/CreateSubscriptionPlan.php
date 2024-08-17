<?php

namespace App\Livewire;

use Livewire\Component;
use Razorpay\Api\Api;

class CreateSubscriptionPlan extends Component
{
    public $name;
    public $amount;
    public $interval;

    public function createPlan()
    {
        $this->validate([
            'name' => 'required|string',
            'amount' => 'required|numeric',
            'interval' => 'required|string',
        ]);

        $api = app(Api::class);

        try {
            $plan = $api->plan->create([
                'period' => $this->interval,
                'interval' => 1,
                'item' => [
                    'name' => $this->name,
                    'amount' => $this->amount * 100, // Razorpay expects amount in paise
                    'currency' => 'INR',
                ],
            ]);

            session()->flash('message', 'Subscription plan created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-subscription-plan');
    }
}
