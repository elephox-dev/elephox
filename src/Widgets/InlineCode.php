<?php
declare(strict_types=1);

namespace App\Widgets;

use Elephox\Templar\EdgeInsets;
use Elephox\Templar\Foundation\Colors;
use Elephox\Templar\Foundation\TextSpan;
use Elephox\Templar\Length;
use Elephox\Templar\RenderContext;
use Elephox\Templar\RendersPadding;
use Elephox\Templar\TextStyle;

class InlineCode extends TextSpan {
	use RendersPadding;

	protected readonly EdgeInsets $padding;

	public function __construct(
		string $text,
		?TextStyle $style = null,
		?EdgeInsets $padding = null,
	) {
		parent::__construct($text, $style ?? new TextStyle(
			color: Colors::Magenta()->desaturate(0.3),
			background: Colors::Grayscale(0.95),
		));

		$this->padding = $padding ?? EdgeInsets::symmetric(Length::inRem(0.25), Length::inRem(0.0625));
	}

	protected function getTag(): string {
		return 'code';
	}

	protected function renderStyleContent(RenderContext $context): string {
		$style = parent::renderStyleContent($context);
		$style .= $this->renderPadding($this->padding);

		return $style;
	}
}
