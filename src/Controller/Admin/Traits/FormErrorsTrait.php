<?php

namespace App\Controller\Admin\Traits;

use Symfony\Component\Form\FormInterface;

trait FormErrorsTrait
{
    private function getFormErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
}
