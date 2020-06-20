<?php


namespace App\Security;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    // these strings are just invented: you can use anything
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof Comment) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Comment $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($comment, $user);
            case self::EDIT:
                return $this->canEdit($comment, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Comment $comment, User $user)
    {
        // copy canEdit
        return $this->canEdit($comment, $user);
    }

    private function canEdit(Comment $comment, User $user)
    {
        if (in_array('ROLE_ADMIN',$user->getRoles())){
            return true;
        }

        // this assumes that the Post object has a `getOwner()` method
        return $user === $comment->getAuthor();
    }
}
