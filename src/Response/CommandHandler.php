<?php


namespace Halitools\MicroCommand\Response;


use Exception;
use Halitools\MicroCommand\Request\Command;
use Psr\Container\ContainerInterface;

class CommandHandler
{

    /** @var ContainerInterface */
    private $container;

    /**
     * CommandHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $content
     * @return string
     */
    public function handle($content)
    {
        /** @var Command $command */
        $command = unserialize(base64_decode($content));
        if (!is_a($command, Command::class)) {
            abort(422, 'Body should be a serialized command');
        }
        try {
            $response = $this->handleCommand($command);
        } catch (Exception $exception) {
            $response = ExceptionResponseFactory::make($exception);
        }
        return base64_encode(serialize($response));
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function handleCommand(Command $command)
    {
        $instance = $this->container->get($command->getInterface());
        return call_user_func_array([$instance, $command->getMethod()], $command->getArguments());
    }
}