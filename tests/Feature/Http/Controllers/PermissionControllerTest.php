<?php

//Profiles Tests

use function Pest\Laravel\{get,};

it('verificar se existe a rota permissions', function(){

    get('/app/permissions')
    ->assertOk()
    ->assertViewIs('permissions.index');
});
todo('verificar se somente usuários autenticado podem acessar essa rota');
todo('verificar se usuários não autenticados estão sendo redirecionando para pagina de login');
todo('verificar se na pagina tem o componente para criação de um perfil');
todo('verificar se ao clicar no componente o modal para cadastro de perfil está sendo exibido corretamente');
todo('verificar se os dados estão devidamente preenchidos');
todo('verificar se foi realizado o cadastro de um perfil com sucesso e se o evento foi desparado corretamente');

//Permission "Modules" Tests

