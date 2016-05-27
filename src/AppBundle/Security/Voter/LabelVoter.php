<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Label;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LabelVoter extends Voter
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


        if (!$subject instanceof Label) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $label, TokenInterface $token)
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
            return $this->canCreate($label, $user);
        case self::EDIT:
            return $this->canEdit($label, $user);
        case self::DELETE:
            return $this->canDelete($label, $user);
        }
    }

    /**
     * @param Label $label
     * @param User  $user
     * @return bool
     */
    private function canCreate(Label $label, User $user)
    {
        return true;
    }

    /**
     * @param Label $label
     * @param User  $user
     * @return bool
     */
    private function canEdit(Label $label, User $user)
    {
        if ($user === $label->getCreatedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param Label $label
     * @param User  $user
     * @return bool
     */
    private function canDelete(Label $label, User $user)
    {
        if ($user === $label->getCreatedBy()) {
            return true;
        }

        return false;
    }
}