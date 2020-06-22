<?php


namespace MyParcelCom\IsotopeLib\Classes;


use Contao\BackendTemplate;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Backend;
use Contao\Image;
use Contao\Input;
use Contao\Message;
use Isotope\Model\Address;
use Isotope\Model\Config;
use Isotope\Model\Product;
use Isotope\Model\ProductCollection\Order;
use Isotope\Model\ProductPrice;
use MyParcelCom\ContaoLib\Classes\ApiAccessService;
use MyParcelCom\ContaoLib\Classes\ShipmentStatus;
use MyParcelCom\ContaoLib\Classes\ShipmentStatusSync;
use MyParcelCom\ContaoLib\Resources\contao\models\MyParcelComAuthModel;
use MyParcelCom\ContaoLib\Resources\contao\models\MyParcelComShipmentModel;

class OrderCallback extends Backend
{
    public function transferShipmentData(DC_Table $dc, $redirect = true)
    {
        $orderId = $dc->id;
        $order = Order::findByPk($orderId);
        $shippingInfos = $this->getShippingInfos($orderId);
        $shippingWeight = $shippingInfos['weight'];
        $shippingAddressId = $order->shipping_address_id;
        $address = Address::findByPk($shippingAddressId);
        $authModel = MyParcelComAuthModel::findBy("connectWith", "isotope");
        if (!$this->checkConfiguration($authModel)) {
            Message::addError("Bei der Prüfung der Konfiguration ist ein Fehler aufgetreten. Bitte prüfen Sie Ihre Daten auf Vollständigkeit.");
            Controller::redirect('contao?do=iso_orders&id=' . $orderId . '&rt=' . Input::get('rt') . '&ref=' . Input::get('ref'));
            return;
        }
        $apiService = new ApiAccessService(
            $authModel->clientid,
            $authModel->clientsecret,
            $authModel->apiUrl,
            $authModel->authUrl,
            $authModel->shopName
        );
        // split street name and number
        $idx = strrpos($address->street_1, " ");
        $street = substr($address->street_1, 0, $idx);
        $streetNumber = substr($address->street_1, $idx, strlen($address->street_1) - $idx);
        $recipientAddress = [
            'street' => $street,
            'city' => $address->city,
            'country' => $address->country,
            'firstname' => $address->firstname,
            'lastname' => $address->lastname,
            'postalCode' => $address->postal,
            'company' => $address->company,
            'streetnumber' => intval(trim($streetNumber)),
            'phoneNumber' => $address->phone
        ];
        
        $description = "Order#" . str_pad($order->getDocumentNumber(), 10, "0", STR_PAD_LEFT);
        
        $items = $this->getItemInfo($orderId, $order->getCurrency());
        
        $additionalData = [
            // needs to be in cents
            'amount' => $order->getTotal() * 100,
            'currency' => $order->getCurrency(),
            // fill document number with zeros to 10 digits
            'description' => $description,
            'content_type' => $authModel->content_type,
            'non_delivery' => $authModel->non_delivery,
            'incoterm' => $authModel->incoterm,
            'items' => $items,
        ];
        
        if ($shippingInfos['dimensions']) {
            $additionalData['dimensions'] = $shippingInfos['dimensions'];
        }
    
        if (!$this->checkData($recipientAddress, $additionalData, $shippingWeight)) {
            Message::addError("Bei der Prüfung der Bestelldaten ist ein Fehler aufgetreten. Bitte prüfen Sie Ihre Daten auf Vollständigkeit.");
            Controller::redirect('contao?do=iso_orders&id=' . $orderId . '&rt=' . Input::get('rt') . '&ref=' . Input::get('ref'));
            return;
        }

        $success = $apiService->createShipment(
            $shippingWeight,
            $authModel->id,
            $orderId,
            $recipientAddress,
            $additionalData
        );
        if ($success) {
            if ($success['success']) {
                Message::addInfo("Die Daten für die Bestellung ID $orderId wurden erfolgreich an MyParcel übertragen.");
                $statusUpdated = $this->updateOrderStatus($orderId, $authModel->myparcelStatus);
                if (!$statusUpdated) {
                    Message::addError("Beim Aktualisieren des Status für Bestellung ID $orderId ist ein Fehler aufgetreten.");
                }
                $shipmentUpdated = $this->updateShipmentStatus($orderId);
                if (!$shipmentUpdated) {
                    Message::addError("Beim Aktualisieren des Status für Sendung von Bestellung ID $orderId ist ein Fehler aufgetreten.");
                }
                if ($success['international']) {
                    Message::addInfo("Da es sich um eine internationale Sendung handelt, sollten Sie die Bestelldaten im MyParcel-Portal prüfen, um fehlende Unterlagen anzugeben und den Paketaufkleber erzeugen zu können.");
                }
            }
            
            
        } else {
            Message::addError("Beim Übertragen der Bestellungsdaten ist ein Fehler aufgetreten.");
        }
        if ($redirect) {
            Controller::redirect('contao?do=iso_orders&id=' . $orderId . '&rt=' . Input::get('rt') . '&ref=' . Input::get('ref'));
        }
    }
    
    private function checkData($recipientAddress, $additionalData, $weight)
    {
        if (!$weight) {
            return false;
        }
        if (!$recipientAddress['postalCode'] || !$recipientAddress['phoneNumber'] || !$recipientAddress['street']) {
            return false;
        }
        return true;
    }
    
    private function getItemInfo($orderId, $targetCurrency)
    {
        $itemInfos = [];
        $db = Database::getInstance();
        $items = $db->prepare("SELECT * FROM tl_iso_product_collection_item WHERE pid = ?")
            ->execute($orderId)->fetchAllAssoc();
        foreach ($items as $item) {
            $productId = $item['product_id'];
            // first of its kind
            if (!$itemInfos[$productId]) {
                $product = $this->getCorrectProduct($productId);
                $price = ProductPrice::findPrimaryByProductId($productId);
                if ($product && $price) {
                    // calculate weight
                    $weightInGrams = 0;
                    if ($product->shipping_weight) {
                        $shippingWeight = unserialize($product->shipping_weight);
                        if ($shippingWeight['value'] && $shippingWeight['unit']) {
                            switch ($shippingWeight['unit']) {
                                case 'g':
                                    $weightInGrams = intval($shippingWeight['value']);
                                    break;
                                case 'mg':
                                    $weightInGrams = intval($shippingWeight['value']) / 1000;
                                    break;
                                case 'kg':
                                    $weightInGrams = intval($shippingWeight['value']) * 1000;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                    $flPrice = floatval($price->getTiers()[$price->getLowestTier()]);
                    // convert price
                    $defaultShopConf = Config::findByFallback();
                    $targetShopConf = Config::findBy('name', $targetCurrency);
                    if ($targetShopConf !== $defaultShopConf) {
                        if ($targetShopConf) {
                            $factor = $targetShopConf->priceCalculateFactor;
                            $mode = $targetShopConf->priceCalculateMode;
                            if ($mode === "mul") {
                                $flPrice = $flPrice * $factor;
                            } else if ($mode === "div") {
                                $flPrice = $flPrice / $factor;
                            }
                            // convert to cents
                            $flPrice = (int)($flPrice * 100);
                        }
                    }
                    
                    $originCountryCode = $product->origin_country_code;
                    if (!$originCountryCode) {
                        $originCountryCode = strtoupper($defaultShopConf->country);
                    }
                    
                    $itemInfo = [
                        'description' => strip_tags($product->description),
                        'quantity' => 1,
                        'item_value' => $flPrice,
                        'item_weight' => $weightInGrams,
                        'hs_code' => "8517.12.00",
                        'origin_country_code' => $originCountryCode,
                        'sku' => "123456"
                    ];
                    $itemInfos[$productId] = $itemInfo;
                }
            } else {
                // product type already handled
                $itemInfos[$productId]['quantity'] += 1;
            }
        }
        
        return $itemInfos;
    }
    
    private function updateShipmentStatus($orderId)
    {
        $sql = "UPDATE tl_myparcelcom_api_shipment SET status = ? WHERE orderID = ?";
        $affectedRows = Database::getInstance()
            ->prepare($sql)
            ->execute(ShipmentStatus::STATUS_TRANSFERRED, $orderId)
            ->affectedRows;
        return $affectedRows > 0;
    }
    
    /**
     * Checks if all required data is set.
     * @param $authModel
     */
    private function checkConfiguration($authModel)
    {
        if (!$authModel) {
            Message::addError("Es wurde keine MyParcel-Konfiguration für Isotope konfiguriert. Bitte überprüfen Sie Ihre Konfiguration.");
            return false;
        }
        if (!$authModel->clientid ||
            !$authModel->clientsecret ||
            !$authModel->apiUrl ||
            !$authModel->authUrl ||
            !$authModel->shopName
        ) {
            Message::addError("Es fehlen Daten für den Zugriff auf die MyParcel-API. Bitte überprüfen Sie Ihre Konfiguration.");
            return false;
        }
        if (!$authModel->myparcelStatus) {
            Message::addError("Es ist kein Bestellstatus für die Übertragung an MyParcel definiert. Bitte überprüfen Sie Ihre Konfiguration.");
            return false;
        }
        return true;
    }
    
    private function getShippingInfos($orderId)
    {
        $shippingInfos = [];
        $weightInGrams = 0;
        $db = Database::getInstance();
        $items = $db->prepare("SELECT * FROM tl_iso_product_collection_item WHERE pid = ?")
            ->execute($orderId)->fetchAllAssoc();
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $product = $this->getCorrectProduct($productId);
            if ($product) {
                if ($product->shipping_weight) {
                    $shippingWeight = unserialize($product->shipping_weight);
                    if ($shippingWeight['value'] && $shippingWeight['unit']) {
                        switch ($shippingWeight['unit']) {
                            case 'g':
                                $weightInGrams += intval($shippingWeight['value']);
                                break;
                            case 'mg':
                                $weightInGrams += intval($shippingWeight['value']) / 1000;
                                break;
                            case 'kg':
                                $weightInGrams += intval($shippingWeight['value']) * 1000;
                                break;
                            default:
                                break;
                        }
                    }
                }
                if ($product->shipping_dimensions) {
                    $arrDimensions = explode("x", $product->shipping_dimensions);
                    $dimensions['height'] = $arrDimensions[0];
                    $dimensions['width'] = $arrDimensions[1];
                    $dimensions['length'] = $arrDimensions[2];
                }
            }
        }
        $shippingInfos['weight'] = $weightInGrams;
        if ($dimensions) {
            $shippingInfos['dimensions'] = $dimensions;
        }
        return $shippingInfos;
    }
    
    private function getCorrectProduct($productId)
    {
        $db = Database::getInstance();
        $product = $db->prepare("SELECT * FROM tl_iso_product WHERE id = ?")
            ->execute($productId);
        while ($product->pid != 0) {
            $product = $db->prepare("SELECT * FROM tl_iso_product WHERE id = ?")
                ->execute($product->pid);
        }
        return $product;
    }
    
    public function trackShipment(DC_Table $dc)
    {
        $orderID = $dc->id;
        $shipment = MyParcelComShipmentModel::findOneBy('orderID', $orderID);
        if (!$shipment) {
            return;
        }
        $shipmentID = $shipment->shipmentID;
        $authModel = MyParcelComAuthModel::findOneBy('connectWith', 'isotope');
        $apiService = new ApiAccessService(
            $authModel->clientid,
            $authModel->clientsecret,
            $authModel->apiUrl,
            $authModel->authUrl,
            $authModel->shopName
        );
        $objShipment = $apiService->getShipment($shipmentID);
        $trackingCode = $objShipment->getTrackingCode();
        $trackingURL = $objShipment->getTrackingUrl();
        $data = [
            'trackingCode' => $trackingCode,
            'trackingUrl' => $trackingURL,
            'orderID' => $orderID
        ];
        $template = new BackendTemplate('be_tracking_output');
        $template->setData($data);
        return $template->parse();
    }
    
    private function updateOrderStatus($orderId, $statusId)
    {
        $sql = "UPDATE tl_iso_product_collection SET order_status = ? WHERE id = ?";
        $rowsAffected = Database::getInstance()
            ->prepare($sql)->execute($statusId, $orderId)->affectedRows;
        return $rowsAffected > 0;
    }
    
    public function getTransferButton($row, $href, $label, $title, $icon)
    {
        $authModel = MyParcelComAuthModel::findBy('connectWith', 'isotope');
        if ($authModel) {
            $statusId = $authModel->myparcelStatus;
            $trackingStatusId = $authModel->trackingStatus;
            if (($row['order_status'] !== $statusId) && ($row['order_status'] !== $trackingStatusId)) {
                $href .= '&id=' . $row['id'];
                return '<a href="' . Backend::addToUrl($href) . '" title="' . \Contao\StringUtil::specialchars($title) . '">' . Image::getHtml($icon, $label) . '</a> ';
            }
        }
        return '';
    }
    
    public function getTransferAllButton($href, $label, $title, $class, $attributes, $table, $rootIds)
    {
        if (!$GLOBALS['TL_CONFIG']['myparcel_enableTransferButton']) {
            return "";
        } else {
            $icon = 'bundles/myparcelcomisotope/img/myparcel_blue.svg';
            $label = $GLOBALS['TL_LANG']['tl_iso_product_collection']['transferAllShipments'];
            return '<a href="' . Backend::addToUrl($href) . '" title="' . \Contao\StringUtil::specialchars($title) . '">' . Image::getHtml($icon, $label) . $label[0] . '</a> ';
        }
    }
    
    public function getTrackingButton($row, $href, $label, $title, $icon)
    {
        $authModel = MyParcelComAuthModel::findBy('connectWith', 'isotope');
        if ($authModel) {
            $orderStatus = $row['order_status'];
            if ($orderStatus === $authModel->trackingStatus) {
                $href .= '&id=' . $row['id'];
                return '<a href="' . Backend::addToUrl($href) . '" title="' . \Contao\StringUtil::specialchars($title) . '">' . Image::getHtml($icon, $label) . '</a> ';
            }
        }
        return "";
    }
    
    public function transferAllShipments(DC_Table $dc)
    {
        $sql = "SELECT id FROM tl_iso_product_collection WHERE type = 'order' AND order_status != ? AND order_status != ?";
        $authModel = MyParcelComAuthModel::findBy('connectWith', 'isotope');
        if (!$authModel) {
            return;
        }
        $myparcelStatus = $authModel->myparcelStatus;
        $trackingStatus = $authModel->trackingStatus;
        $result = Database::getInstance()->prepare($sql)->execute($myparcelStatus, $trackingStatus);
        $arrIds = $result->fetchAllAssoc();
        $ctr = 0;
        foreach ($arrIds as $id) {
            $dc->id = $id['id'];
            $this->transferShipmentData($dc, false);
            $ctr++;
        }
        Message::addInfo("Es wurden Daten für $ctr Bestellungen übertragen.");
        Controller::redirect('contao?do=iso_orders&rt=' . Input::get('rt') . '&ref=' . Input::get('ref'));
    }
    
    public function syncAllShipments(DC_Table $dc)
    {
        $authModel = MyParcelComAuthModel::findOneBy('connectWith', 'isotope');
        $myparcelStatus = $authModel->myparcelStatus;
        $arrIds = $this->getOrderIds($myparcelStatus);
        $arrOrderIds = [];
        foreach ($arrIds as $id) {
            $arrOrderIds[] = $id['id'];
        }
        $sync = new ShipmentStatusSync();
        $changeCtr = $sync->synchronizeStatus($arrOrderIds, $authModel);
        Message::addInfo("Es wurde der Status für $changeCtr Bestellungen aktualisiert.");
        Controller::redirect('contao?do=iso_orders&rt=' . Input::get('rt') . '&ref=' . Input::get('ref'));
    }
    
    private function getOrderIds($myparcelStatus)
    {
        $sql = "SELECT id FROM tl_iso_product_collection WHERE type = 'order' AND order_status = ?";
        $result = Database::getInstance()->prepare($sql)->execute($myparcelStatus);
        return $result->fetchAllAssoc();
    }
}