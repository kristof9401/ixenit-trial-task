<?php

namespace App\Services;
use App\Models\Address;
use App\Models\Person;
use App\Models\ContactData;

class PersonService
{
    private $personData;
    
    /**
     * Add a new person
     *
     * @return void
     */
    public function add()
    {
        $person = Person::create([
            'first_name' => $this->personData['first_name'],
            'last_name' => $this->personData['last_name'],
            'birthdate' => $this->personData['birthdate'],
        ]);

        foreach ($this->personData['addresses'] as $currAddress) {
            $currAddress['person_id'] = $person->id;
            Address::create($currAddress);
        }

        foreach ($this->personData['contact_data'] as $currContactData) {
            $currContactData['person_id'] = $person->id;
            ContactData::create($currContactData);
        }
    }


    /**
     * Set the value of personData
     *
     * @param string $personData
     * @return  self
     */ 
    public function setPersonData(string $personData)
    {
        $this->personData = json_decode($personData, true);

        return $this;
    }

    /**
     * Validate the data and retrive error message in case of invalid.
     *
     * @param string  $data
     * @return array
     */
    public function validateData(string $data) {
        $requiredKeys = ['first_name', 'last_name', 'birthdate', 'addresses', 'contact_data'];

        $return = [
            'valid' => true,
            'error' => ''
        ];

        $personData = json_decode($data, true);

        if ($personData == false) {
            $return['error'] = "The content is not valid JSON!";
        }

        $usedAddressTypes = [];

        if (is_array($personData)) {
            foreach ($requiredKeys as $currKey) {
                if (!array_key_exists($currKey, $personData) || !$personData[$currKey]) {
                    $return['error'] = 'The ' . $currKey . ' is missing!';
                } 
            }

            if (array_key_exists('addresses', $personData)) {
                $addressRequiredKeys = ['type', 'postal_code', 'city', 'street'];

                foreach ($personData['addresses'] as $currAddress) {
                    if (in_array($currAddress['type'], $usedAddressTypes)) {
                        $return['error'] ='A person has only one permanent and only one temporary address';
                    } else {
                        $usedAddressTypes[] = $currAddress['type'];
                    }

                    array_map(function ($key) use ($currAddress, &$return) {
                        if (!array_key_exists($key, $currAddress) || !$currAddress[$key]) {
                            $return['error'] = 'The ' . $key . ' is missing within address!';
                        }
                    }, $addressRequiredKeys);
                }
            }

            if (array_key_exists('contact_data', $personData)) {
                $contactDataRequiredKeys = ['type', 'value'];

                foreach ($personData['contact_data'] as $currContactData) {
                    array_map(function ($key) use ($currContactData, &$return) {
                        if (!array_key_exists($key, $currContactData) || !$currContactData[$key]) {
                            $return['error'] = 'The ' . $key . ' is missing within contact_data!';
                        } 
                    }, $contactDataRequiredKeys);

                    if (array_key_exists('type', $currContactData) && $currContactData['type'] == 'email') {
                        if (!filter_var($currContactData['value'], FILTER_VALIDATE_EMAIL)) {
                            $return['error'] = 'Email format is not valid';
                        }
                    }
                }
            }
        }

        $return['valid'] = empty($return['error']);

        return $return;
    }

    public function personExists()
    {
        return array_key_exists('person_id', $this->personData) && is_numeric($this->personData['person_id']);
    }

    /**
     * Validate the data and retrive error message in case of failure
     *
     * @param string  $data
     * @return array
     */
    public function modify() 
    {
        $person = Person::where('id', $this->personData['person_id'])
                    ->with('addresses', 'contactData')->first();

        if (is_null($person)) {
            return false;
        }

        $person->update([
            'first_name' => $this->personData['first_name'],
            'last_name' => $this->personData['last_name'],
            'birthdate' => $this->personData['birthdate'],
        ]);

        foreach ($this->personData['addresses'] as $currAddress) {
            if (!array_key_exists('id', $currAddress)) {
                $person->addresses()->create($currAddress);
                continue;
            }

            $address = Address::find($currAddress['id']);

            if (is_null($address)) {
                continue;
            }

            $address->update($currAddress);
        }

        foreach ($this->personData['contact_data'] as $currContactData) {
            if (!array_key_exists('id', $currContactData)) {
                $person->contactData()->create($currContactData);
                continue;
            }

            $contactData = ContactData::find($currContactData['id']);

            if (is_null($contactData)) {
                continue;
            }

            $contactData->update($currContactData);
        }

        $person->refresh();

        return $person;
    }
}
