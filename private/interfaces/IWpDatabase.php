<?php

interface IWpDatabase
{
    public function getOption($key);
    public function updateOption($key, $value);
}