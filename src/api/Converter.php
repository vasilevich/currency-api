<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use vasilevich\currencyconverter\CurrencySourceBankOfEurope;
use vasilevich\currencyconverter\CurrencySourceBankOfIsrael;
use vasilevich\currencyconverter\CurrencySourceDenemarkNationalBank;
use vasilevich\currencyconverter\CurrencySourceFromSerialization;

class Converter
{
    public const ISRAEL_SOURCE = "israel";
    public const EUROPE_SOURCE = "europe";
    public const DENMARK_SOURCE = "denmark";
    private static $cache;


    public static function initCache()
    {
        if (!Converter::$cache) {
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => __DIR__ . '/../../cache', // or in windows "C:/tmp/"
            ]));
            Converter::$cache = CacheManager::getInstance('files');
        }
    }

    public static function convert($from, $to, $amount = 1, $source = self::ISRAEL_SOURCE)
    {
        return self::getSource($source)->getCurrencyList()->convert($from, $to, $amount);
    }

    public static function getSource($source = self::ISRAEL_SOURCE)
    {
        if ($source == null)
            $source = self::ISRAEL_SOURCE;
        $source = strtolower($source);
        $cachedString = Converter::$cache->getItem(self::_filter_key($source));
        if ($cachedString->isHit()) {
            return new CurrencySourceFromSerialization($cachedString->get());
        }
        switch ($source) {
            case self::ISRAEL_SOURCE:
                return self::addToCache(new CurrencySourceBankOfIsrael(), $source);
            case self::EUROPE_SOURCE:
                return self::addToCache(new CurrencySourceBankOfEurope(), $source);
            case self::DENMARK_SOURCE:
                return self::addToCache(new CurrencySourceDenemarkNationalBank(), $source);
            default:
                return null;
        }
    }

    private static function addToCache($converter, $source, $amountOfDays = 1)
    {
        $cachedString = self::$cache->getItem(self::_filter_key($source));
        $date = new DateTime();
        $date->setTime(0, 0, 0, 0);
        $date->add(new DateInterval('P' . $amountOfDays . 'D'));
        $cachedString->set($converter->serialize())->expiresAt($date);
        self::$cache->save($cachedString);
        return $converter;
    }

    private static function _filter_key($key = "")
    {
        $chars = [
            "{" => "rcb",
            "}" => "lcb",
            "(" => "rpn",
            ")" => "lpn",
            "/" => "fsl",
            "\\" => "bsl",
            "@" => "ats",
            ":" => "cln"
        ];

        foreach ($chars as $character => $replacement) {
            if (strpos($key, $character)) {
                $key = str_replace($character, "~" . $replacement . "~", $key);
            }
        }

        return trim($key);
    }

    public static function getAvailableConverters()
    {
        return [
            self::ISRAEL_SOURCE,
            self::EUROPE_SOURCE,
            self::DENMARK_SOURCE
        ];
    }

}

Converter::initCache();
