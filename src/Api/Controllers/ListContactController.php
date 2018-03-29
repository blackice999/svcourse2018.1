<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 29.03.2018
 * Time: 13:07
 */

namespace Course\Api\Controllers;


use Course\Services\Http\Request;
use Course\Services\Http\Response;
use Course\Services\Utils\HTMLGenerator;

class ListContactController implements Controller
{

    public function get()
    {


        //Could not find a way to send "Authorization" key to header using Chrome
        $userModel = Request::getAuthenticatedUser();

        if(!$userModel) {
            Response::showUnauthorizedResponse();
        }
        HTMLGenerator::row(4);
        echo HTMLGenerator::tag("h2", "Contacts");


        $headers = ['header1', 'header2'];

        $rowData = [
            ['content11', 'content12'],
            ['content21', 'content22']
        ];

        $borderWidth = 1;
        HTMLGenerator::table(1, $headers, $rowData);
        HTMLGenerator::closeRow();
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}