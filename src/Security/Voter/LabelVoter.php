<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Security\Voter;

use App\Entity\Label;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class LabelVoter
 * @author Romain Richard
 */
class LabelVoter extends Voter
{
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

        switch ($attribute) {
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
     *
     * @return bool
     */
    private function canCreate(Label $label, User $user)
    {
        return true;
    }

    /**
     * @param Label $label
     * @param User  $user
     *
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
     *
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
