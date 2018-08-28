<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 26/08/2018
 * Time: 09:37
 */

namespace AppBundle\Security;


use AppBundle\Entity\Quote;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class QuoteVoter extends Voter
{

    const EDIT = 'edit';

    private $decisionManager;

    /**
     * EditQuoteVoter constructor.
     * @param $decisionManager
     */
    public function __construct( AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {

        if (!in_array($attribute, [self::EDIT] )) {
            return false;
        }

        if (!$subject instanceof Quote) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

         if($this->decisionManager->decide($token, array('ROLE_ADMIN'))){
             return true;
         }
         elseif($this->decisionManager->decide($token, array('ROLE_USER'))){
            return false;
         }


    }
}