<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pendiente = 'pendiente';
    case Pagado    = 'pagado';
    case Enviado   = 'enviado';
    case Entregado = 'entregado';

    /**
     * All possible status values (e.g. for filters).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Statuses an admin can manually transition an order to.
     * 'pagado' is set automatically by checkout, not by admin action.
     */
    public static function manuallyAssignable(): array
    {
        return [
            self::Pendiente->value,
            self::Enviado->value,
            self::Entregado->value,
        ];
    }
}
