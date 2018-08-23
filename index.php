<?php
require_once './vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

// init file system
$fsObject = new Filesystem();
$current_dir_path = getcwd();

// make a new directory
try {
    $new_dir_path = $current_dir_path . "/foo";

    if (!$fsObject->exists($new_dir_path))
    {
        $old = umask(0);
        $fsObject->mkdir($new_dir_path, 0775);
        $fsObject->chown($new_dir_path, "www-data");
        $fsObject->chgrp($new_dir_path, "www-data");
        umask($old);
    }
} catch (IOExceptionInterface $exception) {
    echo "Error creating directory at". $exception->getPath();
}

// create a new file and add contents
try {
    $new_file_path = $current_dir_path . "/foo/bar.txt";

    if (!$fsObject->exists($new_file_path))
    {
        $fsObject->touch($new_file_path);
        $fsObject->chmod($new_file_path, 0777);
        $fsObject->dumpFile($new_file_path, "Adding dummy content to bar.txt file.\n");
        $fsObject->appendToFile($new_file_path, "This should be added to the end of the file.\n");
    }
} catch (IOExceptionInterface $exception) {
    echo "Error creating file at". $exception->getPath();
}

// copy a directory
try {
    $src_dir_path = $current_dir_path . "/foo";
    $dest_dir_path = $current_dir_path . "/foo_copy";

    if (!$fsObject->exists($dest_dir_path))
    {
        $fsObject->mirror($src_dir_path, $dest_dir_path);
    }
} catch (IOExceptionInterface $exception) {
    echo "Error copying directory at". $exception->getPath();
}

// remove a directory
try {
    $arr_dirs = array(
        $current_dir_path . "/foo",
        $current_dir_path . "/foo_copy"
    );

    $fsObject->remove($arr_dirs);
} catch (IOExceptionInterface $exception) {
    echo "Error deleting directory at". $exception->getPath();
}
