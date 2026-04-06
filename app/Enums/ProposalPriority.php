<?php

namespace App\Enums;

enum ProposalPriority: string
{
    case VeryLow  = 'very_low';
    case Low      = 'low';
    case Normal   = 'normal';
    case High     = 'high';
    case VeryHigh = 'very_high';

    /**
     * Label hiển thị các mức độ ưu tiên
     */
    public function label(): string
    {
        return match($this) {
            self::VeryLow  => 'Rất thấp',
            self::Low      => 'Thấp',
            self::Normal   => 'Bình thường',
            self::High     => 'Cao',
            self::VeryHigh => 'Rất cao',
        };
    }

    /**
     * Nhãn có icon
     */
    public function jsLabel(): string
    {
        return match($this) {
            self::VeryLow  => '⬇ Rất thấp',
            self::Low      => '↘ Thấp',
            self::Normal   => '➡ Bình thường',
            self::High     => '↗ Cao',
            self::VeryHigh => '⬆ Rất cao',
        };
    }

    /**
     * Mảng [value => label] cho select options
     */
    public static function options(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[$case->value] = $case->label();
        }
        return $result;
    }

    /**
     * Mảng các value string để validate input priority
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Mảng [{value, label}] serialize sang JSON cho window.PRIORITY_OPTIONS
     */
    public static function jsOptions(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->jsLabel()],
            self::cases()
        );
    }

    /**
     * Tìm enum từ string value, trả về Normal nếu không khớp
     */
    public static function fromValueOrDefault(string $value): self
    {
        return self::tryFrom($value) ?? self::Normal;
    }
}
