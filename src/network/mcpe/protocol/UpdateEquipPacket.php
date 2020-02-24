<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\handler\PacketHandler;

class UpdateEquipPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_EQUIP_PACKET;

	/** @var int */
	public $windowId;
	/** @var int */
	public $windowType;
	/** @var int */
	public $unknownVarint; //TODO: find out what this is (vanilla always sends 0)
	/** @var int */
	public $entityUniqueId;
	/** @var string */
	public $namedtag;

	protected function decodePayload() : void{
		$this->windowId = $this->buf->getByte();
		$this->windowType = $this->buf->getByte();
		$this->unknownVarint = $this->buf->getVarInt();
		$this->entityUniqueId = $this->buf->getEntityUniqueId();
		$this->namedtag = $this->buf->getRemaining();
	}

	protected function encodePayload() : void{
		$this->buf->putByte($this->windowId);
		$this->buf->putByte($this->windowType);
		$this->buf->putVarInt($this->unknownVarint);
		$this->buf->putEntityUniqueId($this->entityUniqueId);
		$this->buf->put($this->namedtag);
	}

	public function handle(PacketHandler $handler) : bool{
		return $handler->handleUpdateEquip($this);
	}
}