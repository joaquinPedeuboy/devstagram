<?php

namespace App\Livewire;

use Livewire\Component;

class LikePost extends Component
{

    public $post;
    public $isLiked;
    public $likes;

    // Similar a un constructor, se manda a llamar cuando dan like
    public function mount($post)
    {
        $this->isLiked = $post->checkLike(auth()->user());
        $this->likes = $post->likes->count();
    }

    // Funcion de like o dislike
    public function like() 
    {
        if($this->post->checkLike(auth()->user())) {
            $this->post->likes()->where('post_id', $this->post->id)->delete();
            // Cambia en automatico el like
            $this->isLiked = false;
            // Like disminuye
            $this->likes--;
        } else {
            $this->post->likes()->create([
                'user_id' => auth()->user()->id
            ]);
            // Cambia en automatico el like
            $this->isLiked = true;
            // Like aumenta
            $this->likes++;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
