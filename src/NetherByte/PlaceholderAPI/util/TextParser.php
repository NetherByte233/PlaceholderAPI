<?php

declare(strict_types=1);

namespace NetherByte\PlaceholderAPI\util;

final class TextParser{
    /**
     * Replace %identifier% tokens using a resolver callback that returns string|null.
     * Unknown tokens are left unchanged.
     *
     * @param callable(string): (?string) $resolver
     */
    public static function replace(string $text, callable $resolver) : string{
        return preg_replace_callback('/%([^%]+)%/', function(array $m) use ($resolver){
            $id = $m[1];
            $value = $resolver($id);
            return $value ?? $m[0];
        }, $text) ?? $text;
    }
}
