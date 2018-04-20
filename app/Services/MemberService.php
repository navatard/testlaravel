<?php

namespace App\Services;

use App\Exceptions\EmailAlreadyExistException;
use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;


class MemberService
{
/**
     * @var Member
     */
    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Permet la récupération des tâches de la TODO
     *
     * @return Collection
     * @throws \Exception
     */
    public function lists(): Collection
    {
        return $this->member->all();
    }

    /**
     * Permet de créer un nouvel email
     *
     * @param string $member
     * @throws \Exception
     */
    public function create(string $member)
    {
        // $memberMocked
        $result = $this->member->where([
            Member::EMAIL => $member
        ])->first();
        
        if (!is_null($result)) {
            throw new EmailAlreadyExistException();
        }

        $this->member->create([
            Member::EMAIL => $member
        ]);
    }

   
}