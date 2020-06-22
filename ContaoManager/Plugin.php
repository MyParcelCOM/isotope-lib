<?php

/**
 * con4gis for Contao Open Source CMS
 *
 * @version   php 7
 * @package   con4gis-Data (DataBundle)
 * @author    con4gis contributors
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2011 - 2019
 * @link      https://www.kuestenschmiede.de
 */

namespace MyParcelCom\IsotopeLib\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Config\ConfigInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use MyParcelCom\ContaoLib\MyParcelComContaoLibBundle;
use MyParcelCom\IsotopeLib\MyParcelComIsotopeLibBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * Gets a list of autoload configurations for this bundle.
     *
     * @param ParserInterface $parser
     *
     * @return ConfigInterface[]
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MyParcelComIsotopeLibBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, MyParcelComContaoLibBundle::class, 'isotope'])
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load('@MyParcelComIsotopeLibBundle/Resources/config/config.yml');
    }

}
