<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use App\Entity\Country;
use App\Service\TranslationsGenerator;

class TranslatableController extends BaseController
{

    protected $translationGenerator;

    /**
     * TranslatableController constructor.
     * @param $translationGenerator
     */
    public function __construct(TranslationsGenerator $translationGenerator)
    {
        $this->translationGenerator = $translationGenerator;
    }

    protected function translationsOnCreate($langs, $object, $translationClass)
    {
        $translationEM = $this->admin->getModelManager()->getEntityManager($translationClass);
        $countryRepo = $this->getDoctrine()->getRepository(Country::class);

        foreach ($langs as $countryId => $lang) {

            $country = $countryRepo->find($countryId);
            $translation = new $translationClass();
            $translation->setEntity($object);
            $translation->setCountry($country);
            $this->translationGenerator->fillInTranslationInfo($translation, $lang);
            $translationEM->persist($translation);
        }
    }

    protected function translationsOnEdit($langs, $object, $translationClass)
    {
        $translationRepo = $this->getDoctrine()->getRepository($translationClass);
        $countryRepo = $this->getDoctrine()->getRepository(Country::class);

        $objectTranslations = $object->getTranslations()->toArray();

        $currentCountries = [];
        foreach ($objectTranslations as $objectTranslation) {
            $currentCountries[$objectTranslation->getId()] = $objectTranslation->getCountry()->getId();
        }

        if (!empty($langs)) {
            foreach ($langs as $countryId => $lang) {
                $translationId = array_search($countryId, $currentCountries);
                if ($translationId) {
                    $translation = $translationRepo->find($translationId);
                    $this->translationGenerator->fillInTranslationInfo($translation, $lang);
                } else {
                    $country = $countryRepo->find($countryId);
                    $translation = new $translationClass();
                    $translation->setEntity($object);
                    $translation->setCountry($country);
                    $this->translationGenerator->fillInTranslationInfo($translation, $lang);
                }
                $this->getDoctrine()->getManager()->persist($translation);
            }

            $countriesIDs = array_keys($langs);
            foreach ($currentCountries as $translationId => $countryId) {
                if(!in_array($countryId, $countriesIDs)) {
                    $this->getDoctrine()->getManager()->remove($translationRepo->find($translationId));
                }
            }
        } else {
            foreach ($currentCountries as $translationId => $countryId) {
                $this->getDoctrine()->getManager()->remove($translationRepo->find($translationId));
            }
        }

    }

}