<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Person list</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        @vite(['resources/css/app.css', 'resources/js/list.js'])
    </head>
    <body>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-lg-12">
                    <div class="card rounded-3">
                        <div class="card-body p-4">
                            <h4 class="text-center my-3 pb-3">Person list</h4>

                            <div class="col-12 mb-4">
                                <a href="add-person" class="btn btn-success">Add new person</a>
                            </div>

                            <table class="col-12 table mb-4">
                                <thead>
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Birthdate</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Telephone</th>                                
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="list-content">
                                    <tr>
                                        <td colspan="5"> Loading... </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

