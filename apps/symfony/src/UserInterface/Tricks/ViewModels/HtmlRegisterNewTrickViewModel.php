<?php

declare(strict_types=1);

namespace App\UserInterface\Tricks\ViewModels;

use Symfony\Component\Form\FormView;

class HtmlRegisterNewTrickViewModel {
    public FormView $form;
    public array $errors = [];

    public function __construct()
    {
    }
}
