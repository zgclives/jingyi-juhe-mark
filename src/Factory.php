<?php

namespace JuheMark;

/**
 * Class Factory
 * @method static \JuheMark\Common\Application Common(array $config = [])
 * @method static \JuheMark\Partner\Application Partner(array $config = [])
 */
class Factory
{
    /**
     * @param string $name
     * @param array  $config
     * @return \EasyWeChat\Kernel\ServiceContainer
     */
    public static function make($name, $config = [])
    {
        $namespace   = self::studly($name);
        $application = "\\JuheMark\\{$namespace}\\Application";
        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

    public static function studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }
}
