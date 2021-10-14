<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Contracts\Repository;

use App\Infrastructure\Tricks\Entity\Category;
use Doctrine\Persistence\ObjectRepository;

/**
 * @method Category[]    findAll()
 */
interface CategoriesRepositoryInterface extends ObjectRepository
{
}
