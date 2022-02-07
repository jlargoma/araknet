<?php
namespace App\Services;

      
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogsService{
  
  var $logger;
  
  public function __construct($folder,$channel) {
    $this->logger = new Logger($channel);
    $folder .= date('-Ym');
    $this->logger->pushHandler(
            new StreamHandler(storage_path('logs/'.$folder.'.log'), Logger::DEBUG)
            );
  }
  
  public function info($text,$context = []) {
    if ($context && !is_array($context)) $context = [$context];
    $this->logger->info($text,$context);
  }
  public function warning($text,$context = []) {
    if ($context && !is_array($context)) $context = [$context];
    $this->logger->warning($text,$context);
  }
  public function error($text,$context = []) {
    if ($context && !is_array($context)) $context = [$context];
    $this->logger->error($text,$context);
  }
  
}


