<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Validates phone format
 * @Annotation
 */
class RequiresPhoneFormat extends Constraint
{
    public $message = '"{string}" is not a valid phone number format';
}
