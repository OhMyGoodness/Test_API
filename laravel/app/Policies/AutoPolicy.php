<?php

declare(strict_types=1);

namespace App\Policies;

use App\Services\Auto\Models\Auto;
use App\Services\User\Models\User;

/**
 * Policy для управления доступом к автомобилям.
 *
 * Определяет, может ли аутентифицированный пользователь
 * выполнять операции редактирования и удаления над конкретным автомобилем.
 */
class AutoPolicy
{
    /**
     * Проверяет, может ли пользователь обновить автомобиль.
     *
     * Пользователь может редактировать только те автомобили, которые принадлежат ему.
     *
     * @param User $user Аутентифицированный пользователь.
     * @param Auto $auto Автомобиль, который требуется обновить.
     * @return bool Разрешено ли редактирование.
     */
    public function update(User $user, Auto $auto): bool
    {
        return $user->id === $auto->user_id;
    }

    /**
     * Проверяет, может ли пользователь удалить автомобиль.
     *
     * Пользователь может удалять только те автомобили, которые принадлежат ему.
     *
     * @param User $user Аутентифицированный пользователь.
     * @param Auto $auto Автомобиль, который требуется удалить.
     * @return bool Разрешено ли удаление.
     */
    public function delete(User $user, Auto $auto): bool
    {
        return $user->id === $auto->user_id;
    }
}
