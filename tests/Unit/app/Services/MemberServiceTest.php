<?php

namespace Tests\Unit\app\Services;

use Tests\TestCase;
use App\Models\Member;
use App\Services\MemberService;
use App\Exceptions\EmailAlreadyExistException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberServiceTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $memberMocked;

    /**
     * La méthode "setUp" est appelée à chaque excecution de test
     */
    public function setUp()
    {
        parent::setUp();

        $this->memberMocked = \Mockery::mock(Member::class);
    }

    /**
     * Doit ajouter un nouvel email en base de donnée
     * Doit aussi vérifier qu'un email est en cours d'envoi dans le gestionnaire de queue
     *
     * 2 Points
     */
    public function test_BDD_Add_Email()
    {
        // Arrange
        $member = 'mathieu.berchi@gmail.com';

        // Assert
        $this->memberMocked->shouldReceive('where')
            ->once()
            ->with([
                Member::EMAIL => $member
            ])
            ->andReturn($this->memberMocked);

        $this->memberMocked->shouldReceive('first')
            ->once()
            ->andReturnNull();


        $this->memberMocked->shouldReceive('create')
            ->once()
            ->with([
                Member::EMAIL => $member
            ])
            ->andReturnTrue();

        $memberService = new MemberService($this->memberMocked);

        // Act
        $memberService->create($member);
    }


    /**
     * Doit retourner une exception de type EmailAlreadyExistException
     * si l'email est déjà existant
     *
     * 2 Points
     */
    public function test_Already_Existing()
    {
        // Arrange
        $member = 'mathieu.berchi@gmail.com';

        $memberLocalMocked = \Mockery::mock(Member::class);

        $memberLocalMocked->shouldReceive('offsetGet')
            ->once()
            ->with('id')
            ->andReturn(2);

        $this->memberMocked->shouldReceive('where')
            ->once()
            ->with([
                'id' => 1
            ])
            ->andReturn($this->memberMocked);

        $this->memberMocked->shouldReceive('first')
            ->once()
            ->andReturn(new Member());

        $this->memberMocked->shouldReceive('where')
            ->once()
            ->with([
                Member::EMAIL => $member
            ])
            ->andReturn($this->memberMocked);

        $this->memberMocked->shouldReceive('first')
            ->once()
            ->andReturn($memberLocalMocked);

        $memberService = new MemberService($this->memberMocked);

        // Assert
        $this->expectException(EmailAlreadyExistException::class);
    }

}
