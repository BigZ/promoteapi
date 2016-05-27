<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Gig;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GigVoter extends Voter
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


        if (!$subject instanceof Gig) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $gig, TokenInterface $token)
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
            return $this->canCreate($gig, $user);
        case self::EDIT:
            return $this->canEdit($gig, $user);
        case self::DELETE:
            return $this->canDelete($gig, $user);
        }
    }

    /**
     * @param Gig  $gig
     * @param User $user
     * @return bool
     */
    private function canCreate(Gig $gig, User $user)
    {
        return true;
    }

    /**
     * @param Gig  $gig
     * @param User $user
     * @return bool
     */
    private function canEdit(Gig $gig, User $user)
    {
        if ($user === $gig->getCreatedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param Gig  $gig
     * @param User $user
     * @return bool
     */
    private function canDelete(Gig $gig, User $user)
    {
        if ($user === $gig->getCreatedBy()) {
            return true;
        }

        return false;
    }
}