<?php
declare(strict_types=1);
namespace FediE2EE\PKDServer\Scheduled;

use FediE2EE\PKDServer\ActivityPub\ActivityStream;
use FediE2EE\PKDServer\Exceptions\DependencyException;
use FediE2EE\PKDServer\Protocol;
use FediE2EE\PKDServer\ServerConfig;
use ParagonIE\EasyDB\EasyDB;
use Throwable;

class ASQueue
{
    private EasyDB $db;

    /**
     * @throws DependencyException
     */
    public function __construct(private readonly ServerConfig $config)
    {
        $this->db = $config->getDB();
    }

    /**
     * ASQueue::run() is a very dumb method.
     *
     * All this method does is grab the unprocessed messages, order them, decode them, and then pass them onto
     * Protocol::process().
     *
     * The logic is entirely contained to Protocol and the Table classes.
     */
    public function run(): void
    {
        $workload = $this->db->run(
            "SELECT * FROM pkd_activitystream_queue
            WHERE NOT processed
            ORDER BY queueid ASC"
        );
        $protocol = new Protocol($this->config);
        foreach ($workload as $queue) {
            try {
                $decoded = json_decode($queue['message'], false, 512, JSON_THROW_ON_ERROR);
                $protocol->process(ActivityStream::fromDecoded($decoded));
                $success = true;
            } catch (Throwable $ex) {
                // TODO: log failure!
                echo $ex->getMessage(), PHP_EOL;
                $success = false;
            }
            $this->db->update(
                'pkd_activitystream_queue',
                [
                    'processed' => true,
                    'successful' => $success,
                ],
                ['queueid' => $queue['queueid']]
            );
        }
    }
}
