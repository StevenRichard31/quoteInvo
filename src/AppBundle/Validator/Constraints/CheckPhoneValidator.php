<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 05/06/2018
 * Time: 11:18
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckPhoneValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {

        $limit = 3;

        //si "phones" n'a pas de numero de telephone
        if($value->count() == (null || 0)){
            $this->context->buildViolation($constraint->messageNoPhone)->addViolation();
        }

        // limite le nombre telephone
        if( $limit < $value->count() ){
            $this->context->buildViolation($constraint->messageTooManyPhone)
                ->setParameter('{{ limit }}', $limit)
                ->addViolation();

        }
    }
}