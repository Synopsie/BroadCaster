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
 * @version 1.2.1
 *
 */

declare(strict_types=1);

namespace broadcaster\manager;

use broadcaster\Main;
use broadcaster\utils\BroadCastMessage;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\scheduler\ClosureTask;

use pocketmine\Server;
use function count;
use function is_callable;

class BroadCastManager {
	/** @var BroadCastMessage[] */
	private array $messages     = [];
	private int $currentMessage = 0;
	private Main $main;
	private ClosureTask $closureTask;

	public function __construct(
		Main $main
	) {
		$this->main = $main;
	}

	public function registerMessage(BroadCastMessage ...$message) : void {
		foreach ($message as $msg) {
			$this->messages[] = $msg;
		}
	}

	private function getNextMessage() : BroadCastMessage {
		$message = $this->messages[$this->currentMessage];
		$this->currentMessage++;
		if ($this->currentMessage >= count($this->messages)) {
			$this->currentMessage = 0;
		}
		return $message;
	}

	/**
	 * @return BroadCastMessage[]
	 */
	public function getMessages() : array {
		return $this->messages;
	}

	public function setup() : void {
		$scheduler = $this->main->getScheduler();
		$interval  = $this->main->getConfig()->get('broadcastMessage.Interval');
		foreach ($this->getMessages() as $message) {
			if (!$message->isExclusive()) {
				$scheduler->scheduleRepeatingTask(
					$this->getClosureTask(),
					$interval * 60 * 20
				);
			} else {
				$scheduler->scheduleRepeatingTask(
					$this->getClosureTask(),
					$message->getTime() * 60 * 20
				);
			}
		}
	}

	private function getClosureTask() : ClosureTask {
		if (!isset($this->closureTask)) {
			return $this->closureTask = new ClosureTask(
				function () : void {
					$message = $this->getNextMessage();
					$msg     = $message->getMessage();
					$type    = $message->getType();
                    $config = Main::getInstance()->getConfig();
					if (is_callable($msg)) {
						$msg = $msg();
					}
					if ($type === 'popup') {
						Server::getInstance()->broadcastPopup($msg);
					} elseif($type === 'tip') {
						Server::getInstance()->broadcastTip($msg);
					} elseif($type === 'actionbar') {
						foreach (Server::getInstance()->getOnlinePlayers() as $player) {
							$player->sendActionBarMessage($msg);
						}
					}elseif($type === 'toast'){
                        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                            $player->sendToastNotification($config->get('broadcast.toast.title', ''), $msg);
                        }
                    } else {
						Server::getInstance()->broadcastMessage($msg);
					}
                    if ($config->get('use.sound')) {
                        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                            $position = $player->getPosition();
                            $player->getNetworkSession()->sendDataPacket(
                                PlaySoundPacket::create(
                                    $config->get('sound.name', 'note.bell'),
                                    $position->getX(),
                                    $position->getY(),
                                    $position->getZ(),
                                    $config->get('sound.volume', 100),
                                    $config->get('sound.pitch', 1)
                                )
                            );
                        }
                    }
				}
			);
		} else {
			return $this->closureTask;
		}
	}
}
