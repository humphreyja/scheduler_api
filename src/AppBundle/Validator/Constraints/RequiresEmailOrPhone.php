<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint that requires a user to have either a phone or an email provided.
 * @Annotation
 */
class RequiresEmailOrPhone extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public $message = 'The user requires either email or phone to be set';
}
