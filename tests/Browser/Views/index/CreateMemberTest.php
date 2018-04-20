<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\Member;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateMemberTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $url = 'http://localhost:8000';


    public function setUp()
    {
        parent::setUp();
        exec('php artisan migrate:refresh');
    }
    /**
     * Un visiteur arrive sur la page /
     * - Il saisie son email
     * - Il soumet le formulaire
     * - Un message l'informe qu'il va de recevoir un email "index.success"
     *
     * 2 Points
     */
    
    public function testAddMail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url . '/')
                ->assertMissing('.alert')
                ->value('input[name=email]', 'john.doe@domain.tld')
                ->press(__('members.index.send'))
                ->assertPathIs('/')
                ->assertSee(__('success_message'))
                ->assertVisible('.alert-success');
        });
    }

    /**
     * Un visiteur arrive sur la page /
     * - Il saisie son email (déjà existant en base de donnée)
     * - Il soumet le formulaire
     * - Un message l'informe qu'il va de recevoir un email "index.success"
     *
     * 2 Points
     */
    
    public function testAddSameMail()
    {
        
        factory(Member::class)->create([
            Member::EMAIL => 'john.doe@domain.tld' // => First Member
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit($this->url . '/')
                ->assertMissing('.alert')
                ->value('input[name=email]', 'john.doe@domain.tld')
                ->press(__('members.index.send'))
                ->assertPathIs('/')
                ->assertStatus(500)
                ->assertSee(__('success_message'))
                ->assertVisible('.alert-success');
        });
    }

    /**
     * Un visiteur arrive sur la page /
     * - Il ne saisi pas d'email
     * - Il soumet le formulaire
     * - Un message l'informe qu'il doit saisir son email
     *
     * 2 Points
     */
    public function testAddMailEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->url . '/')
                ->assertMissing('.alert')
                ->value('input[name=email]', '')
                ->press(__('members.index.send'))
                ->assertPathIs('/')
                ->assertSee(__('required_fields'))
                ->assertVisible('.alert-warning');
        });
    }
}
