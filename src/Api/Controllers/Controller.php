<?php

namespace Course\Api\Controllers;

/**
 * Interface Controller
 * Every controller should extend this, forces it to implement
 * the HTTP methods GET, POST, PUT, DELETE
 * @package Course\Api\Controllers
 */
interface Controller
{
    public function get();
    public function create();
    public function update();
    public function delete();
}