<?php

namespace shop\useCases\manage\Shop;

use shop\entities\Shop\Country;
use shop\forms\manage\Shop\CountryForm;
use shop\repositories\Shop\CountryRepository;

class CountryManageService
{
    private $countries;

    public function __construct(CountryRepository $countries)
    {
        $this->countries = $countries;
    }

    public function create(CountryForm $form): Country
    {
        $country = Country::create(
            $form->name,
            $form->iso_code_2,
            $form->iso_code_3,
            $form->iso_number_3,
            $form->active,
            $form->sort
        );
        $this->countries->save($country);
        return $country;
    }

    public function edit($id, CountryForm $form): void
    {
        $country = $this->countries->get($id);
        $country->edit(
            $form->name,
            $form->iso_code_2,
            $form->iso_code_3,
            $form->iso_number_3,
            $form->active,
            $form->sort
        );
        $this->countries->save($country);
    }

    public function activate($id): void
    {
        $country = $this->countries->get($id);
        $country->activate();
        $this->countries->save($country);
    }

    public function draft($id): void
    {
        $country = $this->countries->get($id);
        $country->draft();
        $this->countries->save($country);
    }


    public function remove($id): void
    {
        $country = $this->countries->get($id);
        $this->countries->remove($country);
    }
    
}