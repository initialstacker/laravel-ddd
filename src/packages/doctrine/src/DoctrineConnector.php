<?php declare(strict_types=1);

namespace Initialstack\Doctrine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Cache\Adapter\RedisAdapter;

final class DoctrineConnector extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: EntityManagerInterface::class,
            concrete: function (Application $app): EntityManager {
                $redis = RedisAdapter::createConnection(
                    dsn: config(key: 'doctrine.redis_url'),
                    options: config(key: 'doctrine.redis_ssl_options', default: [])
                );

                $proxyDir = storage_path(path: 'doctrine/proxies');

                if (!is_dir(filename: $proxyDir)) {
                    mkdir(directory: $proxyDir, permissions: 0775, recursive: true);
                }
                
                $config = ORMSetup::createAttributeMetadataConfiguration(
                    paths: config(key: 'doctrine.metadata_dirs'),
                    isDevMode: config(key: 'doctrine.dev_mode'),
                    proxyDir: $proxyDir,
                    cache: new RedisAdapter(redis: $redis)
                );

                $config->setAutoGenerateProxyClasses(
                    autoGenerate: ProxyFactory::AUTOGENERATE_ALWAYS
                );

                $connection = DriverManager::getConnection(
                    params: config(key: 'doctrine.connection'),
                    config: $config
                );
                
                foreach (config(key: 'doctrine.custom_types') as $name => $type) {
                    if (!Type::hasType(name: $name)) {
                        Type::addType(name: $name, type: $type);
                    }
                }

                return new EntityManager(conn: $connection, config: $config);
            }
        );
    }
}
