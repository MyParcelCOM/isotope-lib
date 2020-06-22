<?php


namespace MyParcelCom\ContaoLib\Classes;


class ShipmentStatus
{
    const STATUS_PENDING = "pending"; // is this needed? when not transferred, the shipment doesn't exist
    const STATUS_TRANSFERRED = "transferred";
    const STATUS_TRACKABLE = "trackable";
    const STATUS_DELETED = "deleted";
}