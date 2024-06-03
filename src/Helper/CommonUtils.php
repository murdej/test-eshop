<?php

namespace App\Helper;

class CommonUtils
{
    /**
     * @param array<mixed, mixed> $arr
     * @param \Closure|string $keyCallback
     * @return array<mixed, mixed>
     */
    public static function indexBy(array $arr, \Closure|string $keyCallback): array
    {
        if (is_string($keyCallback)) {
            $keyCallback = match (true) {
                str_starts_with($keyCallback, '.') => fn($item) => $item->{substr($keyCallback, 1)},
                str_starts_with($keyCallback, '[') => fn($item) => $item[substr($keyCallback, 1)],
                default => throw new \InvalidArgumentException("Invalid string keyCallback '$keyCallback'.")
            };
        }
        $newArray = [];

        foreach ($arr as $v) {
            $k = $keyCallback($v);
            $newArray[$k] = $v;
        }

        return $newArray;
    }

    /**
     * @param array<mixed, mixed> $olds
     * @param array<mixed, mixed> $news
     * @param \Closure|null $eqCallback
     * @return object{
     *    'unchanged': object{oldValue:mixed, newValue:mixed}[],
     *    'changed': object{oldValue:mixed, newValue:mixed}[],
     *    'added': object{oldValue:mixed, newValue:mixed}[],
     *    'removed': object{oldValue:mixed, newValue:mixed}[],
     * }
     */
    public static function diffAssoc(array $olds, array $news, \Closure|null $eqCallback = null): object
    {
        if (!$eqCallback) $eqCallback = fn($a, $b) => $a == $b;

        $oldKeys = array_keys($olds);
        $newKeys = array_keys($news);

        $keysInBoth = array_intersect($oldKeys, $newKeys);
        $unchanged = [];
        $changed = [];

        foreach ($keysInBoth as $k) {
            $item = (object)[
                'oldValue' => $olds[$k],
                'newValue' => $news[$k],
            ];
            if ($eqCallback($olds[$k], $news[$k]))
                $unchanged[$k] = $item;
            else
                $changed[$k] = $item;
        }

        $added = [];
        $removed = [];
        foreach (array_diff($newKeys, $oldKeys) as $k) $added[$k] = (object)[
            'oldValue' => null,
            'newValue' => $news[$k],
        ];
        foreach (array_diff($oldKeys, $newKeys) as $k) $removed[$k] = (object)[
            'oldValue' => $olds[$k],
            'newValue' => null,
        ];


        return (object)[
            'unchanged' => $unchanged,
            'changed' => $changed,
            'added' => $added,
            'removed' => $removed,
        ];
    }

    /**
     * @param array<mixed,mixed> $array
     * @param mixed[] $keys
     * @return array<mixed,mixed>
     */
    public static function filterArrayByKeys(array $array, array $keys): array {
        return array_filter(
            $array,
            fn($key) => in_array($key, $keys),
            ARRAY_FILTER_USE_KEY
        );
    }

    public static function slugify(string $str): string
    {
        return strtolower(str_replace(' ', '-', $str));
    }

    /**
     * @template T
     * @param array<mixed, T> $items
     * @return T
     */
    public static function randomItem(array $items): mixed
    {
        return $items[array_rand($items)];
    }

    /**
     * @param array<mixed, mixed> $src
     * @param (callable(mixed, mixed):array{mixed,mixed}) $callback
     * @return array<mixed, mixed>
     */
    public static function mapKV(array $src, callable $callback): array
    {
        $res = [];
        foreach ($src as $k => $v) {
            [$newK, $newV] = $callback($k, $v);
            $res[$newK] = $newV;
        }

        return $res;
    }

}