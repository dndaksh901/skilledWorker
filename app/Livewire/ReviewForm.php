<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\UserReview;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class ReviewForm extends Component
{
    public $profileId;
    public $vendorId;
    public $reviewText;
    public $rating = 1;
    public $reviews;
    public $userReview;

    protected $rules = [
        'reviewText' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
    ];

    public function mount($profile)
    {
        $this->profileId = $profile->id;
        $this->vendorId = $profile->vendor_id;
        $this->loadReviews();

         // Check if the user has already reviewed this vendor
         $this->userReview = Review::where('profile_id', $this->profileId)
         ->where('user_id', auth()->id()) // Assuming user is authenticated
         ->first();

     if ($this->userReview) {
         $this->reviewText = $this->userReview->review;
         $this->rating = $this->userReview->rating;
     }
    }

    public function loadReviews()
    {
        $this->reviews = Review::where('profile_id', $this->profileId)->get();
    }

    public function submitReview()
    {
        $this->validate();
        $this->userReview = Review::where('profile_id', $this->profileId)
        ->where('user_id', auth()->id()) // Assuming user is authenticated
        ->first();

        if ($this->userReview) {
            // Update existing review
            $this->userReview->update([
                'review' => $this->reviewText,
                'rating' => $this->rating,
            ]);
        } else {
            // Create a new review
            Review::create([
                'profile_id' => $this->profileId,
                'vendor_id' => $this->vendorId,
                'user_id' => auth()->id(),
                'review' => $this->reviewText,
                'rating' => $this->rating,
            ]);
        }

        $this->reset('reviewText', 'rating');
        $this->userReview='';
        $this->dispatch('review-change');
    }

    public function deleteReview($reviewId)
{
    try {
        // Fetch the review that belongs to the authenticated user
        $review = Review::where('id', $reviewId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$review) {
            // If no review found, throw an exception
            throw new \Exception('Review not found or you do not have permission to delete this review.');
        }

        // Delete the associated user review if exists
        UserReview::where([
            'review_id' => $reviewId,
            'user_id' => auth()->id(),
        ])->delete();

        // Delete the review
        $review->delete();

        // Dispatch the event to update the reviews list
        $this->dispatch('review-change');

    } catch (\Exception $e) {
        // Log the error or handle it as necessary
        // For now, we'll just return a session flash message
        session()->flash('error', $e->getMessage());
    }
}

    public function likeReview($reviewId)
    {
        $review = Review::find($reviewId);
        $userReview = UserReview::where('review_id', $reviewId)
            ->where('user_id', auth()->id())
            ->first();

        if ($userReview) {
            if ($userReview->like) {
                // User has already liked the review
                return;
            } else {
                // User disliked before, remove dislike and add like
                $review->decrement('dislikes');
                $review->increment('likes');
                $userReview->update(['like' => true]);
            }
        } else {
            // Create a new like
            $review->increment('likes');
            UserReview::create([
                'review_id' => $reviewId,
                'user_id' => auth()->id(),
                'like' => true,
            ]);
        }

        $this->dispatch('review-change');
    }

    public function dislikeReview($reviewId)
    {
        $review = Review::find($reviewId);
        $userReview = UserReview::where('review_id', $reviewId)
            ->where('user_id', auth()->id())
            ->first();

        if ($userReview) {
            if (!$userReview->like) {
                // User has already disliked the review
                return;
            } else {
                // User liked before, remove like and add dislike
                $review->decrement('likes');
                $review->increment('dislikes');
                $userReview->update(['like' => false]);
            }
        } else {
            // Create a new dislike
            $review->increment('dislikes');
            UserReview::create([
                'review_id' => $reviewId,
                'user_id' => auth()->id(),
                'like' => false,
            ]);
        }

        $this->dispatch('review-change');
    }

    #[On('review-change')]
    public function updateReviews()
    {
        $this->loadReviews();
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}

?>
