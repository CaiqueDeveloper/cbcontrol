<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;
    public User $user;
    public ?string $name = null;
    public ?string $number_phone = null;
    public ?string $email = null;
    public ?string $birthday = null;
    public ?string $password = null;
    public ?string $password_confirm = null;

    protected $rules = [

        'name' => 'required|min:4|max:150',
        'number_phone' => 'nullable|max:16|unique:users',
        'email' => 'required|email|unique:users',
        'birthday' => 'nullable|date',
        'password' => 'required|min:8|max:16',
        'password_confirm' => 'required|min:8|max:16|same:password'

    ];

    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function render(): View
    {
        return view('livewire.users.create');
    }

    public function create(): void
    {   
        $this->validate();
        
        $data = [
            'name' => $this->name,
            'number_phone' =>  $this->number_phone,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'password' => $this->password,
            'company_id' => $this->user->company_id,
        ];
    
        $this->user->company->users()->create($data);
        $this->notifications();
        $this->reset();
        $this->emitTo(ListUsers::class, 'users::index::created');
        $this->closeModal();   
        
    }
    public function notifications(){

        $this->notification()->success(
            $title = 'Parabéns!',
            $description = 'Usuário Cadastrado com sucesso!'
        ); 
        foreach(Auth::user()->company->users as $user){
            
            $notification = new \MBarlow\Megaphone\Types\General(
                'Cadastro de Usuário!',
                'O usuário(a) '.Auth::user()->name.' cadastrou um usuário na empresa '.$this->user->company->corporate_reason,
                
            );
            $user->notify($notification);
        }
    }
}
