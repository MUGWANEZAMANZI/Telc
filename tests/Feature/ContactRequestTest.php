<?php

use App\Livewire\ExamDashboard;
use App\Models\ContactRequest;
use App\Models\User;
use Livewire\Livewire;

test('users can submit contact form successfully', function () {
    expect(ContactRequest::count())->toBe(0);

    Livewire::test('contact-form')
        ->set('name', 'John Doe')
        ->set('organization', 'Acme Inc')
        ->set('email', 'john@acme.com')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('successMessage', 'Vielen Dank! Ihre Kontaktanfrage wurde erfolgreich übermittelt.');

    expect(ContactRequest::count())->toBe(1);
    $req = ContactRequest::first();
    expect($req->name)->toBe('John Doe');
    expect($req->organization)->toBe('Acme Inc');
    expect($req->email)->toBe('john@acme.com');
    expect($req->status)->toBe('not_responded');
});

test('admin can toggle contact request response status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $req = ContactRequest::create([
        'name' => 'Jane Smith',
        'organization' => 'Corp Inc',
        'email' => 'jane@corp.com',
        'password' => '',
        'status' => 'not_responded',
    ]);

    Livewire::test(ExamDashboard::class)
        ->call('toggleContactStatus', $req->id, 'responded');

    expect($req->refresh()->status)->toBe('responded');

    Livewire::test(ExamDashboard::class)
        ->call('toggleContactStatus', $req->id, 'not_responded');

    expect($req->refresh()->status)->toBe('not_responded');
});
