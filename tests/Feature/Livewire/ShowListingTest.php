<?php

use App\Actions\Audit\RecordAudit;
use App\Livewire\ShowListing;
use App\Models\Listing;
use App\Models\User;
use Livewire\Livewire;

it('shows a published listing', function () {
    $listing = Listing::factory()->publishedInWindow()->create();

    $this->get(route('listings.show', $listing))
        ->assertOk()
        ->assertSee($listing->title)
        ->assertDontSee($listing->seller->contact_email, false);
});

it('forbids viewing unpublished listings', function () {
    $listing = Listing::factory()->draft()->create();

    $this->get(route('listings.show', $listing))
        ->assertForbidden();
});

it('does not expose seller contact in page source before reveal', function () {
    $listing = Listing::factory()->publishedInWindow()->create([
        'description' => 'Unique listing description for test.',
    ]);

    $response = $this->get(route('listings.show', $listing));

    $response->assertOk();
    expect($response->getContent())->not->toContain($listing->seller->contact_email);
    expect($response->getContent())->not->toContain($listing->seller->contact_phone);
});

it('forbids guests from revealing contact', function () {
    $listing = Listing::factory()->publishedInWindow()->create();

    Livewire::test(ShowListing::class, ['listing' => $listing])
        ->call('revealContact')
        ->assertForbidden();
});

it('forbids owners from revealing their own contact', function () {
    $listing = Listing::factory()->publishedInWindow()->create();

    Livewire::actingAs($listing->seller->user)
        ->test(ShowListing::class, ['listing' => $listing])
        ->call('revealContact')
        ->assertForbidden();
});

it('reveals contact for authenticated buyers and dispatches an audit job', function () {
    RecordAudit::fake();

    $listing = Listing::factory()->publishedInWindow()->create();
    $buyer = User::factory()->create();
    $email = $listing->seller()->value('contact_email');
    $phone = $listing->seller()->value('contact_phone');

    Livewire::actingAs($buyer)
        ->test(ShowListing::class, ['listing' => $listing])
        ->call('revealContact')
        ->assertSet('contactRevealed', true)
        ->assertSee($email)
        ->assertSee($phone);

    RecordAudit::assertPushed();
});
