<?php

declare(strict_types=1);

namespace App\UserInterface\Security\ViewModels;

use Symfony\Component\Form\FormView;

class HtmlAskNewPasswordViewModel
{
    public ?FormView $form;

    public array $errors = [];
}
