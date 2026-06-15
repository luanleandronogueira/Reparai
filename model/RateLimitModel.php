<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Cache\FileStore;
use Illuminate\Cache\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cache\RateLimiter;

class RateLimitModel {
    private $limiter;

    public function __construct() {
        // Inicializa o sistema de arquivos do Laravel
        $filesystem = new Filesystem();

        // Define onde os arquivos de bloqueio serão salvos
        $storagePath = __DIR__ . '/../storage/cache';

        // Cria a pasta de cache se ela não existir
        if (!$filesystem->exists($storagePath)) {
            $filesystem->makeDirectory($storagePath, 0755, true);
        }

        // Configura o "estoque" (FileStore) e o "repositório" de cache
        $fileStore = new FileStore($filesystem, $storagePath);
        $cache = new Repository($fileStore);

        // Finalmente, cria o motor do RateLimiter
        $this->limiter = new RateLimiter($cache);
    }

    public function getLimiter() {
        return $this->limiter;
    }

}