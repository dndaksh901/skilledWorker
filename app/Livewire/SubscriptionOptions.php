<?php

namespace App\Livewire;

use Livewire\Component;
use Razorpay\Api\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SubscriptionOptions extends Component
{
    public $hasSubscription;

    public function mount()
    {
        // Check if the user is authenticated and has an active subscription
        if (Auth::check()) {
            $user = Auth::user();
            $this->hasSubscription = $user->subscription()->active()->exists(); // Adjust the query based on your subscription model
        } else {
            $this->hasSubscription = false;
        }
    }

    public function subscribe($planId)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            session()->flash('error', 'Please login to subscribe.');
            return redirect()->route('login');
        }

        $api = app(Api::class);
        $user = Auth::user();

        try {
            $subscription = $api->subscription->create([
                'plan_id' => $planId,
                'total_count' => $planId == 'plan_monthly_id' ? 12 : 1, // 12 months for monthly plan, 1 year for yearly plan
                'customer_notify' => 1,
                'quantity' => 1,
                'start_at' => Carbon::now()->addMonth()->timestamp, // First month free
                'expire_by' => $planId == 'plan_monthly_id' ? Carbon::now()->addYear()->timestamp : Carbon::now()->addYear()->timestamp,
                'notes' => [
                    'user_id' => $user->id,
                ],
            ]);

            // Save subscription details to the user profile
            $user->subscription_id = $subscription->id;
            $user->subscription_status = 'active';
            $user->subscription_end = Carbon::createFromTimestamp($subscription->current_end)->toDateTimeString();
            $user->save();

            session()->flash('message', 'Subscription created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.subscription-options');
    }
}
