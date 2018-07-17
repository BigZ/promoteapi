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

use App\Entity\Artist;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class ArtistVoter.
 *
 * @author Romain Richard
 */
class ArtistVoter extends Voter implements VoterInterface
{
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * ArtistVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
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

        return true;
    }

    /**
     * {@inheritdoc}
     */
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

        switch ($attribute) {
            case self::CREATE:
                return true;
            case self::EDIT:
                return $artist instanceof Artist && $this->canEdit($artist, $user);
            case self::DELETE:
                return $artist instanceof Artist && $this->canDelete($artist, $user);
        }
    }

    /**
     * @param Artist $artist
     * @param User   $user
     *
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
     * @param User   $user
     *
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
