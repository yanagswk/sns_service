<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;


class CreateSQLQueryLogger
{
    /**
     * SQLクエリ用Monologインスタンス生成 : __invoke
     * @param array $config config/logging.php で sqlQueryLog に設定した path とか days とかが入ってる！
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // 'debug' とかの文字列をMonologが使えるログレベルに変換
        $level = Logger::toMonologLevel($config['level']);

        // 日ごとにログローテートするハンドラ作成
        $handler = new RotatingFileHandler($config['path'], $config['days'], $level);

        // 改行コードを出力する＆カラのコンテキストを出力しないフォーマッタを設定
        $handler->setFormatter(new LineFormatter(null, null, true, true));

        // 'logs/sql.log'
        // 'logs/request.log'
        // /var/www/html/storage/logs/sql.log
        $logger_name = mb_strtoupper(basename($config['path'], 'log'));

        // Monologインスタンス作成してハンドラ設定して返却
        $logger = new Logger($logger_name);     // ロガー名
        $logger->pushHandler($handler);
        return $logger;
    }

}


?>
