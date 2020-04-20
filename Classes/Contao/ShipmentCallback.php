<?php


namespace MyParcelCom\ContaoApi\Classes\Contao;


use Contao\Controller;
use Contao\DC_Table;
use Contao\File;
use Contao\Message;
use MyParcelCom\ContaoApi\Classes\ApiAccessService;
use MyParcelCom\ContaoApi\Resources\contao\models\MyParcelComAuthModel;
use MyParcelCom\ContaoApi\Resources\contao\models\MyParcelComShipmentModel;

class ShipmentCallback
{
    public function registerShipment(DC_Table $dc)
    {
        $shipmentDBId = $dc->id;
        // TODO get correct ID
        $authId = 1;
        $authModel = MyParcelComAuthModel::findByPk($authId);
        $shipment = MyParcelComShipmentModel::findByPk($shipmentDBId);
        $apiService = new ApiAccessService(
            $authModel->clientid,
            $authModel->clientsecret,
            $authModel->apiUrl,
            $authModel->authUrl,
            $authModel->shopName
        );
        $registered = $apiService->registerShipment($shipment->shipmentID);
        if (!$registered) {
            Message::addError("Es ist ein Fehler bei der Registrierung der Lieferung mit der ID " . $shipmentDBId . " aufgetreten.");
        }
    }
    
    public function downloadLabel(DC_Table $dc)
    {
        $shipmentDBId = $dc->id;
        // TODO get correct ID
        $authId = 1;
        $authModel = MyParcelComAuthModel::findByPk($authId);
        $shipment = MyParcelComShipmentModel::findByPk($shipmentDBId);
        $apiService = new ApiAccessService(
            $authModel->clientid,
            $authModel->clientsecret,
            $authModel->apiUrl,
            $authModel->authUrl,
            $authModel->shopName
        );
        $encodedLabel = $apiService->getLabel($shipment->shipmentID);
        $labelFileContent = base64_decode($encodedLabel);
        // store in /files
        // TODO check if directory exists
        $filename = 'files/myparcel.com/labels/' . $shipment->shipmentID;
        file_put_contents($filename, $labelFileContent);
        // TODO should it be directly downloaded or stored in the files/ directory?
    }
}