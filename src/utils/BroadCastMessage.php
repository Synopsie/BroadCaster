<?php

/*
 *  ____   __   __  _   _    ___    ____    ____    ___   _____
 * / ___|  \ \ / / | \ | |  / _ \  |  _ \  / ___|  |_ _| | ____|
 * \___ \   \ V /  |  \| | | | | | | |_) | \___ \   | |  |  _|
 *  ___) |   | |   | |\  | | |_| | |  __/   ___) |  | |  | |___
 * |____/    |_|   |_| \_|  \___/  |_|     |____/  |___| |_____|
 *
 * Ce plugin fera en sorte d'écrire des messages automatiques au moment souhaitez et entièremenet configurable.
 *
 * @author Synopsie
 * @link https://github.com/Synopsie
 * @version 1.4.1
 *
 */

declare(strict_types=1);

namespace broadcaster\utils;

use Closure;
use Exception;

final class BroadCastMessage {
	/**
	 * @var string|Closure(): string $message
	 */
	private Closure|string $message;
	private string $type;
	private bool $exclusive;
	private ?int $time;

	/**
	 * @throws Exception
	 */
	public function __construct(
		Closure|string $message,
		string $type,
		bool                    $exclusive = false,
		?int                    $time = null
	) {
		if ($exclusive && $time === null) {
			throw new Exception('If the message is exclusive, you must specify a time');
		}
		$this->message   = $message;
		$this->type      = $type;
		$this->exclusive = $exclusive;
		$this->time      = $time;
	}

	/**
	 * @return string|Closure(): string
	 */
	public function getMessage() : Closure|string {
		return $this->message;
	}

	public function getType() : string {
		return $this->type;
	}

	public function isExclusive() : bool {
		return $this->exclusive;
	}

	public function getTime() : ?int {
		return $this->time;
	}

}
