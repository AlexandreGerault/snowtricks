<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Contract\Repository;

use App\Infrastructure\Trick\Entity\Category;
use Doctrine\Persistence\ObjectRepository;

/**
 * @method Category[] findAll()
 */
interface CategoryRepositoryInterface extends ObjectRepository
{
}
