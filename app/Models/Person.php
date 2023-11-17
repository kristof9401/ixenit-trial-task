<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    
    protected $fillable = ['first_name','last_name','birthdate'];
    
    /**
     * When we get the model we get these attributes as well
     *
     * @var array
     */
    protected $appends = ['email', 'telephone'];

    /**
     * Retrieves the connecting contact_data records
     *
     * @return array
     */
    public function contactData()
    {
        return $this->hasMany(ContactData::class, 'person_id', 'id');
    }

    /**
     * Retrieves the connecting address records
     *
     * @return array
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'person_id', 'id');
    }
    
    /**
     * Get the email addres of the person
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        $data = $this->contactData()->where('type', 'email')->first();
        
        if (is_null($data)) {
            return null;
        }

        return $data->value;
    }
    
    /**
     * Get the telehone number of the person
     *
     * @return string
     */
    public function getTelephoneAttribute()
    {
        $data = $this->contactData()->where('type', 'tel')->first();
        
        if (is_null($data)) {
            return null;
        }

        return $data->value;
    }
    
    /**
     * Delete the person with all of him/her data
     *
     * @return void
     */
    public function deletePersonWithDetails()
    {
        $this->addresses()->delete();
        $this->contactData()->delete();
        $this->delete();
    }

}
