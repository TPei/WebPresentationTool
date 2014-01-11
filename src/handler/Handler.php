<?php

namespace handler;

use File;

abstract class Handler
{

    protected function sendAsJSON(array $data)
    {
        header('Content-type: application/json');
        echo json_encode($data);
    }

    protected function sendFile(File $file)
    {
        header('Content-type: ' . $file->mime);
        echo $file->fileData;
    }

    protected function getData($key)
    {
        return $_GET[$key];
    }

    protected function postData($key)
    {
        return $_POST[$key];
    }


} 