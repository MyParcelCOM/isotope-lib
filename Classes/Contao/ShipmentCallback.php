<?php


namespace MyParcelcom\ContaoApi\Classes\Contao;


class ShipmentCallback
{
    public function registerShipmentButtonCb($row, $href, $label, $title, $icon)
    {
        // TODO do some sort of validation, so only valid shipments can be registered
    }
    
    public function registerShipment()
    {
        
    }
    
    public function downloadLabelButtonCb($row, $href, $label, $title, $icon)
    {
        // TODO check if label is already existent (status check for current shipment)
        // TODO if yes, then deliver a link that points to a symfony route, so the pdf can be downloaded
        
    }
    
    public function downloadLabel()
    {
    
    }
}