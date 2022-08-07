<?php
declare(strict_types=1);

namespace App\Routes;

use App\Widgets\Box;
use App\Widgets\InlineCode;
use Elephox\Http\Contract\ResponseBuilder;
use Elephox\Http\Response;
use Elephox\Http\ResponseCode;
use Elephox\Mimey\MimeType;
use Elephox\Templar\EdgeInsets;
use Elephox\Templar\Foundation\Column;
use Elephox\Templar\Foundation\Container;
use Elephox\Templar\Foundation\FullscreenBody;
use Elephox\Templar\Foundation\FullscreenDocument;
use Elephox\Templar\Foundation\Head;
use Elephox\Templar\Foundation\Link;
use Elephox\Templar\Foundation\Text;
use Elephox\Templar\Foundation\TextSpan;
use Elephox\Templar\Foundation\Title;
use Elephox\Templar\Length;
use Elephox\Templar\Templar;
use Elephox\Templar\TextStyle;
use Elephox\Templar\Widget;
use Elephox\Web\Routing\Attribute\Controller;
use Elephox\Web\Routing\Attribute\Http\Any;
use Elephox\Web\Routing\Attribute\Http\Get;
use RuntimeException;

#[Controller("")]
class WebController {
	/**
	 * @throws \ErrorException
	 */
	#[Get]
	public function index(): ResponseBuilder {
		return Response::build()->responseCode(ResponseCode::OK)->htmlBody(
				$this->getRenderer()->render($this->getContent())
			);
	}

	#[Get('style.css')]
	public function style(): ResponseBuilder {
		return Response::build()->responseCode(ResponseCode::OK)->htmlBody(
				$this->getRenderer()->renderStyle($this->getContent()),
				MimeType::TextCss
			);
	}

	private function getRenderer(): Templar {
		return new Templar();
	}

	private function getContent(): Widget {
		return new FullscreenDocument(
			head: new Head(
				children: [
					new Title("Elephox Application"),
					new Link('/style.css'),
				],
			),
			body: new FullscreenBody(
				child: new Container(
					child: new Column(
						children: [
							new Container(
								child: new Text(
									"Hello World",
									style: new TextStyle(
										size: Length::inRem(3),
										weight: 600,
									),
								),
								margin: EdgeInsets::only(bottom: Length::inRem(1.5)),
							),
							new Box(
								new Text("This is your new Elephox application"),
								new TextSpan("", children: [
									new TextSpan("You can find the source code of this application in the "),
									new InlineCode("src/"),
									new TextSpan(" directory."),
								]),
							),
							new Box(
								new Text("Want to see your exception handler at work?"),
								new Text(
									<<<HTML
In case you have <a href="https://packagist.org/packages/nunomaduro/collision" target="_blank"><code>nunomaduro/collision</code></a>
(or <a href="https://packagist.org/packages/filp/whoops" target="_blank"><code>filp/whoops</code></a>)
installed, it will handle unexpected exceptions for you!<br>
<a href="/throw">Click here</a> to throw an example exception and see the magic in action.
HTML
								)
							),
							new Box(
								new Text("Need to look something up?"),
								new Text(
									<<<HTML
						You can access the Elephox documentation at
						<a href="https://elephox.dev" target="_blank">elephox.dev</a>.
HTML
								)
							),
						],
					),
					padding: EdgeInsets::symmetric(
						Length::inRem(1.5),
						Length::inRem(3),
					),
				),
			),
		);
	}

	#[Get]
	public function throw(): ResponseBuilder {
		throw new RuntimeException('Test exception');
	}

	#[Any('regex:.*', -1)]
	public function notFound(): ResponseBuilder {
		return Response::build()->responseCode(ResponseCode::NotFound)->fileBody(
				APP_ROOT . '/views/error404.html',
				MimeType::TextHtml
			);
	}
}
