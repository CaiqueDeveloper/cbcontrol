<?php

namespace App\Http\Livewire\Networks;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public ?bool $showModal = false;

    public function render(): View
    {
        return view('livewire.networks.show');
    }
}
