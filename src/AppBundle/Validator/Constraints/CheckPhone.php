<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 05/06/2018
 * Time: 11:35
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckPhone extends Constraint
{
    public $messageNoPhone = 'Ajouter aux minimum un numéro de telephone pour le client. ' ;

    public $messageTooManyPhone = 'Il y a plus de "{{ limit }}" numéro de téléphone ' ;

}