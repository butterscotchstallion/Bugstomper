<?php
/*
 * iModule - interface describing the methods
 * all modules must implement
 *
 */
namespace module;
interface iModule
{
    public function GetRoutes();
}