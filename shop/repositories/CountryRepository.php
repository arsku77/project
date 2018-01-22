<?php

namespace shop\repositories\Shop;

use shop\entities\Country;
use shop\repositories\NotFoundException;

class CountryRepository
{
    public function get($id): Country
    {
        if (!$Country = Country::findOne($id)) {
            throw new NotFoundException('Country is not found.');
        }
        return $Country;
    }

    public function findByName($name): ? Country
    {
        return Country::findOne(['name' => $name]);
    }

    public function save(Country $Country): void
    {
        if (!$Country->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Country $Country): void
    {
        if (!$Country->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}