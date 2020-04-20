<?php


namespace MyParcelCom\ContaoApi\Classes\Contao;


class ShipmentCallback
{
    public function registerShipmentButtonCb($row, $href, $label, $title, $icon)
    {
        // TODO do some sort of validation, so only valid shipments can be registered
        $link = '<a href=' .$href . ' title=' . $title . '>';
        if ($icon) {
            $altText = "";
            $icon = '<img src="' . $icon . '" width=16 height=16 alt=' .$altText . '/>';
            $link .= $icon;
        }
        $link = $link . '</a>';
        return $link;
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