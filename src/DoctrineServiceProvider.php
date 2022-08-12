<?php


namespace Crmdesenvolvimentos\Doctrine;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Crmdesenvolvimentos\Doctrine\Types\EnumType;


class DoctrineServiceProvider extends ServiceProvider
{

    protected $types = [
        'mysql' => [
            'enum' => EnumType::class,
        ],
    ];

    /**
     * Register the service provider.
     */
    public function register()
    {
        $connection = DB::connection();
        $name = $connection->getDriverName();

        foreach (Arr::get($this->types, $name, []) as $type => $handler) {
            if (!Type::hasType($type)) {
                Type::addType($type, $handler);
            }

            $connection->getDoctrineConnection()
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping($type, $type);
        }
    }
}
