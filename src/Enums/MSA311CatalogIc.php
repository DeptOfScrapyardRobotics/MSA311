<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

enum MSA311CatalogIc: string
{
    case MSA311 = 'msa311';

    /**
     * @return list<string>
     */
    public static function slugs(): array
    {
        return array_map(
            static fn (self $case): string => $case->value,
            self::cases(),
        );
    }
}
