<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\PersonService as PersonService;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    private $personService;

    public function __construct()
    {
       $this->personService = new PersonService(); 
    }
    
    /**
     * Save person data
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function savePersonData(Request $request) 
    {
        $validationResult = $this->personService->validateData($request->getContent());

        if ($validationResult['valid'] == false) {
            // The data is not valid. Return with the error message
            return response()->json(
                $validationResult,
                400
            );
        }

        $this->personService->setPersonData($request->getContent());

        if (!$this->personService->personExists()) {
            $this->personService->add();
        } else {
            $validationResult['person'] = $this->personService->modify();
        }

        if (isset($validationResult['person']) && !is_null($validationResult['person'])) {
            $validationResult['person'] = $validationResult['person'];
        }

        return response()->json($validationResult);
    }
    
    /**
     * Get all persons to list them
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getPersons()
    {
        return response()->json(
            Person::all()
        );
    }
    
    /**
     * Delete a person by id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function deletePerson(int $id)
    {
        $person = Person::find($id)->firstOrFail();

        $person->deletePersonWithDetails();

        return response()->json('OK');
    }
    
    /**
     * Get a person by id with all of him/her details
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function getPersonDetails(int $id)
    {
        $person = Person::where('id', $id)->with('addresses', 'contactData')->firstOrFail();

        return response()->json($person);
    }
}
