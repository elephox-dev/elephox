<?php
declare(strict_types=1);

namespace App;

use App\Models\User;
use App\Repositories\UserRepository;
use Elephox\Collection\ArrayMap;
use Elephox\Core\Context\Contract\CommandLineContext;
use Elephox\Core\Context\Contract\ExceptionContext;
use Elephox\Core\Context\Contract\RequestContext;
use Elephox\Core\Contract\App as AppContract;
use Elephox\Core\Handler\Attribute\CommandHandler;
use Elephox\Core\Handler\Attribute\ExceptionHandler;
use Elephox\Core\Handler\Attribute\Http\Any;
use Elephox\Core\Handler\Attribute\Http\Get;
use Elephox\Core\Handler\Attribute\Http\Post;
use Elephox\Core\Registrar;
use Elephox\Database\Contract\Storage;
use Elephox\Database\MysqlStorage;
use Elephox\DI\Contract\Container;
use Elephox\Http\Contract;
use Elephox\Http\Response;
use Elephox\Http\Url;
use Elephox\Logging\ConsoleSink;
use Elephox\Logging\Contract\Sink;
use Elephox\Logging\GenericSinkLogger;
use mysqli;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRunner;

class App implements AppContract
{
	use Registrar {
		registerAll as private registerAllTrait;
	}

	public array $classes = [
		UserRepository::class,
		WhoopsRunner::class,
		MysqlStorage::class,
		ConsoleSink::class,
		GenericSinkLogger::class
	];

	public array $aliases = [
		Storage::class => MysqlStorage::class,
		Sink::class => ConsoleSink::class,
	];

	public function registerAll(Container $container): void
	{
		$this->registerAllTrait($container);

		$container->singleton(mysqli::class, static function (Container $c) {
			$args = ArrayMap::fromIterable(Url::fromString($_ENV['DB_DSN'])->asArray())
				->mapKeys(fn (string $key) => match ($key) {
					'host' => 'hostname',
					'path' => 'database',
					default => $key
				})
				->asArray();

			return $c->instantiate(mysqli::class, $args);
		});
	}

	#[Get('/')]
	public function handleIndex(): Contract\Response
	{
		return Response::withJson([
			'message' => 'Hello, World! Send a POST request to /register to create an account or to /login to log in.',
			'ts' => microtime(true) - ELEPHOX_START,
		]);
	}

	#[Post('login')]
	public function login(RequestContext $context, UserRepository $userRepository): Contract\Response
	{
		$json = $context->getRequest()->getJson();
		if (!array_key_exists('username', $json)) {
			return Response::withJson([
				'message' => 'Username is required.',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		$username = $json['username'];
		/** @var User|null $user */
		$user = $userRepository->findBy('username', $username);
		if ($user === null) {
			return Response::withJson([
				'message' => 'User not found',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		if (!array_key_exists('password', $json) || !$user->verifyPassword($json['password'])) {
			return Response::withJson([
				'message' => 'Invalid password',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		return Response::withJson([
			'message' => "Welcome back, {$user->getUsername()}!",
			'ts' => microtime(true) - ELEPHOX_START,
		]);
	}

	#[Post('register')]
	public function register(RequestContext $context, UserRepository $userRepository): Contract\Response
	{
		$json = $context->getRequest()->getJson();
		if (!array_key_exists('username', $json)) {
			return Response::withJson([
				'message' => 'Username is required.',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		$username = $json['username'];
		/** @var User|null $user */
		$user = $userRepository->findBy('username', $username);
		if ($user !== null) {
			return Response::withJson([
				'message' => 'Username already exists',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		if (!array_key_exists('password', $json)) {
			return Response::withJson([
				'message' => 'Password is required.',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		if (!array_key_exists('email', $json)) {
			return Response::withJson([
				'message' => 'Email is required.',
				'ts' => microtime(true) - ELEPHOX_START,
			]);
		}

		$user = User::from($username, $json['password'], $json['email']);
		$userRepository->add($user);

		return Response::withJson([
			'message' => "Successfully registered, {$user->getUsername()}!",
			'ts' => microtime(true) - ELEPHOX_START,
		]);
	}

	#[Any('(?<anything>.*)')]
	public function catchAll(RequestContext $context, string $anything): Contract\Response
	{
		return Response::withJson([
			'message' => "You sent a request to an invalid endpoint: $anything Perhaps your request method ({$context->getRequest()->getMethod()->getValue()}) was invalid?",
			'tip' => "You can comment out the App::catchAll method to see your exception handler deal with an unmatched request.",
			'ts' => microtime(true) - ELEPHOX_START,
		]);
	}

	#[CommandHandler("/info/")]
	public function infoCommand(GenericSinkLogger $logger): void
	{
		$logger->info("Your app runs with the Elephox Framework version " . ELEPHOX_VERSION);
	}

	#[CommandHandler("/setup\-db/")]
	public function setupDbCommand(MysqlStorage $storage): void
	{
		$initial = require dirname(__DIR__). '/database/Transforms/20211122_Initial.php';

		$storage->getConnection()->query($initial);
	}

	#[CommandHandler]
	public function commandLineHandler(CommandLineContext $context, GenericSinkLogger $logger): void
	{
		$logger->info("You successfully invoked your Elephox app from command line!");
		$logger->info("\tCommand: " . $context->getCommand());
		$logger->info("Try running it with the 'info' command");
	}

	#[ExceptionHandler]
	public function globalExceptionHandler(ExceptionContext $context, ?CommandLineContext $commandLineContext, GenericSinkLogger $logger, WhoopsRunner $whoops): void
	{
		// handle command line exceptions
		if ($commandLineContext !== null) {
			$message = match ($commandLineContext->getCommand()) {
				'setup-db' => "An error occurred while setting up the db: " . $context->getException()->getMessage(),
				'info' => "An error occurred while running the info command: " . $context->getException()->getMessage(),
				'test' => "An error occurred while running the test command: " . $context->getException()->getMessage(),
				default => "Command not found: " . $commandLineContext->getCommand(),
			};

			$logger->error($message);

			exit(1);
		}

		// fallback handler for all other exceptions
		$whoops->pushHandler(new PrettyPageHandler);
		$whoops->handleException($context->getException());
	}
}
