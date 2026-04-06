<?php

namespace App\Enums;

enum StudentProcessingStage: string
{
    case Input = 'input';
    case School = 'school';
    case Embassy = 'embassy';
    case Visa = 'visa';

    public function label(): string
    {
        return match ($this) {
            self::Input => 'Tiếp nhận',
            self::School => 'Xử lý & Gửi trường',
            self::Embassy => 'Trình Cục/ĐSQ',
            self::Visa => 'Đã có Visa/Vé',
        };
    }

    public function topBorderClass(): string
    {
        return match ($this) {
            self::Input => 'border-top-slate',
            self::School => 'border-top-blue',
            self::Embassy => 'border-top-purple',
            self::Visa => 'border-top-green',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn(self $case) => ['value' => $case->value, 'label' => $case->label(), 'borderClass' => $case->topBorderClass()],
            self::cases()
        );
    }
}
