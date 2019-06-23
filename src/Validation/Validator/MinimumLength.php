<?php


namespace Terminal42\PasswordValidationBundle\Validation\Validator;

use Contao\System;
use Symfony\Component\Validator\Exception\ValidatorException;
use Terminal42\PasswordValidationBundle\Validation\ValidationConfiguration;
use Terminal42\PasswordValidationBundle\Validation\ValidationContext;

final class MinimumLength implements PasswordValidatorInterface
{

    private $configuration;

    public function __construct(ValidationConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function validate(ValidationContext $context): bool
    {
        if (false === $this->configuration->hasConfiguration($context->getUserEntity())) {
            return true;
        }

        $configuration = $this->configuration->getConfiguration($context->getUserEntity());
        $minimumLength = $configuration['min_length'];
        if (!$minimumLength) {
            return true;
        }

        $password = $context->getPassword()->getString();

        if (strlen($password) < $minimumLength) {
            throw new ValidatorException(sprintf($this->translate('minLength'), $minimumLength));
        }

        return true;
    }

    private function translate(string $key)
    {
        System::loadLanguageFile('exception');

        return $GLOBALS['TL_LANG']['XPT']['passwordValidation'][$key];
    }
}