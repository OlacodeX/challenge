<?php

it('shows french navigation when locale is french in session', function () {
    $this->withSession(['locale' => 'fr'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Parcourir', false)
        ->assertSee('Se connecter', false);
});

it('sets locale via the locale switch route', function () {
    $this->get(route('locale.switch', 'fr'))
        ->assertRedirect();

    expect(session('locale'))->toBe('fr');
});

it('shows french login page when locale is french', function () {
    $this->withSession(['locale' => 'fr'])
        ->get(route('login'))
        ->assertOk()
        ->assertSee('Bon retour', false)
        ->assertSee('Connectez-vous à votre compte', false);
});

it('shows french marketplace hero when locale is french', function () {
    $this->withSession(['locale' => 'fr'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Trouvez des actifs professionnels', false);
});
