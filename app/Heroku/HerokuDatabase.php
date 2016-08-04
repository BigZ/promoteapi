<?php

namespace Application\Heroku;

use Composer\Script\Event;
use Dotenv\Dotenv;

class HerokuDatabase
{
    public static function populateEnvironment(Event $event)
    {
        $io = $event->getIO();

        try {
            $dotenv = new Dotenv(__DIR__ . '/../../Web/');
            $dotenv->load();
        } catch (\Exception $exception) {
            $io->write('Loading env from shell');
        }

        $url = getenv('DATABASE_URL');

        if ($url) {
            $url = parse_url($url);
            putenv("DATABASE_HOST={$url['host']}");
            putenv("DATABASE_USER={$url['user']}");

            $password = isset($url['pass']) ? $url['pass'] : 'NULL';
            putenv("DATABASE_PASSWORD={$password}");

            $port = isset($url['port']) ? $url['port'] : 'NULL';
            putenv("DATABASE_PORT={$port}");

            $db = substr($url['path'], 1);
            putenv("DATABASE_NAME={$db}");
        }
        
        $io->write('DATABASE_URL='.getenv('DATABASE_URL'));
    }
}
