<?php
declare(strict_types=1);

namespace App;

use App\Repositories\UserRepository;
use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Context\Contract\RequestContext;
use Elephox\Core\Contract\App as AppContract;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Core\Handler\Attribute\Http\Post;
use Elephox\Database\Contract\Storage;
use Elephox\Database\MysqlStorage;
use Elephox\DI\Contract\Container;
use Elephox\Http\Contract;
use Elephox\Http\Response;
use Elephox\Http\Url;
use JsonException;
use RuntimeException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRunner;

class App implements AppContract
{
    public function __construct(Container $container)
    {
        $container->register(Storage::class, static function () {
            $dsn = Url::fromString($_ENV['DB_DSN']);
            $connection = mysqli_connect($dsn->getHost(), $dsn->getUsername(), $dsn->getPassword(), trim($dsn->getPath(), '/'));

            return new MysqlStorage($connection);
        });

        $container->register(UserRepository::class, UserRepository::class);
        $container->register(WhoopsRunner::class, WhoopsRunner::class);
    }

    #[Get('/')]
    public function handleIndex(): Contract\Response
    {
        return Response::withJson([
            'message' => 'Hello, World! Send a POST request to /info to get more info.',
            'ts' => microtime(true) - ELEPHOX_START,
        ]);
    }

    #[Post('info')]
    public function info(RequestContext $context, UserRepository $userRepository): Contract\Response
    {
        try {
            $json = $context->getRequest()->getJson();
        } catch (JsonException $e) {
            throw new RuntimeException('Invalid JSON body.', previous: $e);
        }

        $username = $json['username'];
        $user = $userRepository->findBy('username', $username);
        if ($user === null) {
            return Response::withJson([
                'message' => 'User not found',
                'ts' => microtime(true) - ELEPHOX_START,
            ]);
        }

        return Response::withJson([
            'message' => "You successfully POSTed! Next, try to comment out the 'catchAll' handler and see your exception handler at work.",
            'ts' => microtime(true) - ELEPHOX_START,
        ]);
    }

    #[Any('{anything}')]
    public function catchAll(RequestContext $context, string $anything): Contract\Response
    {
        return Response::withJson([
            'message' => "You sent a request to an invalid endpoint: $anything Perhaps your request method ({$context->getRequest()->getMethod()->getValue()}) was invalid?",
            'ts' => microtime(true) - ELEPHOX_START,
        ]);
    }

    #[CommandHandler]
    public function commandLineHandler(CommandLineContext $context): void
    {
        echo "You successfully invoked your Elephox app from command line!";
    }

    #[ExceptionHandler]
    public function globalExceptionHandler(ExceptionContext $context, WhoopsRunner $whoops): void
    {
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->handleException($context->getException());
    }
}
