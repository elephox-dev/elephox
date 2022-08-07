<?php
declare(strict_types=1);

namespace App\Widgets;

use Elephox\Templar\BorderRadius;
use Elephox\Templar\BoxShadow;
use Elephox\Templar\BuildWidget;
use Elephox\Templar\EdgeInsets;
use Elephox\Templar\Foundation\Column;
use Elephox\Templar\Foundation\Container;
use Elephox\Templar\Foundation\TextStyleApplicator;
use Elephox\Templar\Length;
use Elephox\Templar\RenderContext;
use Elephox\Templar\TextStyle;
use Elephox\Templar\Widget;

class Box extends BuildWidget {
	public function __construct(
		protected readonly ?Widget $title = null,
		protected readonly ?Widget $child = null,
	) {}

	protected function build(RenderContext $context): Widget {
		$children = [];

		if ($this->title !== null) {
			$children[] = new Container(
				child: new TextStyleApplicator(
					child: $this->title,
					textStyle: new TextStyle(
						size: Length::inRem(1.5),
					),
				),
				margin: EdgeInsets::only(bottom: Length::inRem(1.5)),
			);
		}

		if ($this->child !== null) {
			$children[] = $this->child;
		}

		return new Container(
			child: new Column(
				children: $children,
			),
			shadows: BoxShadow::fromElevation(5)->withAmbient(),
			padding: EdgeInsets::all(Length::inRem(1.25)),
			margin: EdgeInsets::only(bottom: Length::inRem(1.5)),
			borderRadius: BorderRadius::all(6),
		);
	}
}
