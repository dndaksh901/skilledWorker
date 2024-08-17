@if (!$hasSubscription)
    <div class="container my-4">
        <h2 class="text-center mb-4">Choose Your Subscription Plan</h2>

        <div class="row">
            <!-- Monthly Plan Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center border-primary">
                    <div class="card-header bg-primary text-white">
                        Monthly Plan
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">$49</h3>
                        <p class="card-text">per month</p>
                        <button class="btn btn-primary" wire:click="subscribe('plan_monthly_id')">Subscribe Monthly</button>
                    </div>
                </div>
            </div>

            <!-- Yearly Plan Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">
                        Yearly Plan
                    </div>
                    <div class="card-body">
                        <h3 class="card-title">$199</h3>
                        <p class="card-text">per year</p>
                        <button class="btn btn-success" wire:click="subscribe('plan_yearly_id')">Subscribe Yearly</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
