<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Artist;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArtistVoter extends Voter
{
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])) {
            return false;
        }


        if (!$subject instanceof Artist) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $artist, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // Admin can do it
        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        switch($attribute) {
            case self::CREATE:
                return $this->canCreate($artist, $user);
            case self::EDIT:
                return $this->canEdit($artist, $user);
            case self::DELETE:
                return $this->canDelete($artist, $user);
        }
    }

    /**
     * @param Artist $artist
     * @param User $user
     * @return bool
     */
    private function canCreate(Artist $artist, User $user)
    {
        return true;
    }

    /**
     * @param Artist $artist
     * @param User $user
     * @return bool
     */
    private function canEdit(Artist $artist, User $user)
    {
        if ($user === $artist->getCreatedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param Artist $artist
     * @param User $user
     * @return bool
     */
    private function canDelete(Artist $artist, User $user)
    {
        if ($user === $artist->getCreatedBy()) {
            return true;
        }

        return false;
    }
}