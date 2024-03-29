<?php

use App\Http\Livewire\Profiles\Create;
use App\Http\Livewire\Profiles\Delete;
use App\Http\Livewire\Profiles\ListProfiles;
use App\Http\Livewire\Profiles\Toggle;
use App\Http\Livewire\Profiles\Update;
use App\Http\Livewire\Profiles\Users;
use App\Models\Profile;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {

    $this->user = User::factory()->createOne();
});

it('verificar se somente usuários autenticado podem acessar essa rota', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk()
        ->assertViewIs('permissions.index');
});
it('verificar se usuários não autenticados estão sendo redirecionando para pagina de login', function () {

    get('/app/permissions')
        ->assertFound()
        ->assertRedirect('login');
});
it('verificar se na pagina tem o componente para criação de um perfil', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk()
        ->assertSeeLivewire(ListProfiles::class)
        ->assertSeeLivewire(Create::class);
});
it('verificar se ao clicar no componente o modal para cadastro de perfil está sendo exibido corretamente', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->assertSee('Cadastrar Perfil');

});
it('verificar se os dados estão devidamente preenchidos', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->call('create')
        ->assertHasErrors();
});
it('verificar se foi realizado o cadastro de um perfil com sucesso e se o evento foi desparado corretamente', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    $this->assertDatabaseHas('profiles', [
        'id' => 1,
    ]);
});
it('verficar se exite o componete para deletar um perfil na tela', function () {

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk()
        ->assertSeeLivewire(Delete::class);
});

it('verificar se ao clicar no componente para deletar um perfil o modal de alerta está sendo exibido', function () {

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::actingAs($this->user)
        ->test(Delete::class)
        ->toggle('showModal')
        ->assertSee('Tem certeza de que deseja excluir');
});
it('verificar se ao clicar no componente para deletar um perfil o perfil foi deletado com sucesso', function () {

    $profile = Profile::factory()->createOne();

    Livewire::actingAs($this->user)
        ->test(Delete::class, ['profile' => $profile])
        ->toggle('showModal')
        ->call('delete')
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::deleted')
        ->assertDontSee($profile->name);

    $this->assertDatabaseCount('profiles', 0);
});
it('verficar se exite o componete para editar um perfil na tela', function () {

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk()
        ->assertSeeLivewire(Update::class);
});
it('verificar se ao clicar no componente para editar o perfil o modal está sendo exibido corretamente', function () {

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::actingAs($this->user)
        ->test(Update::class)
        ->toggle('showModal')
        ->assertSee('Editar Perfil');

});
it('verificar se os dados estão devidamente preenchidos na edição do perfil', function () {

    $profile = Profile::factory()->createOne();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::test(Update::class, ['profile' => $profile])
        ->toggle('showModal')
        ->call('update')
        ->assertHasErrors();
});
it('verificar se um perfil foi editado com sucesso!', function () {

    $profile = Profile::factory()->createOne();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::test(Update::class, ['profile' => $profile])
        ->toggle('showModal')
        ->set('profile.name', 'Perfil Base 2')
        ->call('update')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::updated');

    $this->assertDatabaseHas('profiles', [
        'name' => 'Perfil Base 2',
    ]);
});
it('verificar se o componente para associar um perfil a uma usuário o componente está em tela', function () {

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk()
        ->assertSeeLivewire(Users::class);
});
it('verificar se o componente para associar um perfil a uma usuário o modal está sendo exibido corretamente', function () {

    $profile = Profile::factory()->createOne();

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::test(Users::class, ['profile' => $profile, 'user' => $this->user])
        ->toggle('showModal')
        ->assertSee('Adicionar perfil');
});
it('verificar se ao clicar no componente toggle o prfil está sendo associado ao usuário corretamente', function () {

    $profile = Profile::factory()->createOne();

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::test(Toggle::class, ['profile' => $profile, 'user' => $this->user])
        ->call('attach')
        ->assertEmittedTo(Users::class, 'users::index::attach');

    $this->assertDatabaseHas('profile_users', [

        'user_id' => $this->user->id,
        'profile_id' => $profile->id,
    ]);
});
it('verificar se ao clicar no componente toggle o perfil está sendo removido do usuário corretamente', function () {

    $profile = Profile::factory()->createOne();

    actingAs($this->user)
        ->get('/app/permissions')
        ->assertOk();

    Livewire::test(Create::class)
        ->toggle('showModal')
        ->set('name', 'Perfil Base')
        ->call('create')
        ->assertHasNoErrors()
        ->assertEmittedTo(ListProfiles::class, 'profiles::index::created');

    Livewire::test(Toggle::class, ['profile' => $profile, 'user' => $this->user])
        ->call('detach')
        ->assertEmittedTo(Users::class, 'users::index::detach');

    $this->assertDatabaseCount('profile_users', 0);
});
