# MyParcelCom - Isotope


Some informations/instructions for setup of bundle

Please make sure you have first installed the bundle MyParcelCOM/contao-lib and created a account at https://app.myparcel.com/registration for getting API-credentials.
With this bundle you get the status fields from MyParcelCOM/contao-lib bundle. You get the entry "Isotope" as field for connected shop system. For the right working
you need a valid API configuration.

## Isotope-orderoverview
This bundle didn't have own Backend-modules. It expand only the existing order grid with own functionallities.
There are two new functions which added.
1. MyParcel.com - Button exports order data and change order status
2. Tracking - Button open a new window with tracking code and link for getting detail trackinginfos from carrier.
Finally the order grid shows two new buttons, too. "Status für alle aktualisieren"-Button checks the transferstatus of all existing orders. The second button "transfer all" orders to MyParcel.com poratl)  could be activated with setting at the config area of contao (menu called "MyParcel.com"). This button transfers all existing order data for all orders with one click. This function is most needed if you have old orders which are not transfered to the MyParcel.com - Portal.

Detailinformations for installation and setup could be found at https://help.myparcel.com/support/solutions ,too.


(German part)
Im Folgenden werden ein paar Hinweise für die Einrichtung des Bundles gegeben.

Durch dieses Bundle werden in den Statusfeldern des MyParcelCom/ContaoLib-Bundles 
die Isotope-Bestellstatus als Optionen zur Verfügung gestellt. Auch erscheint ein Eintrag
"Isotope" im Feld "Verknüpftes Shop-System". Damit die Aktionen an den Bestellungen funktionieren,
muss es eine API-Konfiguration geben, an der Isotope verknüpft ist.
## Isotope-Bestellungsübersicht
Dieses Bundle stellt keine eigenen Backend-Module zur Verfügung, sondern erweitert
stattdessen die Bestellungsübersicht von Isotope eCommerce.

An den einzelnen Bestellungen gibt es zwei neue Funktionen. Die erste Funktion (MyParcel-Button)
überträgt die Bestelldaten für die entsprechende Bestellung und setzt den Status auf den an der verwendeten
Konfiguration hierfür ausgewählten Status. 

Die zweite Funktion betrifft das Tracking. Der Button wird nur dargestellt, wenn im MyParcel-Portal 
bereits für die Bestellung Tracking-Informationen zur Verfügung stehen. Beim Klick auf den Button wird eine Ansicht geöffnet,
in der der Tracking-Code und ein Link dargestellt werden, mit dem die Sendung nachverfolgt werden kann. Dieser Link führt 
zur Website des verwendeten Dienstes, z.B. DPD.

Über der Liste werden auch bis zu zwei neue Button dargestellt. Der "Status für alle aktualisieren"-Button
prüft für alle Bestellungen, ob im MyParcel-Portal bereits Bestellungen abgewickelt wurden und demnach neue Informationen
zur Verfügung stehen, und setzt den Status im Contao-System entsprechend um. Hierüber wird auch geprüft, ob
für eine Bestellung Tracking-Informationen zur Verfügung stehen.

Ein zweiter Button kann über die Einstellungen eingeblendet werden, dort gibt es einen Bereich
"MyParcel.com". Dieser Button überträgt Bestelldaten in das MyParcel-Portal, und zwar für alle Bestellungen,
die noch nicht ins MyParcel-Portal übertragen wurden oder für die noch keine Tracking-Infos zur Verfügung stehen.
Diese Funktion kann also unter Umständen alte Bestellungen übertragen, die existierten, bevor das MyParcel-Portal genutzt wurde.
