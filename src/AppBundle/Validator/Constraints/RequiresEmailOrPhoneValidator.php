<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class RequiresEmailOrPhoneValidator extends ConstraintValidator
{
    public function validate($user, Constraint $constraint)
    {
        if (empty($user->getEmail()) && empty($user->getPhone())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
