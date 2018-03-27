<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 3/11/2017
 * Time: 4:03 PM
 */
namespace Course\Api\Controllers;

/**
 * Every controller should extend this, forces it to implement
 * the HTTP methods GET, POST, PUT, DELETE
 * Interface Controller
 * @package Course\Api\Controllers
 */
interface Controller
{
    public function get();
    public function create();
    public function update();
    public function delete();
}