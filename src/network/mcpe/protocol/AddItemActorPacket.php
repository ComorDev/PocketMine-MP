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

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\handler\PacketHandler;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;

class AddItemActorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_ITEM_ACTOR_PACKET;

	/** @var int|null */
	public $entityUniqueId = null; //TODO
	/** @var int */
	public $entityRuntimeId;
	/** @var Item */
	public $item;
	/** @var Vector3 */
	public $position;
	/** @var Vector3|null */
	public $motion;
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public $metadata = [];
	/** @var bool */
	public $isFromFishing = false;

	protected function decodePayload() : void{
		$this->entityUniqueId = $this->buf->getEntityUniqueId();
		$this->entityRuntimeId = $this->buf->getEntityRuntimeId();
		$this->item = $this->buf->getSlot();
		$this->position = $this->buf->getVector3();
		$this->motion = $this->buf->getVector3();
		$this->metadata = $this->buf->getEntityMetadata();
		$this->isFromFishing = $this->buf->getBool();
	}

	protected function encodePayload() : void{
		$this->buf->putEntityUniqueId($this->entityUniqueId ?? $this->entityRuntimeId);
		$this->buf->putEntityRuntimeId($this->entityRuntimeId);
		$this->buf->putSlot($this->item);
		$this->buf->putVector3($this->position);
		$this->buf->putVector3Nullable($this->motion);
		$this->buf->putEntityMetadata($this->metadata);
		$this->buf->putBool($this->isFromFishing);
	}

	public function handle(PacketHandler $handler) : bool{
		return $handler->handleAddItemActor($this);
	}
}