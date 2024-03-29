<?php

namespace App\Http\Livewire\Companies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class UploadPhoto extends Component
{
    use WithFileUploads;

    public User $user;

    public $photo;

    use Actions;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function save(): void
    {

        $this->validate([
            'photo' => 'image',
        ]);

        $filename = str_replace(' ', '', date('YmdHi').$this->photo->getClientOriginalName());

        if ($this->user->company->image()->value('path') != null) {
            Storage::delete($this->user->company->image()->value('path'));
        }

        $imagePath = $this->photo->storeAs('public/images/company', $filename);
        $this->user->company->image()->updateOrCreate(['images_id' => $this->user->company->id], ['path' => $imagePath]);
        $this->notifications();
    }

    public function notifications()
    {

        $this->notification()->success(

            $title = 'Parabéns!',
            $description = 'Imagem Adicionada/e ou Atualizada com sucesso!'
        );

    }
}
