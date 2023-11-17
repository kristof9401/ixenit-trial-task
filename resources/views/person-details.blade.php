<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@if ($add_new) Add new person @else Edit Person @endif</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        @vite(['resources/css/app.css', 'resources/js/details.js'])
    </head>
    <body>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-12">
                    <div class="card rounded-3">
                        <div class="card-body p-4">
                            <h4 class="text-center my-3 pb-3">
                                @if (isset($add_new) && $add_new)
                                    Add new person 
                                @else
                                    Edit Person 
                                @endif
                            </h4>

                            <form class="col-12 @if(isset($person_id)) hidden @endisset" id="person-details-form" method="POST">
                                <div class="form-group">
                                    <label for="first-name">First name</label>
                                    <input type="text" class="form-control" id="first-name" name="person.first_name" placeholder="First name">                    
                                </div>
                                <div class="form-group">
                                    <label for="last-name">Last name</label>
                                    <input type="text" class="form-control" id="last-name" name="person.last_name" placeholder="Last name">                    
                                </div>
                                <div class="form-group">
                                    <label for="last-name">Birthdate</label>
                                    <input type="date" class="form-control" id="birthdate" name="person.birthdate">                    
                                </div>
                                <div class="form-group contact-data-group">
                                    <label for="contact-type">Contact type</label>
                                    <select class="form-control" name="contact.type" id="contact-type">
                                        <option value="email">Email</option>
                                        <option value="tel">Telephone</option>
                                        <option value="fb">Facebook url</option>
                                        <option value="twitter">Twitter url</option>
                                        <option value="linkedin">Linkedin url</option>
                                    </select>
                                    <label for="contact-value">Contact value</label>
                                    <input type="text" class="form-control" id="contact-value" name="contact.value" placeholder="Contact value">
                                    
                                    <button class="btn btn-outline-primary mt-2 mr-0 ml-auto d-block clone-button" type="button">Add further contact data</button>
                                </div>
                                <div class="form-group address-group">
                                    Address
                                    <select class="form-control" id="address-type" name="address.type">
                                        <option value="permanent">Permanent</option>
                                        <option value="temporary">Temporary</option>
                                    </select>
                                    <input type="text" class="form-control" id="postal-code" name="address.postal_code" placeholder="Postal code">
                                    <input type="text" class="form-control" id="city" name="address.city"  placeholder="City">
                                    <input type="text" class="form-control" id="street" name="address.street" placeholder="Street">   

                                    <button class="btn btn-outline-primary mb-4 mt-2 mt-2 mr-0 ml-auto d-block clone-button address-btn" type="button">Add further address</button>
                                </div>

                                <div class="form-group">
                                    <a href="/" class="btn btn-danger">Cancel</a>
                                    <button class="btn btn-success float-right" id="save-data">Save</button>
                                </div>
                                
                                @isset($person_id)
                                    <input type="hidden" value="{{ $person_id }}" id="person-id" />
                                @endisset
                            </form>

                            @isset($person_id)
                                <p id="loading-row">Loading...</p>
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
