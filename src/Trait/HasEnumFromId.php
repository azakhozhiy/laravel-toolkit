<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

trait HasEnumFromId
{
    public static function fromId(int $id): ?static
    {
        foreach (self::cases() as $case) {
            if ($case->id() === $id) {
                return $case;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    abstract public function id(): int;

    /**
     * @return array
     */
    public static function getCasesValueAsArray(): array
    {
        return array_map(
            static fn (self $enum) => $enum->value,
            self::cases()
        );
    }

    public static function getIds(): array
    {
        $data = [];

        foreach (self::cases() as $case) {
            $data[] = $case->id();
        }

        return $data;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function equalsValue(mixed $value): bool
    {
        return $this->value === $value;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function equalsId(int $id): bool
    {
        return $this->id() === $id;
    }

    /**
     * @param self $value
     *
     * @return bool
     */
    public function equalsEnum(self $value): bool
    {
        return $this === $value;
    }
}
