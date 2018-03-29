<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 29.03.2018
 * Time: 12:42
 */

namespace Course\Api\Controllers;


use Course\Api\Model\ContactModel;

use Course\Services\Http\Response;
use Course\Services\Utils\HTMLGenerator;
use Course\Services\Utils\StringUtils;

class ContactController implements Controller
{

    public function get()
    {
        HTMLGenerator::row(4);
        echo HTMLGenerator::tag("h2", "Contact");

        HTMLGenerator::form("post", "contact", [
            ["label" => "First name", "type" => "text", "name" => "first_name", "value" => ""],
            ["label" => "Last name", "type" => "text", "name" => "last_name", "value" => ""],
            ["label" => "Email", "type" => "text", "name" => "email", "value" => ""],
            ["label" => "Message", "type" => "text", "name" => "message", "value" => ""],
            ["label" => "", "type" => "submit", "name" => "contact", "value" => "Contact"]
        ]);
        HTMLGenerator::closeRow();
    }

    public function create()
    {
        if (isset($_POST['contact'])) {
            $first_name = StringUtils::sanitizeString($_POST['first_name']);

            $last_name = StringUtils::sanitizeString($_POST['last_name']);
            $email = StringUtils::sanitizeString($_POST['email']);
            $message = StringUtils::sanitizeString($_POST['message']);

            if (empty($first_name)) {
                Response::showInternalErrorResponse(ErrorCodes::INVALID_PARAMETER, "First name is empty");
            }

            if (empty($last_name)) {
                Response::showInternalErrorResponse(ErrorCodes::INVALID_PARAMETER, "Last name is empty");
            }

            if (empty($email)) {
                Response::showInternalErrorResponse(ErrorCodes::INVALID_PARAMETER, "Email is empty");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Response::showInternalErrorResponse(ErrorCodes::INVALID_PARAMETER, "Email is not valid");
            }

            if (empty($message)) {
                Response::showInternalErrorResponse(ErrorCodes::INVALID_PARAMETER, "Message is empty");
            }


            ContactModel::create($first_name, $last_name, $email, $message);

            HTMLGenerator::row(4, 4, 4);
            echo HTMLGenerator::tag("h2", "Successfully inserted contact");
            HTMLGenerator::closeRow();
        }
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