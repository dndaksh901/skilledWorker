<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div>
        <button wire:click="createSubscription('plan_monthly_id')" class="btn btn-primary">Subscribe Monthly ($49)</button>
        <button wire:click="createSubscription('plan_yearly_id')" class="btn btn-primary">Subscribe Yearly ($199)</button>
    </div>

    <div>
        <button wire:click="cancelSubscription" class="btn btn-danger">Cancel Subscription</button>
    </div>

    <div>
        <button wire:click="upgradeSubscription('new_plan_id')" class="btn btn-warning">Upgrade Subscription</button>
    </div>

    @if ($isExpired)
        <div class="alert alert-danger">
            Your subscription has expired. Please renew to continue using our services.
        </div>
    @endif
</div>
