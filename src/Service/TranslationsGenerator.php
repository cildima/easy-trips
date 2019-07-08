<?php

namespace App\Service;

use App\Entity\Translation;

class TranslationsGenerator
{
    /**
     * Contains fields, that could be in translation object
     * @var array
     */
    public static $fields = [
        'title',
        'description',
        'seoTitle',
        'seoDescription',
        'previewText',
        'detailText',
        'readTime',
        'content',
        'losung',
        'subtitle',
        'linkText',
        'shortDescription',
        'serviceListTitle',
        'bannerText',
        'companyInformation',
        'address',
        'bannerText',
        'inflexiveTitle'
    ];

    /**
     * Returns fields, that could be in translation object
     * @return array
     */
    public function fields() {
        return self::$fields;
    }

    /**
     * Fill translation object by array $lang data
     *
     * @param Translation $translation
     * @param array $lang
     */
    public function fillInTranslationInfo(Translation &$translation, $lang)
    {
        foreach ($this->fields() as $field) {
            $method = 'set' . ucfirst($field);
            if (method_exists($translation, $method)) {
                $translation->$method($lang[$field]);
            }
        }

    }

    /**
     * Returns array of available $translation fields
     *
     * @param Translation $translation
     * @return array
     */
    public function getTranslationInfo(Translation $translation)
    {
        $data = [];

        foreach ($this->fields() as $field) {
            $method = 'get' . ucfirst($field);
            if (method_exists($translation, $method)) {
                $data[$field] = $translation->$method();
            }
        }

        $data['countryTitle'] = $translation->getCountry()->getTitle();

        return $data;
    }
}
