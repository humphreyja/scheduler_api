<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Validates email format
 * @Annotation
 */
class RequiresEmailFormat extends Constraint
{
    public $message = '"{string}" is not a valid email format';
}
