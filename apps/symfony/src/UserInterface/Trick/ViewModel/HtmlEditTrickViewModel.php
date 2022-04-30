<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\ViewModel;

use Symfony\Component\Form\FormView;

class HtmlEditTrickViewModel
{
    public FormView $form;
    public array $errors = [];

    public function __construct()
    {
    }
}
