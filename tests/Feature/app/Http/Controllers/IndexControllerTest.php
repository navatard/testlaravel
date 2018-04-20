<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Models\Member;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        exec('php artisan migrate:refresh');
    }

    /**
     * Vérifie que la page index retourne un code 200
     *
     * 2 Points
     */
    public function test_200()
    {
        // Arrange
        // Act
        $response = $this->get('/');
        // Assert
        $response->assertStatus(200);
    }

    /**
     * Vérifie que la redirection est bien / après l'ajout d'un email
     * Doit retourner le message suivant index.success
     *
     * 2 Points
     */
    public function test_Redirect_Add_Member()
    {
        // Arrange
        $email = 'john.doe@domain.tld';
        // Act
        $response = $this->post('/lists/create', [
            Member::EMAIL => $email
        ]);
        // Assert
        $response->assertRedirect('/');
        $response->assertSessionHas('alert', [
            'message' => 'success_message',
            'type' => 'success'
        ]);
    }
    /**
     * Vérifie que l'ajout d'un email (john.doe@domain.tld) existant redirige vers /
     * Doit retourner les message suivant index.success
     * Il ne doit y avoir q'un email en base de donnée
     *
     * 2 Points
     */
    public function test_Already_Member_Exist()
    {
        // Arrange
        $member = factory(Member::class)->create([
            Member::EMAIL => 'john.doe@domain.tld' // => First Member
        ]);

        // Act
        $response = $this->post('/lists/create', [
            Member::EMAIL => 'john.doe@domain.tld'
        ]);

        // Assert
        $response->assertRedirect('/');
        //$response->assertStatus(200);
        $response->assertSessionHas('alert', [
            'message' => 'success_message',
            'type' => 'success'
        ]);
    }

    /**
     * Vérifie que l'ajout d'un email vide retourne une erreur 500
     *
     * 2 Points
     */
    public function test_500()
    {
        // Arrange
        $email = '';
        // Act
        $response = $this->post('/lists/create', [
            Member::EMAIL => $email
        ]);
        // Assert
        $response->assertStatus(500);
    }
}
