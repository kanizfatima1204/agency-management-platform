<?php return ['default'=>env('QUEUE_CONNECTION','sync'),'connections'=>['sync'=>['driver'=>'sync']],'failed'=>['driver'=>'database-uuids','database'=>'mysql','table'=>'failed_jobs']];
