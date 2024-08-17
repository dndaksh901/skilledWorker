<?php

namespace App\Livewire;

use Livewire\Component;
use Razorpay\Api\Api;
use Carbon\Carbon;
use App\Models\User; // Assuming User model handles vendor profiles

class ManageSubscription extends Component
{
    public $planId;
    public $vendor;

    public function mount(User $vendor)
    {
        $this->vendor = $vendor;
    }

    public function createSubscription($planId)
    {
        $api = app(Api::class);

        try {
            $subscription = $api->subscription->create([
                'plan_id' => $planId,
                'total_count' => 12, // assuming monthly plan
                'customer_notify' => 1,
                'quantity' => 1,
                'start_at' => Carbon::now()->addMonth()->timestamp, // First month free
                'expire_by' => Carbon::now()->addYear()->timestamp, // First year free for yearly plan
                'notes' => [
                    'vendor_id' => $this->vendor->id,
                ],
            ]);

            // Save subscription details to the vendor profile
            $this->vendor->subscription_id = $subscription->id;
            $this->vendor->subscription_status = 'active';
            $this->vendor->subscription_end = Carbon::createFromTimestamp($subscription->current_end)->toDateTimeString();
            $this->vendor->save();

            session()->flash('message', 'Subscription created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancelSubscription()
    {
        $api = app(Api::class);

        try {
            $subscription = $api->subscription->fetch($this->vendor->subscription_id);
            $subscription->cancel(['cancel_at_cycle_end' => 1]);

            $this->vendor->subscription_status = 'cancelled';
            $this->vendor->save();

            session()->flash('message', 'Subscription cancelled successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function upgradeSubscription($newPlanId)
    {
        $api = app(Api::class);

        try {
            $subscription = $api->subscription->fetch($this->vendor->subscription_id);
            $subscription->update(['plan_id' => $newPlanId]);

            session()->flash('message', 'Subscription upgraded successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.manage-subscription', [
            'isExpired' => Carbon::parse($this->vendor->subscription_end)->isPast(),
        ]);
    }
}
