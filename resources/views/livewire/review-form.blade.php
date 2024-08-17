<div class="container mt-5">

     <!-- Display error messages -->
     @if (session()->has('error'))
     <div class="alert alert-danger">
         {{ session('error') }}
     </div>
     @endif
    <!-- Review Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form wire:submit.prevent="submitReview">
                <div class="mb-3">
                    <textarea
                        wire:model="reviewText"
                        placeholder="Write your review..."
                        class="form-control"
                        rows="4"
                    ></textarea>
                    @error('reviewText') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="reviewbox-rating mb-4">
                    <p><span>Rating</span></p>
                    <div class="col">
                        <div class="rate">
                            @for ($i = 5; $i >= 1; $i--)
                                <input
                                    type="radio"
                                    id="star{{ $i }}"
                                    wire:model="rating"
                                    value="{{ $i }}"
                                    class="rate"
                                />
                                <label
                                    for="star{{ $i }}"
                                    title="{{ $i }} stars"
                                    class="cursor-pointer"
                                >
                                    {{ $i }} stars
                                </label>
                            @endfor
                        </div>
                        @error('rating') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    @if ($userReview)
                        Update Review
                    @else
                        Submit Review
                    @endif
                </button>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <div>
        @foreach($reviews as $review)
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-text font-bold"><i class="fa-solid fa-user"></i> {{ $review->user->name ?? 'Guest' }}</p>
                    <p class="card-text">{{ $review->review }}</p>

                    <!-- Show Created At Date in Human-Readable Format -->
                    <p class="text-muted">Rating:
                        @for ($i = 5; $i >= 1; $i--)
                            <i class="fa-{{ $review->rating >= $i ? 'solid' : 'regular' }} fa-star" class="cursor-pointer"></i>
                        @endfor
                    </p>

                    <!-- Display Time Since Review Was Created -->
                    <p class="text-muted">Posted: {{ $review->created_at->diffForHumans() }}</p>

                    <!-- Like/Dislike Buttons -->
                    <button
                        wire:click="likeReview({{ $review->id }})"
                        class="btn btn-success me-2"
                        {{ $review->userLiked ? 'disabled' : '' }}
                    >
                        <i class="fa-solid fa-thumbs-up"></i> ({{ $review->likes }})
                    </button>
                    <button
                        wire:click="dislikeReview({{ $review->id }})"
                        class="btn btn-danger"
                        {{ $review->userDisliked ? 'disabled' : '' }}
                    >
                        <i class="fa-solid fa-thumbs-down"></i> ({{ $review->dislikes }})
                    </button>

                    <!-- Delete Button -->
                    @if ($review->user_id === auth()->id())
                        <button
                            wire:click="deleteReview({{ $review->id }})"
                            class="btn btn-outline-danger mt-2"
                        >
                        <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
