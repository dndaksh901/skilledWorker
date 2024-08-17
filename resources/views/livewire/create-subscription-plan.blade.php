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

    <form wire:submit.prevent="createPlan">
        <div class="form-group">
            <label for="name">Plan Name</label>
            <input type="text" id="name" wire:model="name" class="form-control">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="amount">Amount (INR)</label>
            <input type="number" id="amount" wire:model="amount" class="form-control">
            @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="interval">Interval</label>
            <select id="interval" wire:model="interval" class="form-control">
                <option value="">Select Interval</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
            @error('interval') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Plan</button>
    </form>
</div>
