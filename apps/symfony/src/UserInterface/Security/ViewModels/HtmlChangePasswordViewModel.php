<?php

namespace App\UserInterface\Security\ViewModels;

use Symfony\Component\Form\FormView;

class HtmlChangePasswordViewModel
{
    public ?FormView $form;

    public array $errors = [];
}
